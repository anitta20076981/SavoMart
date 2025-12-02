<?php

namespace App\Repositories\Order;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderInvoice;
use App\Models\OrderInvoiceItem;
use App\Models\OrderShipments;
use App\Models\OrderShipmentItems;
use App\Models\OrderAddress;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class OrderRepository implements OrderRepositoryInterface
{

    public function getForDatatable($data)
    {
        return Order::with(['customer'])->select(['*'])
            ->latest()
            ->where(function (Builder $query) use ($data) {
                if (isset($data['status']) && $data['status'] != '') {
                    $query->where('status', '=', $data['status']);
                }

                if (isset($data['customer_id']) && $data['customer_id'] != '') {
                    $query->where('customer_id', '=', $data['customer_id']);
                }
            });
    }


    public function getCustomerActiveOrder($requestData)
    {
        $orders  = Order::with(['orderItems', 'orderItems.product', 'location'])->where('customer_id',$requestData['customer_id']);
        if (isset($requestData['offset']) && isset($requestData['limit'])) {
            $orders = $orders->offset($requestData['offset'])->limit($requestData['limit']);
        }
        if (isset($requestData['search_text']) && isset($requestData['search_text'])) {
            $orders = $orders->where('order_no', 'like', "%{$requestData['search_text']}%");
        }
        return $orders->orderBy('created_at', 'desc')->get();
    }

    public function saveOrder($input)
    {
        if ($order = Order::create($input)) {
            return $order;
        }

        return $order;
    }

    public function saveOrderItem($input)
    {
        if ($orderitems = OrderItem::create($input)) {
            return $orderitems;
        }

        return $orderitems;
    }

    public function update(array $input)
    {
        $order = Order::withOutGlobalScope('notPendingOrder')->find($input['id']);
        unset($input['id']);

        if ($order->update($input)) {
            return $order;
        }

        return false;
    }

    public function get($orderId)
    {
        return Order::where('id', $orderId)->with(['orderItems', 'orderItems.product'])->first();
    }

    public function getCustomerProductOrderItem($customerId, $productId)
    {
        $order = Order::where('customer_id', $customerId)->latest()->first();

        if ($order) {
            $orderItem = OrderItem::where('order_id', $order->id)->where('product_id', $productId)->first();

            return $orderItem;
        } else {
            return null;
        }
    }

    public function updateOrderItem($input)
    {
        $orderItem = OrderItem::find($input['id']);
        unset($input['id']);

        if ($orderItem->update($input)) {
            return $orderItem;
        }

        return false;
    }

    public function getOrderItem($orderItemId)
    {
        return OrderItem::where('id', $orderItemId)->first();
    }

    public function getOrderItemByProduct($product_id,$order_id)
    {
        return OrderItem::where('product_id', $product_id)->where('order_id',$order_id)->first();
    }

    public function updateOrder($input)
    {
        $order = Order::find($input['id']);
        unset($input['id']);

        if ($order->update($input)) {
            return $order;
        }

        return false;
    }

    public function orderItemTotal($orderId, $column)
    {
        return OrderItem::where('order_id', $orderId)->sum($column);
    }


    public function getOrderWithCustomerAndProduct($customerId, $productId)
    {
        $order = Order::where('customer_id', $customerId)->latest()->first();

        if ($order) {
            $orderItem = OrderItem::where('order_id', $order->id)->where('product_id', $productId)->first();

            return $orderItem;
        } else {
            return null;
        }
    }

    public function orderItemDelete($orderItemId)
    {
        $orderItem = OrderItem::findOrFail($orderItemId);

        return $orderItem->delete();
    }

    public function updateOrderItemId($orderId, $updateOrderId)
    {
        OrderItem::where('order_id', $orderId)->update(['order_id' => $updateOrderId]);
    }

    public function delete($orderId)
    {
        $order = Order::with(['orderItems'])->findOrFail($orderId);
        $order->orderItems()->delete();

        return $order->delete();
    }

    public function getAllOrderItems($orderId)
    {
        return OrderItem::with(['product'])->where('order_id', $orderId)->get();
    }

    public function getOrderItemByProductId($orderId,$product_id)
    {
        return OrderItem::where('order_id', $orderId)->where('product_id',$product_id)->first();
    }

    public function deleteOrderItemByProductId($orderId,$product_id)
    {
        return OrderItem::with(['product'])->where('order_id', $orderId)->where('product_id',$product_id)->delete();
    }

    public function orderDataUpdate($orderId)
    {
        // Retrieve the order based on the given conditions
        $order = Order::where('id', $orderId)->first();

        if ($order) {
            // Calculate the sum of quantities and count of items
            $sumQuantity = OrderItem::where('order_id', $orderId)->sum('total_price');
            $count = OrderItem::where('order_id', $orderId)->count();

            // Update the order fields
            $order->update([
                'total_items' => $count,
                'grand_total' => $sumQuantity,
            ]);

            $order->refresh();
            return $order;
        }
    }

    public function getByCartId($cartId)
    {
        // Retrieve the order based on the given conditions
        return Order::where('cart_id', $cartId)->whereIn('status', ['pending', 'dispatched'])->first();
    }

    public function getActiveOrder($orderId)
    {
        // Retrieve the order based on the given conditions
        return Order::where('id', $orderId)->whereIn('status', ['pending', 'dispatched'])->first();
    }

    public function orderItemGrandTotalSum($orderId)
    {
        return OrderItem::where('order_id', $orderId)->sum('total_price');
    }

    public function createInvoice($input)
    {
        if ($invoice = OrderInvoice::create($input)) {
            return $invoice;
        }

        return false;
    }

    public function createInvoiceItems($input)
    {
        if ($items = OrderInvoiceItem::create($input)) {
            return $items;
        }

        return false;
    }

    public function invoice($requestData)
    {
        return OrderInvoice::with('invoiceItems', 'invoiceItems.product')->where('id', $requestData['id'])->first();
    }

    public function invoiceItems($requestData)
    {
        return OrderInvoiceItem::with('invoice')
            ->where('invoice_id', $requestData['id'])->get();
    }

    public function orderShipmentCreate($input)
    {
        if ($orderShipments = OrderShipments::create($input)) {
            return $orderShipments;
        }

        return false;
    }

    public function createShipmentItems($input)
    {
        if ($items = OrderShipmentItems::create($input)) {
            return $items;
        }

        return false;
    }

    public function shippingItems($requestData)
    {
        return OrderShipmentItems::with('shipment')
            ->where('shipment_id', $requestData['id'])->get();
    }


    public function shipment($requestData)
    {
        return OrderShipments::with('shippingItems')->where('id', $requestData['id'])->first();
    }

    public function orderItems($orderItemId)
    {
        return OrderItem::where('id', $orderItemId)->first();
    }

    public function topSellingProducts()
    {

        $topSellingProducts = OrderItem::with(['product'])->select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(total_price) as all_price'))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->take(5)
            ->get();

        return $topSellingProducts ;
    }

    public function recentOrders()
    {
        return Order::with(['customer'])->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
    }

    public function getItems($ordeId)
    {
        return OrderItem::with(['product'])->where('order_id', $ordeId)->get();
    }

    public function orderAddressCreate($input)
    {
        if ($orderAddress = OrderAddress::create($input)) {
            return $orderAddress;
        }

        return $orderAddress;
    }

}
