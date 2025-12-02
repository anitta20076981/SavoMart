<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Products\ProductsRepositoryInterface;
use App\Repositories\Cart\CartRepositoryInterface;
use App\Repositories\Wishlist\WishlistRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class ProductsController extends Controller
{

    private CategoryRepositoryInterface $categoryRepo;
    private ProductsRepositoryInterface $productsRepo;
    private CartRepositoryInterface $cartRepo;
    private WishlistRepositoryInterface $wishlistRepo;


    public function __construct(CategoryRepositoryInterface $categoryRepo,  ProductsRepositoryInterface $productsRepo, CartRepositoryInterface $cartRepo,
    WishlistRepositoryInterface $wishlistRepo)
    {
        $this->categoryRepo = $categoryRepo;
        $this->productsRepo = $productsRepo;
        $this->cartRepo = $cartRepo;
        $this->wishlistRepo = $wishlistRepo;
    }

    public function getProductImage($id)
    {
        $productImage =  $this->productsRepo->getAllImage($id);
        $productImage = $productImage->map(function($items, $key){
            return [
             'image_role' => $items->image_role,
             'image_path' => $items->image_path != '' ? Storage::disk('grocery')->url($items->image_path) : '',
             'alt_text' => $items->alt_text,
            ];
        });
        return $productImage;
    }

    public function getproductById(Request $request)
    {
        $language_type = $request->language_type;
        $product =   $this->productsRepo->get($request->id);

            // $product =  [
            //     'id' => $product->id,
            //     'sku' => $product->sku,
            //     'name' => ($language_type == 'en') ? $product->name : $product->name_ar,
            //     'quantity' => $product->quantity,
            //     'status' => $product->status,
            //     'type' => $product->type,
            //     'description' => ($language_type == 'en') ? $product->description : $product->description_ar,
            //     'price' => $product->price,
            //     'special_price' => $product->special_price,
            //     'special_price_to' => $product->special_price_to,
            //     'special_price_from' => $product->special_price_from,
            //     'discount_id' => $product->discount_id,
            //     'discount_percentage' => $product->discount_percentage,
            //     'discount_amount' => $product->discount_amount,
            //     'image' => $this->getProductImage($product->id),
            // ];

            if ($product['type'] == 'configurable_product') {

                $product->variations = $product->variations->map(function ($variation, $key) {
                    $variation->virtualAttributes;

                    return $variation;
                });
            }elseif ($product->type == 'virtual_product') {
               $product = $this->productsRepo->getProductWithId($request->id);
               $variation = $this->productsRepo->getVariations($product->parent_id);
               $product->variations = $variation;
        }

        $product['is_in_cart'] = (auth('sanctum')->user()) ? ($this->cartRepo->getActiveCartCount(auth('sanctum')->user()->id, $product->id) == 0 ? false : true) : false;


        $product['cart_quantity'] = (auth('sanctum')->user()) ? ($this->cartRepo->getCartWithCustomerAndProduct(auth('sanctum')->user()->id, $product->id)  == null ? 0 : $this->cartRepo->getCartWithCustomerAndProduct(auth('sanctum')->user()->id, $product->id)->quantity) : 0;

        $product['is_in_wishlist'] = (auth('sanctum')->user()) ? ($this->wishlistRepo->productInWishlistCount(auth('sanctum')->user()->id, $product->id) == 0 ? false : true) : false;

        return \response()->json(['status' => true, 'product' => $product],200);
    }

    public function getAllFeatured(Request $request)
    {
        $language_type = $request->language_type;
        $product =   $this->productsRepo->getFeaturedProducts();

            $productImage = $product->map(function($product, $key){
                return [
                    'id' => $product->products->id,
                    'sku' => $product->products->sku,
                    'name' => ($language_type == 'en') ? $product->name : $product->name_ar,
                    'quantity' => $product->quantity,
                    'status' => $product->status,
                    'type' => $product->type,
                    'description' => ($language_type == 'en') ? $product->description : $product->description_ar,
                    'price' => $product->products->price,
                    'special_price' => $product->products->special_price,
                    'special_price_to' => $product->products->special_price_to,
                    'special_price_from' => $product->products->special_price_from,
                    'discount_id' => $product->products->discount_id,
                    'discount_percentage' => $product->products->discount_percentage,
                    'discount_amount' => $product->products->discount_amount,
                    'image' => $this->getProductImage($product->products->id),
                ];
            });

        return \response()->json(['status' => true, 'product' => $productImage],200);
    }

    public function listAllProducts(Request $request)
    {
        try {
            $requestData = $request->all();
            $requestData['sort_by'] = $request->sort_by;
            $requestData['limit'] = $request->has('limit') && $request->limit ? $request->limit : 50;
            $requestData['page'] = $request->has('page') && $request->page ? $request->page : 1;
            $requestData['offset'] = ($requestData['page'] - 1) * $requestData['limit'];
            $requestData['search_text'] = $request->has('search_text') && $request->search_text ? $request->search_text : '';
            $products = $this->productsRepo->getAllProducts($requestData);
            $data = compact('products');
            return $response = ['status' => true, 'data' => $data, 'message' => 'Success'];
        } catch (Exception $e) {
            $response = ['status' => false, 'message' => $e->getMessage()];

            return response()->json($response, 200);
        }
    }

    public function listRelatedProducts(Request $request)
    {
        try {
            $requestData = $request->all();
            $requestData['product_id'] = $request->product_id;
            $requestData['sort_by'] = $request->sort_by;
            $requestData['limit'] = $request->has('limit') && $request->limit ? $request->limit : 50;
            $requestData['page'] = $request->has('page') && $request->page ? $request->page : 1;
            $requestData['offset'] = ($requestData['page'] - 1) * $requestData['limit'];
            $requestData['search_text'] = $request->has('search_text') && $request->search_text ? $request->search_text : '';
            $products = $this->productsRepo->getRealatedProducts($requestData);
            $data = compact('products');
            return $response = ['status' => true, 'data' => $data, 'message' => 'Success'];
        } catch (Exception $e) {
            $response = ['status' => false, 'message' => $e->getMessage()];

            return response()->json($response, 200);
        }
    }

    public function productSearch(Request $request)
    {
        try {
            $term = preg_replace('/\s+/', ' ', $request->term);
            $requestData['term'] = $request->has('term') && $request->term ? preg_replace('/\s+/', ' ', $request->term) : '';
            $requestData['limit'] = $request->has('limit') && $request->limit ? $request->limit : 50;
            $requestData['page'] = $request->has('page') && $request->page ? $request->page : 1;
            $requestData['offset'] = ($requestData['page'] - 1) * $requestData['limit'];
            $products = ($requestData['term']) ? $this->productsRepo->productSearch($requestData) : [];

            $data = compact('products');
            return $response = ['status' => true, 'data' => $data, 'message' => 'Success'];
        } catch (Exception $e) {
            return $response = ['status' => false, 'message' => $e->getMessage()];
        }
    }
}
