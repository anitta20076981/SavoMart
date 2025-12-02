<?php

namespace App\Repositories\Order;

interface OrderRepositoryInterface
{

    public function getForDatatable($data);

    public function getCustomerActiveOrder($requestData);

    public function saveOrder($input);

    public function saveOrderItem($input);

    public function update(array $input);

    public function get($orderId);

    public function getCustomerProductOrderItem($customerId, $productId);

    public function updateOrderItem($input);

    public function getOrderItem($orderItemId);

    public function updateOrder($input);

    public function orderItemTotal($orderId, $column);

    public function getOrderWithCustomerAndProduct($customerId, $productId);

    public function orderItemDelete($orderItemId);

    public function updateOrderItemId($orderId, $updateOrderId);

    public function delete($orderId);

    public function getAllOrderItems($orderId);

    public function getOrderItemByProductId($orderId,$product_id);

    public function deleteOrderItemByProductId($orderId,$product_id);

    public function orderDataUpdate($orderId);

    public function orderItemGrandTotalSum($orderId);

    public function createInvoice($input);

    public function createInvoiceItems($input);

    public function invoiceItems($requestData);

    public function orderShipmentCreate($input);

    public function createShipmentItems($input);

    public function shippingItems($requestData);

    public function shipment($requestData);

    public function orderItems($orderItemId);

    public function topSellingProducts();

    public function recentOrders();

    public function invoice($requestData);

    public function getItems($ordeId);

    public function orderAddressCreate($input);

}
