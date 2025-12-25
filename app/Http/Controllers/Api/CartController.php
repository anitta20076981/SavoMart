<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Cart\CancelCartItemRequest;
use App\Repositories\Cart\CartRepositoryInterface;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Customer\CustomerRepositoryInterface;
use App\Repositories\Products\ProductsRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CartController extends Controller
{

    private CategoryRepositoryInterface $categoryRepo;
    private ProductsRepositoryInterface $productsRepo;
    private CustomerRepositoryInterface $customerRepo;
    private CartRepositoryInterface $cartRepo;

    public function __construct(CategoryRepositoryInterface $categoryRepo, ProductsRepositoryInterface $productsRepo, CustomerRepositoryInterface $customerRepo, CartRepositoryInterface $cartRepo)
    {
        $this->categoryRepo = $categoryRepo;
        $this->productsRepo = $productsRepo;
        $this->customerRepo = $customerRepo;
        $this->cartRepo = $cartRepo;
    }

    public function addToCart(Request $request)
    {

        $customer = auth('sanctum')->user();
        if (isset($customer->id)) {
            $customerId = $customer->id;
        } else {
            return ['status' => true, 'message' => 'Customer Not Existing'];
        }

        $productId = $request->product_id;
        $quantity = $request->has('quantity') ? $request->quantity : 1;

        // Retrieve customer's active cart
        $cart = $this->cartRepo->getCustomerActiveCart($customerId);

        // Retrieve product and calculate amount
        $product = $this->productsRepo->get($productId);
        if (!$product) {
            return ['status' => false, 'message' => 'Product Not Existing'];
        }
        if($quantity > $product->quantity)
        {
            return ['status' => false, 'message' => 'This much of quantity not available'];
        }

        $price = $product->price;
        $amount = $quantity * $price;

        if ($cart) {

            // Check if the product is already in the cart
            $cartItem = $this->cartRepo->getCartItemByProduct($productId, $cart->id);

            if ($cartItem) {
                // Update the existing cart item
                $cartItemData = [
                    'id' => $cartItem->id,
                    'quantity' => $quantity,
                    'total_price' => $amount,
                ];
                $cartItem = $this->cartRepo->updateCartItem($cartItemData);

                $cart = $this->cartRepo->cartDataUpdate($cart->id);

            } else {
                // Add a new cart item
                $cartItemData = [
                    'cart_id' => $cart->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'unit_price' => $price,
                    'total_price' => $amount,
                ];
                $cartItem = $this->cartRepo->saveCartItem($cartItemData);

                $cart = $this->cartRepo->cartDataUpdate($cart->id);

            }

            return \response()->json(['status' => true, 'message' => 'product added to cart'],200);
        } else {
            // Create a new cart
            $cartData = [
                'customer_id' => $customerId,
                'date' => now(), // Use Laravel's helper function to get the current date and time
                'total_items' => 1,
                'grand_total' => $amount,
                'status' => 'active',
            ];
            $cart = $this->cartRepo->saveCart($cartData);

            // Add a new cart item
            $cartItemData = [
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'quantity' => $quantity,
                'unit_price' => $price,
                'total_price' => $amount,
            ];
            $cartItem = $this->cartRepo->saveCartItem($cartItemData);

            return \response()->json(['status' => true, 'message' => 'product added to cart'],200);
        }

        return \response()->json(['status' => false, 'message' => 'product feiled add to cart'],200);
    }

    public function getCartItems($cart_id,$language_type){

        $cartItem = $this->cartRepo->getAllCartItems($cart_id);

        $cartItem = $cartItem->map(function ($items, $key)use($language_type) {
            return [
                'id' => $items->id,
                'product_id' => $items->product_id,
                'quantity' => $items->quantity,
                'unit_price' => $items->unit_price,
                'total_price' => $items->total_price,
                'name' => ($language_type == 'en') ? $items->product->name : $items->product->name_ar,
                'type' => $items->product->type,
                'description' => ($language_type == 'en') ? $items->product->description : $items->product->description_ar,
                'price' => $items->product->price,
                'special_price' => $items->product->special_price,
                'special_price_to' => $items->product->special_price_to,
                'special_price_from' => $items->product->special_price_from,
                'discount_id' => $items->product->discount_id,
                'discount_percentage' => $items->product->discount_percentage,
                'discount_amount' => $items->product->discount_amount,
                'product_quantity' => $items->product->quantity,
                'stock_status' => $items->product->stock_status,
                'final_price' => $items->product->final_price,
                'image' => $this->getProductImage($items->product->id),
            ];
        });

        return $cartItem;
    }

    public function getProductImage($id)
    {
        $productImage = $this->productsRepo->getAllImage($id);
        $productImage = $productImage->map(function ($items, $key) {
            return [
                'image_role' => $items->image_role,
                'image_path' => $items->image_path != '' ? Storage::disk('savomart')->url($items->image_path) : '',
                'alt_text' => $items->alt_text,
            ];
        });
        return $productImage;
    }

    public function listCart(Request $request)
    {
        $customer = auth('sanctum')->user();

        if (isset($customer->id)) {
            $customerId = $customer->id;
        } else {
            return ['status' => true, 'message' => 'Customer Not Existing'];
        }

        $cart = $this->cartRepo->getCustomerActiveCart($customerId);
        if ($cart) {
            $cart = [
                'cart_id' => $cart->id,
                'date' => $cart->date,
                'total_items' => $cart->total_items,
                'grand_total' => $cart->grand_total,
                'cart_items' => $this->getCartItems($cart->id,$request->language_type),
            ];
        } else {
            $cart = [
                'cart_id' => '',
                'date' => '',
                'total_items' => '',
                'grand_total' => '',
                'cart_items' => [],
            ];
            return response()->json(['status' => true, 'cart' => $cart], 200);
        }

        return response()->json(['status' => true, 'cart' => $cart], 200);

    }

    public function cancelCartItem(CancelCartItemRequest $request)
    {

        $customer = auth('sanctum')->user();

        if (isset($customer->id)) {
            $customerId = $customer->id;
        } else {
            return ['status' => true, 'message' => 'Customer Not Existing'];
        }

        $cart = $this->cartRepo->getCustomerActiveCart($customerId);
        if ($cart) {

            $cartItem = $this->cartRepo->getCartItemByProductId($cart->id, $request->product_id);
            if ($cartItem) {

                $cartItem = $this->cartRepo->deleteCartItemByProductId($cart->id, $request->product_id);

                $cart = $this->cartRepo->cartDataUpdate($cart->id);
            } else {
                return response()->json(['status' => true, 'message' => "product not exist "], 200);
            }

        } else {
            $cart = [
                'cart_id' => '',
                'date' => '',
                'total_items' => '',
                'grand_total' => '',
                'cart_items' => [],
            ];
            return response()->json(['status' => true, 'cart' => $cart], 200);
        }

        return response()->json(['status' => true, 'message' => "product deleted "], 200);

    }

    public function cartCount(Request $request)
    {
        try {
            $customer = auth('sanctum')->user();
            $cartCount = $this->cartRepo->getCartCount($customer->id);
            $data = compact('cartCount');
            return $response = ['status' => true, 'data' => $data, 'message' => 'Success'];
        } catch (Exception $e) {
            $response = ['status' => false, 'message' => $e->getMessage()];

            return response()->json($response, 200);
        }
    }
}