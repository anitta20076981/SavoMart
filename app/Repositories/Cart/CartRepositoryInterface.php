<?php

namespace App\Repositories\Cart;

interface CartRepositoryInterface
{
    public function getCustomerActiveCart($customerId);

    public function saveCart($input);

    public function saveCartItem($input);

    public function get($cartId);

    public function getCustomerProductCartItem($customerId, $productId);

    public function updateCartItem($input);

    public function getCartItem($cartItemId);

    public function updateCart($input);

    public function cartItemTotal($cartId, $column);

    public function getCartWithCustomerAndProduct($customerId, $productId);

    public function cartItemDelete($cartItemId);

    public function updateCartItemId($cartId, $updateCartId);

    public function delete($cartId);

    public function getAllCartItems($cartId);

    public function getCartItemByProductId($cartId,$product_id);

    public function deleteCartItemByProductId($cartId,$product_id);

    public function cartDataUpdate($cartId);

    public function getActiveCartCount($customerId, $productId);
}
