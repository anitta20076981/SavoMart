<?php

namespace App\Http\Controllers\Admin\Includes\Order;

use App\Http\Requests\Admin\Order\ShipmentCreateRequest;
use App\Http\Requests\Admin\Order\ShipmentListRequest;
use App\Http\Requests\Admin\Order\ShippingViewRequest;
use App\Repositories\Customer\CustomerRepository;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Settings\SettingsRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use stdClass;

trait Shipment
{
    public function shipmentAdd(ShipmentListRequest $request, OrderRepository $orderRepo)
    {
        $breadcrumbs = [
            ['link' => 'admin_order_list', 'name' => 'Order', 'permission' => 'order_read'],
            ['name' => 'Shipment'],
        ];
        $order = $orderRepo->get($request->id);

        $shipmentProducts = [];

        foreach ($order->orderItems as $key => $items) {
            $shipmentId = count($order->shipment) != 0 ? $order->shipment[0]->id : null;
            $shipmentId = $order->shipment ? $shipmentId : null;

            // if ($items->shipped_status == 'complete') {
            //     continue;
            // }

            $shipmentProducts[$key]['product_id'] = $items->product_id;
            $shipmentProducts[$key]['tax_percent'] = $items->tax_percent;
            $shipmentProducts[$key]['order_quantity'] = $items->quantity;
            $shipmentProducts[$key]['quantity'] = $items->quantity - $items->shipped_qty;
            $shipmentProducts[$key]['price'] = $items->unit_price;
            $shipmentProducts[$key]['total'] = $items->unit_price * $items->quantity;
            $shipmentProducts[$key]['name'] = $items->product->name;
            $shipmentProducts[$key]['sku'] = $items->product->sku;
            $shipmentProducts[$key]['order_item_id'] = $items->id;
            $shipmentProducts[$key]['shipped_qty'] = $items->shipped_qty;
            $shipmentProducts[$key]['shipped_status'] = $items->shipped_status;
        }

        return view('admin.order.addShipment', compact('breadcrumbs', 'order', 'shipmentProducts'));
    }

    public function shipmentCreate(
        ShipmentCreateRequest $request,
        OrderRepository $orderRepo,
        SettingsRepository $settingsRepo,
    ) {
        $shipmentNo =  'SHP-' . rand(100000, 999999);
        $order = $orderRepo->get($request->order_id);
        $orderShipmetData = [
            'order_id' => $order->id,
            'shipment_no' => $shipmentNo,
            // 'shipment_method_id' => $order->shipment_method_id,
        ];

        $orderShipmet = $orderRepo->orderShipmentCreate($orderShipmetData);

        foreach ($request->products as $item) {

            $invoiceItems = [
                'shipment_id' => $orderShipmet->id,
                'order_item_id' => $item['order_item_id'],
                'product_id' => $item['product_id'],
                'quantity' => $item['order_qty'],
                'price' => $item['price'],
                'total' => $item['price'] * $item['order_qty'],
            ];
            $shipmentItem = $orderRepo->createShipmentItems($invoiceItems);
        }


        $orderUpdate = [
            'id' => $order->id,
            'status' => 'dispatched',
            'shipment_status' => 'complete',
        ];
        $orderUpdate = $orderRepo->update($orderUpdate);

        return redirect()
            ->route('admin_order_view', ['id' => $order->id])
            ->with('success', 'Invoice created successfully');
    }
}
