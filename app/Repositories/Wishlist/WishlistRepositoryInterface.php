<?php

namespace App\Repositories\Wishlist;

interface WishlistRepositoryInterface
{
    public function updateWhishlist($input);

    public function delete($input);

    public function productAlreadyExistInWishList($data);

    public function wishList($filterData);

    public function getWishlist($data);

    public function get($id);

    public function whishlistCount($data);

    public function productInWishlistCount($customerId, $productId);
}
