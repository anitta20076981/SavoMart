<?php

namespace App\Repositories\Cart;

use App\Models\Cart;
use App\Models\CartItem;

class CartRepository implements CartRepositoryInterface
{

    public function getCustomerActiveCart($customerId)
    {
        return Cart::where('status', 'active')->where('cart_status', "in_cart")->where('customer_id', $customerId)->first();
    }

    public function saveCart($input)
    {
        if ($cart = Cart::create($input)) {
            return $cart;
        }

        return $cart;
    }

    public function saveCartItem($input)
    {
        if ($cartitems = CartItem::create($input)) {
            return $cartitems;
        }

        return $cartitems;
    }

    public function get($cartId)
    {
        return Cart::where('id', $cartId)->with(['cartItems', 'cartItems.product'])->where('status', 'active')->first();
    }

    public function getCustomerProductCartItem($customerId, $productId)
    {
        $cart = Cart::where('customer_id', $customerId)->latest()->first();

        if ($cart) {
            $cartItem = CartItem::where('cart_id', $cart->id)->where('product_id', $productId)->first();

            return $cartItem;
        } else {
            return null;
        }
    }

    public function updateCartItem($input)
    {
        $cartItem = CartItem::find($input['id']);
        unset($input['id']);

        if ($cartItem->update($input)) {
            return $cartItem;
        }

        return false;
    }

    public function getCartItem($cartItemId)
    {
        return CartItem::where('id', $cartItemId)->first();
    }

    public function getCartItemByProduct($product_id,$cart_id)
    {
        return CartItem::where('product_id', $product_id)->where('cart_id',$cart_id)->first();
    }

    public function updateCart($input)
    {
        $cart = Cart::find($input['id']);
        unset($input['id']);

        if ($cart->update($input)) {
            return $cart;
        }

        return false;
    }

    public function cartItemTotal($cartId, $column)
    {
        return CartItem::where('cart_id', $cartId)->sum($column);
    }


    public function getCartWithCustomerAndProduct($customerId, $productId)
    {
        $cart = Cart::where('customer_id', $customerId)->where('status','active')->latest()->first();
        if ($cart) {
            $cartItem = CartItem::where('cart_id', $cart->id)->where('product_id', $productId)->first();

            return $cartItem;
        } else {
            return null;
        }
    }

    public function cartItemDelete($cartItemId)
    {
        $cartItem = CartItem::findOrFail($cartItemId);

        return $cartItem->delete();
    }

    public function updateCartItemId($cartId, $updateCartId)
    {
        CartItem::where('cart_id', $cartId)->update(['cart_id' => $updateCartId]);
    }

    public function delete($cartId)
    {
        $cart = Cart::with(['cartItems'])->findOrFail($cartId);
        $cart->cartItems()->delete();

        return $cart->delete();
    }

    public function getAllCartItems($cartId)
    {
        return CartItem::with(['product'])->where('cart_id', $cartId)->get();
    }

    public function getCartItemByProductId($cartId,$product_id)
    {
        return CartItem::where('cart_id', $cartId)->where('product_id',$product_id)->first();
    }

    public function deleteCartItemByProductId($cartId,$product_id)
    {
        return CartItem::with(['product'])->where('cart_id', $cartId)->where('product_id',$product_id)->delete();
    }

    public function cartDataUpdate($cartId)
    {
        // Retrieve the cart based on the given conditions
        $cart = Cart::where('status', 'active')->where('cart_status', 'in_cart')->where('id', $cartId)->first();

        if ($cart) {
            // Calculate the sum of quantities and count of items
            $sumQuantity = CartItem::where('cart_id', $cartId)->sum('total_price');
            $count = CartItem::where('cart_id', $cartId)->count();

            // Update the cart fields
            $cart->update([
                'total_items' => $count,
                'grand_total' => $sumQuantity,
            ]);
        }
    }

    public function getCartCount($customerId)
    {
        $cart = Cart::where('customer_id', $customerId)->where('status', 'active')->first();
        $cartItemCount = CartItem::where('cart_id', $cart->id)->count();
        return $cartItemCount;
    }

    public function getActiveCartCount($customerId, $productId)
    {
        $cart = Cart::with('cartItems')->where('customer_id', $customerId)
            ->where('status', 'active')->where('cart_status','in_cart');
        $cart = $cart->whereHas('cartItems', function ($query) use ($productId) {
            return $query->where('product_id', $productId);
        });

        return $cart->count();
    }

}
