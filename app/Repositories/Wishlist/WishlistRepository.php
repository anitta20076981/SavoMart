<?php

namespace App\Repositories\Wishlist;

use App\Models\Wishlist;

class WishlistRepository implements WishlistRepositoryInterface
{
    public function updateWhishlist($input)
    {
        if ($input['action'] == 'add') {
            if ($wishList = Wishlist::create($input)) {
                return $wishList;
            }

            return false;
        } else {
            $wishList = Wishlist::where('product_id', $input['product_id'])
                ->where('customer_id', $input['customer_id'])->delete();
        }
    }

    public function delete($input)
    {
        return Wishlist::where('product_id', $input['product_id'])
            ->where('customer_id', $input['customer_id'])->delete();
    }

    public function productAlreadyExistInWishList($data)
    {

        return Wishlist::where('product_id', $data['product_id'])
            ->where('customer_id', $data['customer_id'])->count();
    }

    public function wishList($filterData)
    {
        $wishList = Wishlist::with('product')->where('customer_id', $filterData['customer_id']);
        if (isset($filterData['offset']) && isset($filterData['limit'])) {
            $wishList = $wishList->offset($filterData['offset'])->limit($filterData['limit']);
        }
        $wishList = $wishList->whereHas('product', function ($query) use ($filterData) {
            if (isset($filterData['search_text']) && isset($filterData['search_text'])) {
                $wishList = $query->where('name', 'like', "%{$filterData['search_text']}%");
            }
        });
        return $wishList->get();
    }

    public function getWishlist($data)
    {
        return Wishlist::where('customer_id', $data['customer_id'])->where('product_id', $data['product_id'])->first();
    }

    public function get($id)
    {
        return Wishlist::where('id', $id)->with('product')->get();
    }

    public function whishlistCount($data)
    {
        return Wishlist::where('customer_id', $data['customer_id'])->count();
    }

    public function productInWishlistCount($customerId, $productId)
    {
        return Wishlist::where('customer_id', $customerId)->where('product_id', $productId)->count();
    }
}
