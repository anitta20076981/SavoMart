<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\OrderAddRequest;
use App\Http\Requests\Admin\Order\OrderAddressUpdateRequest;
use App\Http\Requests\Admin\Order\OrderCancelRequest;
use App\Http\Requests\Admin\Order\OrderCreateRequest;
use App\Http\Requests\Admin\Order\OrderDeleteRequest;
use App\Http\Requests\Admin\Order\OrderEditRequest;
use App\Http\Requests\Admin\Order\OrderGeneratePaymentLinkRequest;
use App\Http\Requests\Admin\Order\OrderListDataRequest;
use App\Http\Requests\Admin\Order\OrderListRequest;
use App\Http\Requests\Admin\Order\OrderStatusChangeRequest;
use App\Http\Requests\Admin\Order\OrderUpdateRequest;
use App\Http\Requests\Admin\Order\OrderViewRequest;
use App\Repositories\Order\OrderRepositoryInterface as OrderRepository;
use App\Repositories\Settings\SettingsRepositoryInterface as SettingsRepository;
use App\Repositories\Customer\CustomerRepositoryInterface as CustomerRepository;
use App\Repositories\Products\ProductsRepositoryInterface as ProductsRepository;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Admin\Includes\Order\Invoice;
use App\Http\Controllers\Admin\Includes\Order\Shipment;

class OrderController extends Controller
{
    use Invoice;
    use Shipment;

    public function list(OrderListRequest $request)
    {

        $breadcrumbs = [
            ['name' => 'Order'],
        ];

        $status = (isset($request->status) ? $request->status : '');

        return view('admin.order.listOrder', compact('breadcrumbs', 'status'));
    }

    public function table(OrderListDataRequest $request, OrderRepository $orderRepo)
    {
        $orders = $orderRepo->getForDatatable($request->all());
        $dataTableJSON = DataTables::of($orders)
            ->addIndexColumn()
            ->editColumn('created_at', function ($order) {
                return $order->created_at->format('Y-m-d');
            })
            ->editColumn('order_no', function ($order) {
                $data['url'] = request()->user()->can('order_read') ? route('admin_order_view', ['id' => $order->id]) : '';
                // $data['text'] = $order->order_no . ' (' . $order->created_at->format(config('date_format.date_time_display')) . ')';
                $data['text'] = $order->order_no;

                return view('admin.elements.listLink', compact('data'));
            })
            ->addColumn('name', function ($order) {
                return $order->customer ? $order->customer->name : '';
            })
            ->editColumn('order_status', function ($order) {
                return view('admin.order.orderStatus')->with('data', $order['status']);
            })
            ->addColumn('paymentMethod', function ($order) {
                return view('admin.order.paymentStatus')->with('data', $order->payment_type);
            })
            ->addColumn('action', function ($order) use ($request) {
                // $data['edit_url'] = request()->user()->can('order_update') ? route('admin_order_edit', ['id' => $order->id]) : '';
                $data['delete_url'] = request()->user()->can('order_delete') ? route('admin_order_delete', ['id' => $order->id]) : '';

                return view('admin.elements.listAction', compact('data'));
            })
            ->make();

        return $dataTableJSON;
    }

    public function view(OrderRepository $orderRepo, OrderViewRequest $request)
    {
        $breadcrumbs = [
            ['link' => 'admin_order_list', 'name' => 'Order', 'permission' => 'order_read'],
            ['name' => 'Order View'],
        ];
        $order = $orderRepo->get($request->id);

        return view('admin.order.viewOrder', compact('breadcrumbs', 'order'));
    }

    public function cancelOrder(OrderCancelRequest $request, OrderRepository $orderRepo,ProductsRepository $productRepo)
    {
        $orderItems = $orderRepo->getItems($request->id);

        foreach ($orderItems as $items) {
                $incremetProductQty = $productRepo->incrementProductQty($items->product_id, $items->quantity);
        }
        $orderStatusUpdate = [
            'id' => $request->id,
            'status' => 'rejected',
        ];
        $orderStatusUpdate = $orderRepo->update($orderStatusUpdate);

        if ($orderStatusUpdate) {
            return response()->json(['status' => 1, 'message' => ' Order canceled successfully']);
        } else {
            return response()->json(['status' => 0, 'message' => 'Oops! Something went wrong']);
        }
    }

    public function add(
        OrderAddRequest $request,
        CustomerRepository $customerRepo,
    ) {
        $breadcrumbs = [
            ['link' => 'admin_order_list', 'name' => 'Order', 'permission' => 'order_read'],
            ['name' => 'Add Order'],
        ];
        $quote = $request->has('id') ? $quoteRepo->getQuote($request->id) : null;
        $old = [];

        if (old('customer_id')) {
            $old['customer_id'] = $customerRepo->getCustomer(old('customer_id'));
        }

        if ($quote) {
            $old['customer_id'] = $customerRepo->getCustomer($quote->requested_customer_id);
        }

        if (old('payment_method_id')) {
            $old['payment_method_id'] = $payment->get(old('payment_method_id'));
        }


        return view('admin.order.addOrder', compact('breadcrumbs', 'quote', 'old'));
    }

    public function productsTable(
        OrderListDataRequest $request,
        ProductsRepository $productRepo,
        CustomerRepository $customerRepo,
    ) {
        // $customer = isset($request->customer_id) ? $customerRepo->getCustomer($request->customer_id) : null;
        // $request->merge(['customer_group_id' => $customer ? $customer->group_id : null]);
        $products = $productRepo->getAvailableProducts($request->all());
        // dd( $products->get);
        $dataTableJSON = DataTables::of($products)
            ->addIndexColumn()
            ->addColumn('product', function ($product) use ($request, $productRepo) {
        // dd($products);

                return view('admin.order.productList', compact('product'));
            })
            ->addColumn('quantity', function ($product) {
                // return $product->productInventory->quantity;
            })
            ->addColumn('stock_status', function ($product) {
                // return $product->stock_status;
            })
            ->addColumn('checked_status', function ($product) use ($request) {
                // return $request->has('added_product_ids') && in_array($product->id, $request->added_product_ids) ? 'checked' : '';
            })
            ->editColumn('status', function ($products) {
                return view('admin.products.stockStatus')->with('data', $products);
            })
            ->make();

        return $dataTableJSON;
    }

    public function save(
        OrderCreateRequest $request,
        OrderRepository $orderRepo,
        SettingsRepository $settingsRepo,
        CustomerRepository $customerRepo,
        ProductsRepository $productRepo,
    ) {
        try {
            DB::beginTransaction();

            // $customerDetailsUpdateData = [
            //     'customer_id' => $request->customer_id,
            //     'street' => $request->street,
            //     'address_line1' => $request->shipping_address_address_1,
            //     'number' => $request->shipping_address_contact,
            // ];

            // $customerDetails = $customerRepo->customerDetailsUpdate($customerDetailsUpdateData);

            $orderData = [
                    'customer_id' => $request->customer_id,
                    'order_no' => 'GRY-' . rand(100000, 999999),
                    'cart_id' => 0,
                    // 'address_id' => $customerDetails->id,
                    'payment_type' => $request->payment_method_id,
                    'date' => now(),
                    'total_items' => 0,
                    'grand_total' => 0,
                    'status' => 'pending',
            ];
            $order = $orderRepo->saveOrder($orderData);


            $orderAddressData = [
                'order_id' => $order->id,
                'customer_id' => $request->customer_id,
                'details' => $request->street,
                'street_address' => $request->shipping_address_address_1,
                'contact' => $request->shipping_address_contact,
            ];

            $orderAddress = $orderRepo->orderAddressCreate($orderAddressData);
            $orderUpdate = [
                'id' => $order->id,
                'address_id' => $orderAddress->id,
            ];
            $orderUpdate = $orderRepo->update($orderUpdate);

            $mergedProducts = [];

            foreach ($request->products as $product) {
                $productId = $product['product_id'];

                if (isset($mergedProducts[$productId])) {
                    $mergedProducts[$productId]['quantity'] += $product['quantity'];
                } else {
                    $mergedProducts[$productId] = $product;
                }
            }

            $mergedProducts = array_values($mergedProducts);

            // if ($request->has('products')) {
                foreach ($mergedProducts as $product) {
                    if ($product['product_id']) {

                        $productData =   $productRepo->get($product['product_id']);
                        if($product['quantity'] > $productData->quantity){
                            // return redirect()->back()->with('error', 'Please check quatity before order a productin');
                        }
                        $orderItemData = [
                            'cart_item_id' => 0,
                            'order_id' => $order->id,
                            'product_id' => $product['product_id'],
                            'quantity' => $product['quantity'],
                            'unit_price' => $productData->price,
                            'total_price' => $product['quantity']*$productData->price,
                        ];
                        $orderItem = $orderRepo->saveOrderItem($orderItemData);

                        //product qty minus
                        $updateProductQty = [
                            'id' => $product['product_id'],
                            'quantity' => $productData->quantity - $product['quantity']
                        ];
                        $updateProductQty = $productRepo->update($updateProductQty);

                    }
                }
            // }

            $order = $orderRepo->orderDataUpdate($order->id);

        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
        DB::commit();

        return redirect()
            ->route('admin_order_list')
            ->with('success', 'Order added successfully');
    }

//     private function _haveTaxRate($taxRate, $shippingAddress)
//     {
//         $haveTaxRate = false;

//         if ($taxRate->is_zip == 'no') {
//             if ($shippingAddress->postel_code == $taxRate->zip_code) {
//                 return $haveTaxRate = true;
//             }
//         } elseif (
//             $shippingAddress->postel_code >= $taxRate->zip_from
//             && $shippingAddress->postel_code <= $taxRate->zip_to
//         ) {
//             return $haveTaxRate = true;
//         }
//     }

//     public function edit(
//         OrderEditRequest $request,
//         OrderRepository $orderRepo,
//         PaymentMethodRepository $paymentMethod,
//         ShipmentMethodRepository $shipmentMethodRepo,
//         CustomerRepository $customerRepo,
//         StateRepository $stateRepo
//     ) {
//         $breadcrumbs = [
//             ['link' => 'admin_order_list', 'name' => 'Order', 'permission' => 'order_read'],
//             ['name' => 'Order Details'],
//         ];
//         $order = $orderRepo->get($request->id);

//         $old = [];

//         if (old('payment_method_id', isset($order->orderPayment->payment_method_id))) {
//             $old['payment_method_id'] = $paymentMethod->get(old('payment_method', $order->orderPayment->payment_method_id));
//         }

//         if (old('shipment_method_id', isset($order->orderShipment->shipment_method_id))) {
//             $old['shipment_method_id'] = $shipmentMethodRepo->get(old('shipment_method', $order->orderShipment->shipment_method_id));
//         }

//         if (old('customer_id', isset($order->customer_id))) {
//             $old['customer_id'] = $customerRepo->getCustomer(old('customer_id', $order->customer_id));
//         }

//         if (old('state', isset($order->billingAddress->state))) {
//             $old['state'] = $stateRepo->getState(old('state_id', $order->billingAddress->state));
//         }

//         if (old('shipment_state', isset($order->shippingAddress->state))) {
//             $old['shipment_state'] = $stateRepo->getState(old('shipment_state', $order->shippingAddress->state));
//         }

//         $productId = $orderRepo->orderProductId($request->id)->toArray();

//         return view('admin.order.editOrder', compact('order', 'breadcrumbs', 'old', 'productId'));
//     }

//     public function editproductsTable(
//         OrderListDataRequest $request,
//         OrderRepository $orderRepo,
//         ProductsRepository $productRepo,
//         CustomerRepository $customerRepo,
//     ) {
//         $selectedProductId = $orderRepo->orderProductId($request->order_id);
//         $customer = isset($request->customer_id) ? $customerRepo->getCustomer($request->customer_id) : null;
//         $request->merge(['customer_group_id' => $customer ? $customer->group_id : null]);
//         $products = $productRepo->getAvailableProducts($request->all());
//         $selectedproducts = $productRepo->getSelectedProducts($selectedProductId, $request->all());
//         $mergeProductTbl = $selectedproducts->union($products);
//         $dataTableJSON = DataTables::of($mergeProductTbl)
//             ->addIndexColumn()
//             ->addColumn('product', function ($product) use ($customer, $productRepo) {
//                 return view('admin.order.productList', compact('product'));
//             })
//             ->addColumn('quantity', function ($product) {
//                 return $product->productInventory->quantity;
//             })
//             ->make();

//         return $dataTableJSON;
//     }

//     public function update(
//         OrderUpdateRequest $request,
//         OrderRepository $orderRepo,
//         SettingsRepository $settingsRepo,
//         CustomerRepository $customerRepo,
//         ProductsRepository $productRepo,
//         TaxRepository $taxRepo
//     ) {
//         try {
//             DB::beginTransaction();

//             $order = $orderRepo->get($request->id);

//             $updateData = [
//                 'id' => $request->id,
//                 'order_no' => $request->order_no,
//                 'customer_id' => $request->customer_id,
//                 'status' => 'placed',
//                 'cart_id' => 0,
//                 'sub_total' => $request->total_price,
//                 'grand_total' => $request->total_price,
//             ];

//             $order = $orderRepo->update($updateData);
//             $country = $settingsRepo->getByKey('country_id');
//             $customer = $customerRepo->getCustomer($request->customer_id);

//             $billingAddressData = [
//                 'id' => $request->billing_address_id,
//                 'order_id' => $request->order_id,
//                 'first_name' => $customer->first_name,
//                 'last_name' => $customer->last_name,
//                 'street_address' => $request->billing_address_address_1,
//                 'country' => $country['value'],
//                 'state' => $request->billing_address_state,
//                 'city' => $request->billing_address_city,
//                 'postel_code' => $request->billing_address_postcode,
//                 'contact' => $request->billing_address_contact,
//                 'type' => 'BILLING',
//             ];

//             $billingAddress = $orderRepo->updateBillingOrShippingAddress($billingAddressData);

//             $shippingAddressData = [
//                 'id' => $request->shipping_address_id,
//                 'order_id' => $request->order_id,
//                 'name_prefix' => $request->name_prefix,
//                 'name_suffix' => $request->name_suffix,
//                 'first_name' => $customer->first_name,
//                 'last_name' => $customer->last_name,
//                 'company' => null,
//                 'street_address' => $request->shipping_address_address_1,
//                 'country' => $country['value'],
//                 'state' => $request->shipping_address_state,
//                 'city' => $request->shipping_address_city,
//                 'postel_code' => $request->shipping_address_postcode,
//                 'contact' => $request->shipping_address_contact,
//                 'type' => 'SHIPMENT',
//             ];

//             $shippingAddress = $orderRepo->updateBillingOrShippingAddress($shippingAddressData);

//             $slectedProductId = [];

//             foreach ($request->products as $data) {
//                 $product = $productRepo->get($data['product_id']);
//                 $haveTaxRate = false;

//                 if ($product->taxCategory != null) {
//                     $taxRateIds = $taxRepo->getTaxRateIdsFromTaxCategory($product->taxCategory->id);
//                     $taxRate = $taxRepo->getTaxByTaxRate($taxRateIds);
//                     $haveTaxRate = $this->_haveTaxRate($taxRate, $shippingAddress);
//                 }
//                 $taxPercent = ($haveTaxRate == true) ? $taxRate->tax_rate : 0;
//                 $taxAmount = ($haveTaxRate == true) ? (round((($data['price'] * $data['quantity']) * $taxRate->tax_rate) / 100, 4)) : 0;
//                 /**quantity-decrement */
//                 $this->_qtyCalculationForUpdate($request->order_id, $data['product_id'], isset($data['id']) ? $data['id'] : '', $orderRepo, $data['quantity'], $product);
//                 $items = [
//                     'id' => isset($data['id']) ? $data['id'] : '',
//                     'order_id' => $request->order_id,
//                     'product_id' => $data['product_id'],
//                     'price' => $data['price'],
//                     'quantity' => $data['quantity'],
//                     'total' => $data['price'] * $data['quantity'],
//                     'tax_amount' => $taxAmount,
//                     'grand_total' => ($data['price'] * $data['quantity']) + $taxAmount,
//                     'base_price' => $data['base-price'],
//                 ];

//                 $orderItems = $orderRepo->updateItems($items);
//                 $slectedProductId[] = $data['product_id'];
//             }

//             $deleteOrderItem = $orderRepo->deleteOrderItems($slectedProductId, $request->order_id);

//             /** order-grand-total update */
//             $orderGrandTotalUpdate = [
//                 'id' => $request->order_id,
//                 'grand_total' => $orderRepo->orderItemGrandTotalSum($request->order_id),
//             ];
//             $order = $orderRepo->update($orderGrandTotalUpdate);

//             $paymentMethod = [
//                 'id' => $order->orderPayment->id,
//                 'order_id' => $request->order_id,
//                 'payment_method_id' => $request->payment_method_id,
//             ];

//             $paymentMethods = $orderRepo->orderPaymentMethodUpdate($paymentMethod);

//             $shipmentMethod = [
//                 'id' => $order->orderShipment->id,
//                 'order_id' => $request->order_id,
//                 'shipment_method_id' => $request->shipment_method_id,
//             ];
//             $shipmentMethod = $orderRepo->orderShipmentMethodUpdate($shipmentMethod);

//             activity()->performedOn($order)->event('Order Updated')->withProperties(['order_id' => $order->id, 'data' => $updateData])->log('Order Updated');
//         } catch (Exception $e) {
//             DB::rollBack();

//             return redirect()->back()->with('error', $e->getMessage());
//         }
//         DB::commit();

//         return redirect()
//             ->route('admin_order_list')
//             ->with('success', 'Order updated successfully');
//     }

//     private function _qtyCalculationForUpdate($orderId, $productId, $Id, $orderRepo, $qty, $product)
//     {
//         if (config('settings.inventory.cataloginventory_options_can_subtract') == 1) {
//             if ($Id != '') {
//                 /**already added product */
//                 $orderItem = $orderRepo->getOrderItem($orderId, $productId);
//                 $oldQty = $orderItem->quantity;

//                 if ($oldQty < $qty) {
//                     $quantity = $qty - $oldQty;
//                     $updateQty = $product->productInventory->quantity - $quantity;
//                 } else {
//                     $quantity = $oldQty - $qty;
//                     $updateQty = $product->productInventory->quantity + $quantity;
//                 }
//                 $data = [];
//                 $data['id'] = $product->productInventory->id;
//                 $data['updateQty'] = $updateQty;
//                 $data['type'] = 'order';
//                 Artisan::call('update:stock', ['--data' => $data]);
//             } else {
//                 /**new product */
//                 $updateQty = $product->productInventory->quantity - $qty;
//                 $data = [];
//                 $data['id'] = $product->productInventory->id;
//                 $data['updateQty'] = $updateQty;
//                 $data['type'] = 'order';
//                 Artisan::call('update:stock', ['--data' => $data]);
//             }
//         }
//     }

//     public function delete(OrderRepository $orderRepo, OrderDeleteRequest $request, ProductsRepository $productRepo)
//     {
//         $order = $orderRepo->get($request->id);

//         foreach ($order->orderItems as $items) {
//             $productRepo->incrementProductQty($items->product_id, $items->quantity);
//         }

//         activity()->performedOn($order)->event('Order Deleted')->withProperties(['order_id' => $order->id])->log('Order Deleted');

//         if ($orderRepo->delete($request->id)) {
//             if ($request->ajax()) {
//                 return response()->json(['status' => 1, 'message' => 'Order deleted successfully']);
//             } else {
//                 return redirect()->route('admin_order_list')->with('success', 'Order deleted successfully');
//             }
//         }

//         if ($request->ajax()) {
//             return response()->json(['status' => 0, 'message' => 'Failed to delete']);
//         } else {
//             return redirect()->route('admin_order_list')->with('success', 'Failed to delete');
//         }
//     }



//     public function orderedProductsTable(
//         OrderListDataRequest $request,
//         OrderRepository $orderRepo
//     ) {
//         $curreny = config('app.currency.symbol');
//         $ordersItems = $orderRepo->getOrderdeProducts($request->order_id);
//         $dataTableJSON = DataTables::of($ordersItems)
//             ->addIndexColumn()
//             ->addColumn('product', function ($ordersItem) {
//                 return view('admin.order.orderedProductList', compact('ordersItem'));
//             })
//             ->addColumn('sku', function ($ordersItem) {
//                 return $ordersItem->product->sku;
//             })
//             ->addColumn('quantity', function ($ordersItem) {
//                 return $ordersItem->quantity;
//             })
//             ->addColumn('unit_price', function ($ordersItem) {
//                 return formatAmount($ordersItem->price);
//             })
//             ->addColumn('total', function ($ordersItem) {
//                 return formatAmount($ordersItem->total);
//             })
//             ->make();

//         return $dataTableJSON;
//     }

    public function deliveryOrder(OrderStatusChangeRequest $request, OrderRepository $orderRepo)
    {
        $order = $orderRepo->get($request->order_id);

        $orderUpdate = [
            'id' => $request->order_id,
            'status' => 'delivered',
            'delivery_date' => Carbon::now(),
        ];

        $orderUpdate = $orderRepo->update($orderUpdate);

        if ($orderUpdate) {
            return response()->json(['status' => 1, 'message' => ' Order Delivered']);
        } else {
            return response()->json(['status' => 0, 'message' => 'Oops! Something went wrong']);
        }
    }

//     public function addressEdit(
//         Request $request,
//         OrderRepository $orderRepo,
//         CountryRepository $countryRepo,
//         StateRepository $stateRepo
//     ) {
//         $orderId = $request->id;
//         $orderAddress = $orderRepo->orderAddress($orderId, $request->type);
//         $old = [];

//         if (old('country', isset($orderAddress->country))) {
//             $old['country'] = $countryRepo->getCountry(old('country', $orderAddress->country));
//         }

//         if (old('state', isset($orderAddress->state))) {
//             $old['state'] = $stateRepo->getState(old('state', $orderAddress->state));
//         }
//         $responce['html'] = (string) view('admin.order.addressEdit', compact('orderAddress', 'old'));

//         $responce['scripts'][] = (string) mix('js/admin/order/addressEdit.js');

//         return $responce;
//     }

//     public function addressUpdate(OrderAddressUpdateRequest $request, OrderRepository $orderRepo)
//     {
//         $updateData = [
//             'order_id' => $request->order_id,
//             'first_name' => $request->first_name,
//             'last_name' => $request->last_name,
//             'company' => $request->company,
//             'street_address' => $request->street_address,
//             'country_id' => $request->country_id,
//             'state_id' => $request->state_id,
//             'city' => $request->city,
//             'postel_code' => $request->postel_code,
//             'contact' => $request->contact,
//             'type' => $request->type,
//         ];
//         $customerAddress = $orderRepo->updateOrderAddressUpdate($updateData);

//         return response()->json(['status' => 1, 'message' => 'Order Address Updated Successfully']);
//     }

//

//     public function pendingList(OrderListRequest $request)
//     {
//         $breadcrumbs = [
//             ['name' => 'Pending Orders'],
//         ];

//         return view('admin.order.listPendingOrder', compact('breadcrumbs'));
//     }

//     public function pendingTable(OrderListDataRequest $request, OrderRepository $orderRepo)
//     {
//         $orders = $orderRepo->getForPendingDatatable($request->all());
//         $dataTableJSON = DataTables::of($orders)
//             ->addIndexColumn()
//             ->editColumn('order_no', function ($order) {
//                 $data['url'] = request()->user()->can('order_read') ? route('admin_order_view', ['id' => $order->id]) : '';
//                 $data['text'] = $order->order_no . '(' . $order->created_at->format(config('settings.config.date_only_display')) . ')';

//                 return view('admin.elements.listLink', compact('data'));
//             })
//             ->addColumn('order_status', function ($order) {
//                 return $order['status'];
//             })
//             ->addColumn('name', function ($order) {
//                 return $order->customer->name;
//             })
//             ->editColumn('order_status', function ($order) {
//                 return view('admin.order.orderStatus')->with('data', $order['status']);
//             })
//             ->addColumn('action', function ($order) use ($request) {
//                 $data['delete_url'] = request()->user()->can('order_delete') ? route('admin_order_delete', ['id' => $order->id]) : '';

//                 return view('admin.elements.listAction', compact('data'));
//             })
//             ->make();

//         return $dataTableJSON;
//     }

//     public function generatePaymentLink(OrderGeneratePaymentLinkRequest $request, OrderRepository $orderRepo, PaymentMethodRepository $paymentMethodRepo)
//     {
//         $paymentHandler = new PaymentHandler();
//         $order = $orderRepo->get($request->order_id);
//         $paymentLink = $paymentHandler->generatePaymentLink(['order' => $order]);

//         return response()->json(['status' => 1, 'data' => ['short_url' => $paymentLink->short_url], 'message' => ' Order canceled successfully']);
//     }

//     public function recentOrders(OrderListDataRequest $request, OrderRepository $orderRepo)
//     {
//         $orders = $orderRepo->getRecentOrders($request->all());
//         $dataTableJSON = DataTables::of($orders)
//             ->addIndexColumn()
//             ->editColumn('created_at', function ($order) {
//                 return $order->created_at->format(config('date_format.date_time_display'));
//             })
//             ->editColumn('order_no', function ($order) {
//                 $data['url'] = request()->user()->can('order_read') ? route('admin_order_view', ['id' => $order->id]) : '';
//                 $data['text'] = $order->order_no;

//                 return view('admin.elements.listLink', compact('data'));
//             })
//             ->addColumn('order_status', function ($order) {
//                 return $order['status'];
//             })
//             ->addColumn('name', function ($order) {
//                 return $order->customer ? $order->customer->name : '';
//             })
//             ->editColumn('order_status', function ($order) {
//                 return view('admin.order.orderStatus')->with('data', $order['status']);
//             })
//             ->make();

//         return $dataTableJSON;
//     }

//     public function orderGraph(Request $request, OrderRepository $orderRepo)
//     {
//         $filter = $request->all();
//         $salesValue = $orderRepo->getTotalSalesGraph($filter);

//         if ($filter['filter_type'] == 'month') {
//             for ($month = 1; $month <= 12; $month++) {
//                 $graphDataArray['labels'][] = date('M', strtotime($filter['year'] . '-' . $month . '-01'));
//                 $graphDataArray['values'][$month] = 0;
//             }

//             foreach ($salesValue as $key => $monthSale) {
//                 $graphDataArray['values'][$monthSale->monthname] = $monthSale->grandTotal;
//             }
//             $graphDataArray['values'] = array_values($graphDataArray['values']);
//         } else {
//             $graphDataArray['labels'] = ['Mon', 'Tue', 'Wed', 'Thu', 'Friy', 'Sat', 'Sun'];
//             $graphDataArray['values'] = array_fill(0, 7, 0);

//             foreach ($salesValue as $key => $weekSale) {
//                 $dayOfWeek = date('N', strtotime($weekSale->created_at));
//                 $graphDataArray['values'][$dayOfWeek - 1] = $weekSale->grandTotal;
//             }
//             $graphDataArray['values'] = array_values($graphDataArray['values']);
//         }

//         return response()->json(['status' => 1, 'data' => $graphDataArray, 'message' => 'success']);

//     }
    public function quantityUpdate(Request $request, OrderRepository $orderRepo)
    {
        $orderItem = $orderRepo->getOrderItem($request->order_item_id);
        $updateOrderItem = [
            'id' => $request->order_item_id,
            'quantity' => $request->quantity,
            'total_price' => $orderItem->unit_price * $request->quantity,
        ];

        $updateOrderItem = $orderRepo->updateOrderItem($updateOrderItem);
        $orderItemGrandTotal = $orderRepo->orderItemGrandTotalSum($request->order_id);

        $updateOrder = [
            'id' => $request->order_id,
            'grand_total' => $orderItemGrandTotal,
        ];
        $updateOrder = $orderRepo->update($updateOrder);

        return response()->json(['status' => true]);
    }
}
