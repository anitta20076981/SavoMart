<?php

namespace App\Http\Controllers\Admin\Includes\Order;

use App\Http\Requests\Admin\Order\InvoiceCreateRequest;
use App\Http\Requests\Admin\Order\InvoiceListRequest;
use App\Http\Requests\Admin\Order\InvoiceViewRequest;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Settings\SettingsRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use PDF;
use stdClass;

trait Invoice
{
    public function invoiceAdd(InvoiceListRequest $request, OrderRepository $orderRepo)
    {
        $breadcrumbs = [
            ['link' => 'admin_order_list', 'name' => 'Order', 'permission' => 'order_read'],
            ['name' => 'Invoice'],
        ];
        $order = $orderRepo->get($request->id);

        $invoiceProducts = [];
        $subTotal = 0;
        $totalTaxAmount = 0;
        $grandTotal = 0;

        foreach ($order->orderItems as $key => $items) {

            if ($items->invoice_status == 'complete') {
                continue;
            }
            $invoiceQty = 0;
            $invoiceProducts[$key]['product_id'] = $items->product_id;
            $invoiceProducts[$key]['id'] = $items->id;
            $invoiceProducts[$key]['price'] = $items->unit_price;
            $invoiceProducts[$key]['quantity'] = $items->quantity;
            $invoiceProducts[$key]['tax_percent'] = $items->tax_percent;
            $invoiceProducts[$key]['tax_amount'] = $items->tax_amount;

            $invoiceProducts[$key]['quantity'] = $items->quantity - $items->invoiced_qty;

            $taxAmount = ($items->price * $invoiceProducts[$key]['quantity'] * $items->tax_percent) / 100;
            $invoiceProducts[$key]['grand_total'] = ($invoiceProducts[$key]['quantity'] * $items->price) + $taxAmount;
            $invoiceProducts[$key]['name'] = $items->product->name;
            $invoiceProducts[$key]['sku'] = $items->product->sku;
            $subTotal += $items->unit_price * $invoiceProducts[$key]['quantity'];
            $totalTaxAmount += $taxAmount;
        }
        $grandTotal = $subTotal + $totalTaxAmount;

        return view('admin.order.addInvoice', compact('breadcrumbs', 'order', 'invoiceProducts', 'subTotal', 'totalTaxAmount', 'grandTotal'));
    }

    public function invoiceCreate(InvoiceCreateRequest $request, OrderRepository $orderRepo, SettingsRepository $settingsRepo)
    {
        $invoiceNo = 'INV-' . rand(100000, 999999);
        try {
            DB::beginTransaction();
            $order = $orderRepo->get($request->order_id);
            $invoice_total_amount = 0;
            $invoice_tax_amount = 0;
            $invoiceData = [
                'invoice_no' => $invoiceNo,
                'order_id' => $request->order_id,
                'grand_total' => $orderRepo->orderItemGrandTotalSum($order->id),
                'total_tax_amount' => 0,
            ];
            $invoice = $orderRepo->createInvoice($invoiceData);

            foreach ($request->products as $item) {

                $invoiceItems = [
                    'invoice_id' => (!isset($invoice)) ? $order->invoice->id : $invoice->id,
                    'order_item_id' => $item['order_item_id'],
                    'product_id' => $item['product_id'],
                    'quantity' => $item['order_qty'],
                    // 'unit_price' => $item['unit_price'],
                    'tax_amount' => 0,
                    'total_amount' =>  ($item['unit_price'] * $item['order_qty']),
                ];
                $invoice_total_amount += $invoiceItems['total_amount'];
                $invoiceItems = $orderRepo->createInvoiceItems($invoiceItems);

            }

            $order = $orderRepo->get($request->order_id);

            if ($order->status == 'pending') {
                $orderUpdate = [
                    'id' => $order->id,
                    'status' => 'processing',
                ];
                $orderUpdate = $orderRepo->update($orderUpdate);
            }
            $orderUpdate = [
                'id' => $order->id,
                'invoice_status' => 'complete',
            ];
            $orderUpdate = $orderRepo->update($orderUpdate);





            //invoicePdfGeneration
            $settings = $settingsRepo->getAll()->keyBy('key');
            $currency = $settingsRepo->getCurrency($settings->get('currency_id')->value);
            $invoicedata = $orderRepo->invoice(['id' => $invoice->id]);
            $filePath = 'order/invoice/';

            $pdf = PDF::loadView('admin.order.invoicepdfTemplate', ['invoicedata' => $invoicedata, 'settings' => $settings, 'currency' => $currency, 'order' => $order, 'invoice_total_amount' => $invoice_total_amount, 'invoice_tax_amount' => $invoice_tax_amount]);
            Storage::disk('savomart')->put($filePath . $invoiceNo . '_invoice.pdf', $pdf->output());


        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
        DB::commit();

        return redirect()
            ->route('admin_order_view', ['id' => $order->id])
            ->with('success', 'Invoice created successfully');
    }

    public function invoiceView(InvoiceViewRequest $request, OrderRepository $orderRepo)
    {
        $breadcrumbs = [
            ['link' => 'admin_order_list', 'name' => 'Invoice', 'permission' => 'order_read'],
            ['name' => 'Invoice'],
        ];
        $invoiceItems = $orderRepo->invoiceItems($request->all());
        $invoice = $orderRepo->invoice($request->all());

        return view('admin.order.viewInvoice', compact('breadcrumbs', 'invoiceItems', 'invoice'));
    }
}