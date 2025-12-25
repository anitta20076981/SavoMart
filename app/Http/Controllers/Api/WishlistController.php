<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Cart\CartRepositoryInterface as CartRepository;
use App\Repositories\Products\ProductsRepositoryInterface as ProductsRepository;
use App\Repositories\Wishlist\WishlistRepositoryInterface as WishlistRepository;
use Exception;
use Illuminate\Support\Facades\Storage;

class WishlistController extends Controller
{
    protected $wishlistRepo;

    protected $cartRepo;

    protected $productsRepo;

    public function __construct(
        WishlistRepository $wishlistRepo,
        CartRepository $cartRepo,
        ProductsRepository $productsRepo,


    ) {
        $this->wishlistRepo = $wishlistRepo;
        $this->cartRepo = $cartRepo;
        $this->productsRepo = $productsRepo;
    }

    public function getProductImage($id)
    {
        $productImage =  $this->productsRepo->getAllImage($id);
        $productImage = $productImage->map(function($items, $key){
            return [
             'image_role' => $items->image_role,
             'image_path' => $items->image_path != '' ? Storage::disk('savomart')->url($items->image_path) : '',
             'alt_text' => $items->alt_text,
            ];
        });
        return $productImage;
    }

    public function wishList(Request $request)
    {
        try {
            $wishlist = [];
            $filterData['customer_id'] = auth('sanctum')->user()->id;
            $filterData['product_id'] = $request->product_id;

            $wishlist = $this->wishlistRepo->getWishlist($filterData);
            if(isset($wishlist)){
                return $response = ['status' => true, 'data' => $wishlist, 'message' => 'Success'];
            }else{
            return $response = ['status' => false, 'data' => null, 'message' => 'not added'];
            }

        } catch (Exception $e) {
            $response = ['status' => false, 'message' => $e->getMessage()];

            return response()->json($response, 200);
        }
    }

    public function allWishList(Request $request)
    {
        try {
            $wishlist = [];
            $filterData['customer_id'] = auth('sanctum')->user()->id;
            $filterData['sort_by'] = $request->sort_by;
            $filterData['limit'] = $request->has('limit') && $request->limit ? $request->limit : 50;
            $filterData['page'] = $request->has('page') && $request->page ? $request->page : 1;
            $filterData['offset'] = ($filterData['page'] - 1) * $filterData['limit'];
            $filterData['search_text'] = $request->has('search_text') && $request->search_text ? $request->search_text : '';

            $wishlist = $this->wishlistRepo->wishList($filterData);

            $product = $wishlist->map(function($wishlist, $key){
                return [
                    'id' => $wishlist->product->id,
                    'sku' => $wishlist->product->sku,
                    'name' => $wishlist->product->name,
                    'quantity' => $wishlist->product->quantity,
                    'status' => $wishlist->product->status,
                    'type' => $wishlist->product->type,
                    'description' => $wishlist->product->description,
                    'price' => $wishlist->product->price,
                    'special_price' => $wishlist->product->special_price,
                    'special_price_to' => $wishlist->product->special_price_to,
                    'special_price_from' => $wishlist->product->special_price_from,
                    'discount_id' => $wishlist->product->discount_id,
                    'discount_percentage' => $wishlist->product->discount_percentage,
                    'discount_amount' => $wishlist->product->discount_amount,
                    'final_price' => $wishlist->product->final_price,
                    'image' => $this->getProductImage($wishlist->product->id),
                ];
            });

            if(isset($product)){
                return $response = ['status' => true, 'data' => $product, 'message' => 'Success'];
            }else{
            return $response = ['status' => false, 'data' => null, 'message' => 'there is no product in wishlist'];
            }

        } catch (Exception $e) {
            $response = ['status' => false, 'message' => $e->getMessage()];

            return response()->json($response, 200);
        }
    }

    public function addWhishlist(Request $request)
    {
        try {
            $wishlist = [];
            $request->merge(['customer_id' => auth('sanctum')->user()->id]);

            if ($request->action == 'add') {
                $productExistCount = $this->wishlistRepo->productAlreadyExistInWishList($request->all());

                if ($productExistCount == 0) {
                    $updateWishlist = $this->wishlistRepo->updateWhishlist($request->all());

                    $wishlist = $this->wishlistRepo->get($updateWishlist->id);

                    return $response = ['status' => true, 'data' => $wishlist, 'message' => 'Product added to wishlist'];
                } else {
                    return $response = ['status' => false, 'message' => 'Product already added in wishlist'];
                }
            } else {
                $wishlist = $this->wishlistRepo->getWishlist($request->all());

                if ($wishlist) {
                    $this->wishlistRepo->delete($request->all());
                }

                return $response = ['status' => true, 'message' => 'Product removed from wishlist'];
            }
        } catch (Exception $e) {
            $response = ['status' => false, 'message' => $e->getMessage()];

            return response()->json($response, 200);
        }
    }
}
