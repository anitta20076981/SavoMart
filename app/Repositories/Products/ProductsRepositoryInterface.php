<?php

namespace App\Repositories\Products;

interface ProductsRepositoryInterface
{
    public function getProducts($data);

    public function getAvailableProducts($data);

    public function get($id);

    public function getProductWithId($id);


    public function getFeaturedProducts();

    public function save($input);

    public function saveImages($input);

    public function getthumbnail($productId);

    public function deleteImage($id, $fileName);

    public function deleteImages($productId, $notInIds);

    public function update($data);

    public function delete($id);

    public function updateThumbnail($data);

    public function getImage($id);

    public function getAllImage($product_id);

    public function searchProducts($requestData);

    public function saveProductCategory($data);

    public function getProductCategories($productId);

    public function getPorductsByCategory($category_ig);

    public function deleteCategories($productId);

    public function getSelectedProducts($id, $data);

    public function searchCategoryProducts($term, $categoryId);

    public function getProductByCatatagories($catagoryIds, $operator);

    public function getProductByAttributes($attributeIds, $condition);

    public function saveProductIndices($data);

    public function deleteVariationsUsingParentId($id, $availableIds);

    public function getVariationsUsingParentId($id);

    public function deleteVariantsUsingParentId($id);

    public function getProduct($productId);

    public function saveRelatedProducts($data);

    public function deleteRelatedProducts($productId);

    public function getAllProducts($requestData);

    public function getProductById($productId);

    public function isSkuExist($sku);

    public function getPendingProducts($data);

    public function getProductsForQuote($data);

    public function searchVendorProducts($data);

    public function featuredProductSave($input);

    public function deleteFeaturedProduct($id);

    public function incrementProductQty($productId, $qty);

    public function getRealatedProducts($requestData);

    public function productSearch($requestData);

    public function getVariantProduct($productId, $parentId);

    public function getVariations($id)
;

}
