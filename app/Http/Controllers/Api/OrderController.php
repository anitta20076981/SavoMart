<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Order\CancelOrderItemRequest;
use App\Http\Requests\Api\Order\AddOrderRequest;
use App\Http\Requests\Api\Order\OrderDetailsRequest;
use App\Repositories\Cart\CartRepositoryInterface;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Customer\CustomerRepositoryInterface;
use App\Repositories\Customer\CustomerRepositoryInterface as CustomerRepository;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\Products\ProductsRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{

    private CategoryRepositoryInterface $categoryRepo;
    private ProductsRepositoryInterface $productsRepo;
    private CustomerRepositoryInterface $customerRepo;
    private OrderRepositoryInterface $orderRepo;
    private CartRepositoryInterface $cartRepo;

    public function __construct(CategoryRepositoryInterface $categoryRepo, ProductsRepositoryInterface $productsRepo, CustomerRepositoryInterface $customerRepo, OrderRepositoryInterface $orderRepo, CartRepositoryInterface $cartRepo)
    {
        $this->categoryRepo = $categoryRepo;
        $this->productsRepo = $productsRepo;
        $this->customerRepo = $customerRepo;
        $this->orderRepo = $orderRepo;
        $this->cartRepo = $cartRepo;
    }

    public function addToOrder(AddOrderRequest $request, CustomerRepository $customerRepo)
    {

        $customer = auth('sanctum')->user();
        if (isset($customer->id)) {
            $customerId = $customer->id;
        } else {
            return ['status' => true, 'message' => 'Customer Not Existing'];
        }

        $cart = $this->cartRepo->get($request->cart_id);
        if (!$cart) {
            return \response()->json(['status' => true, 'message' => 'cart not exist'], 200);
        }
        $address = $customerRepo->getaddressById($request->address_id);
        if (!$address) {
            return \response()->json(['status' => true, 'message' => 'address not exist'], 200);
        }

        $order = $this->orderRepo->getByCartId($cart->id);

        if (!$order) {
            if ($cart->total_items > 0) {

                $orderData = [
                    'customer_id' => $cart->customer_id,
                    'order_no' => 'GRY-' . rand(100000, 999999),
                    'cart_id' => $cart->id,
                    'address_id' => $request->address_id,
                    'payment_type' => $request->payment_type,
                    'date' => now(),
                    'total_items' => $cart->total_items,
                    'grand_total' => $cart->grand_total,
                    'status' => 'pending',
                ];
                $order = $this->orderRepo->saveOrder($orderData);
                if($order){
                    $cartData = [
                        'id' => $cart->id,
                        'cart_status' => 'in_order',
                    ];
                    $cart = $this->cartRepo->updateCart($cartData);
                }
                foreach ($cart->cartItems as $items) {
                    if($items->quantity > $items->product->quantity)
                    {
                        return \response()->json(['status' => false, 'message' => 'Quantity does not available'], 200);
                    }

                    $orderItemData = [
                        'cart_item_id' => $items->id,
                        'order_id' => $order->id,
                        'product_id' => $items->product_id,
                        'quantity' => $items->quantity,
                        'unit_price' => $items->unit_price,
                        'total_price' => $items->total_price,
                    ];

                    $orderItem = $this->orderRepo->saveOrderItem($orderItemData);

                }

                $orderAddressData = [
                    'order_id' => $order->id,
                    'customer_id' => $request->customer_id,
                    'details' => $address->street,
                    'street_address' => $address->address_line1,
                    'contact' => $address->number,
                ];

                $orderAddress = $this->orderRepo->orderAddressCreate($orderAddressData);
                $orderUpdate = [
                    'id' => $order->id,
                    'address_id' => $orderAddress->id,
                ];
                $orderUpdate = $this->orderRepo->update($orderUpdate);

                $cartUpdate = [
                    'id' => $cart->id,
                    'status' => 'inactive',
                ];
                $cartUpdate = $this->cartRepo->updateCart($cartUpdate);

                return \response()->json(['status' => true, 'message' => 'order added'], 200);
            } else {

                return \response()->json(['status' => true, 'message' => 'cart is empty'], 200);
            }
        } else {
            return \response()->json(['status' => true, 'message' => 'order exists'], 200);

        }

        return \response()->json(['status' => false, 'message' => 'product feiled add to order'], 200);
    }

    public function getOrderItems($order_id, $language_type)
    {
        $orderItem = $this->orderRepo->getAllOrderItems($order_id);

        $orderItem = $orderItem->map(function ($items, $key) use ($language_type) {
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
                'return_status' => $items->return_status,
                'image' => $this->getProductImage($items->product->id),
            ];
        });

        return $orderItem;
    }

    public function getProductImage($id)
    {
        $productImage = $this->productsRepo->getAllImage($id);
        $productImage = $productImage->map(function ($items, $key) {
            return [
                'image_role' => $items->image_role,
                'image_path' => $items->image_path != '' ? Storage::disk('grocery')->url($items->image_path) : '',
                'alt_text' => $items->alt_text,
            ];
        });
        return $productImage;
    }

    public function listOrder(Request $request)
    {
        $customer = auth('sanctum')->user();

        if (isset($customer->id)) {
            $customerId = $customer->id;
        } else {
            return ['status' => true, 'message' => 'Customer Not Existing'];
        }

        $requestData = $request->all();
        $requestData['product_id'] = $request->product_id;
        $requestData['sort_by'] = $request->sort_by;
        $requestData['limit'] = $request->has('limit') && $request->limit ? $request->limit : 50;
        $requestData['page'] = $request->has('page') && $request->page ? $request->page : 1;
        $requestData['offset'] = ($requestData['page'] - 1) * $requestData['limit'];
        $requestData['search_text'] = $request->has('search_text') && $request->search_text ? $request->search_text : '';
        $requestData['customer_id'] = $customerId;

        $order = $this->orderRepo->getCustomerActiveOrder($requestData);

        if ($order) {

            $order = $order->map(function ($items, $key) {
                return [
                    'id' => $items->id,
                    'order_no' => $items->order_no,
                    'location' =>  $items->orderAddress->street_address,
                    'status' => $items->status,
                    'date' => $items->date,
                    'payment_type' => $items->payment_type,
                    'total_items' => $items->total_items,
                    'grand_total' => $items->grand_total,
                    'delivery_date' => $items->delivery_date,
                ];
            });
        } else {
            $order = [
                'order_id' => '',
                'date' => '',
                'total_items' => '',
                'grand_total' => '',
                'order_items' => [],
            ];
            return response()->json(['status' => true, 'order' => $order], 200);
        }

        return response()->json(['status' => true, 'order' => $order], 200);

    }


    public function orderDetails(OrderDetailsRequest $request)
    {
        $customer = auth('sanctum')->user();

        if (isset($customer->id)) {
            $customerId = $customer->id;
        } else {
            return ['status' => true, 'message' => 'Customer Not Existing'];
        }

        $order = $this->orderRepo->get($request->order_id);

        if ($order) {
            $order = [
                'id' => $order->id,
                'order_no' => $order->order_no,
                'status' => $order->status,
                'date' => $order->date,
                'location' => $order->orderAddress->street_address,
                'payment_type' => $order->payment_type,
                'total_items' => $order->total_items,
                'grand_total' => $order->grand_total,
                'delivery_date' => $order->delivery_date,
                'order_items' => $this->getOrderItems($order->id, $request->language_type),
            ];
        } else {
            $order = [
                'order_id' => '',
                'date' => '',
                'total_items' => '',
                'grand_total' => '',
                'order_items' => [],
            ];
            return response()->json(['status' => true, 'order' => $order], 200);
        }

        return response()->json(['status' => true, 'order' => $order], 200);

    }


    public function cancelOrderItem(CancelOrderItemRequest $request)
    {

        $customer = auth('sanctum')->user();

        if (isset($customer->id)) {
            $customerId = $customer->id;
        } else {
            return ['status' => true, 'message' => 'Customer Not Existing'];
        }

        $order = $this->orderRepo->getActiveOrder($request->order_id);
        if ($order) {

            $orderData = [
                'id' => $order->id,
                'status' => 'rejected',
            ];
            $order = $this->orderRepo->updateOrder($orderData);

        } else {
            return response()->json(['status' => true, 'message' => "order not found"], 200);
        }

        return response()->json(['status' => true, 'message' => "order deleted "], 200);

    }

}
