<?php

namespace App\Repositories\Products;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductCategories;
use App\Models\ProductFeatured;
use App\Models\ProductImage;
use App\Models\ProductInventory;
use App\Models\ProductPriceIndices;
use App\Models\ProductRelation;
use App\Models\ProductReview;
use App\Models\ProductStockNotifications;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class ProductsRepository implements ProductsRepositoryInterface
{

    public function getProducts($data)
    {
        $products = Product::with('categories')
            ->where('status', '<>', 'pending')
            ->select('id', 'status', 'name', 'name_ar', 'sku', 'type',)
            ->where(function (Builder $query) use ($data) {
                if (isset($data['status']) && $data['status']) {
                    $query->where('status', '=', $data['status']);
                }
            })->orderby('created_at', 'desc');

        if (isset($data['category_id']) && $data['category_id'] != '') {
            $products = $products->whereHas('categories', function ($query) use ($data) {
                return $query->where('category_id', $data['category_id']);
            });
        }

        return $products;
    }

    public function getAvailableProducts($data)
    {
        $products = Product::with('categories')
            ->select('id', 'status', 'type', 'name', 'sku', 'price',)
            // ->where('status', 'publish')
            ->where(function (Builder $query) use ($data) {
                if (isset($data['status']) && $data['status']) {
                    $query->where('status', '=', $data['status']);
                }
            });

        if (isset($data['category_id']) && $data['category_id'] != '') {
            $products = $products->whereHas('categories', function ($query) use ($data) {
                $query->where('category_id', $data['category_id']);
            });
        }

        // if (isset($data['customer_group_id']) && $data['customer_group_id'] != '') {
        //     $products = $products->whereHas('productPriceIndices', function ($query) use ($data) {
        //         $query->where('customer_group_id', $data['customer_group_id']);
        //     });
        // }

        // if (config('settings.inventory.cataloginventory_options_show_out_of_stock') == 0) {
        //     $products->where('stock_status', '!=', 'outofstock');
        // }

        if (isset($data['search']['value'])) {
            $products = $products->where('name', 'like', "%{$data['search']['value']}%");
        }

        // $products = $products->whereIn('type', ['virtual_product', 'simple_product']);

        return $products;
    }

    public function delete($id)
    {
        $products = Product::find($id);

        return $products->delete();
    }

    public function get($id)
    {
        $products = Product::with(
            'variations.productAttributes','variations.productImages','productImages'
        )->find($id);

        return $products;
    }

    public function getProductWithId($id)
    {
        $products = Product::with('productImages')->find($id);

        return $products;
    }

    public function getFeaturedProducts()
    {
        $products = ProductFeatured::with('products')->get();

        return $products;
    }

    public function deleteVariationsUsingParentId($id, $availableIds)
    {
        return Product::where('parent_id', $id)->whereNotIn('id', $availableIds)->delete();
    }

    public function deleteVariantsUsingParentId($id)
    {
        if (Product::where('parent_id', $id)->delete()) {
            return true;
        }

        return false;
    }

    public function getVariationsUsingParentId($id)
    {
        return Product::where('parent_id', $id)->pluck('id');
    }

    public function save($input)
    {
        $input['status']='active';

        if ($products = Product::create($input)) {

            return $products;
        }

        return false;
    }

    public function saveImages($input)
    {
        if (isset($input['id']) && $input['id']) {
            $productImage = ProductImage::find($input['id']);
            $productImage->update($input);

            return $productImage;
        } elseif ($productImage = ProductImage::create($input)) {
            return $productImage;
        }

        return false;
    }

    public function getthumbnail($productId)
    {
        return $products = ProductImage::where('product_id', $productId)->where('image_role', 'THUMBNAIL')->value('image_path');
    }

    public function deleteImage($id, $fileName)
    {
        return ProductImage::where('id', $id)->where('image_path', $fileName)->delete();
    }

    public function deleteImages($productId, $notInIds)
    {
        $images = ProductImage::whereNotIn('id', $notInIds)->where('product_id', $productId)->where('image_role', 'BASE')->get();

        foreach ($images as $image) {
            if (Storage::disk('savomart')->delete($image->image_path)) {
                $image->delete();
            }
        }
    }

    public function update($data)
    {
        $products = Product::find($data['id']);

        if ($products->update($data)) {
            return $products;
        }

        return false;
    }

    public function updateThumbnail($data)
    {
        $productsImage = ProductImage::where('image_role', 'THUMBNAIL')->where('product_id', $data['product_id'])->first();

        if ($productsImage->update($data)) {
            return $productsImage;
        }

        return false;
    }

    public function getImage($id)
    {
        return $productsImage = ProductImage::find($id);
    }

    public function getAllImage($product_id)
    {
        return $productsImages = ProductImage::where('product_id', $product_id)->get();
    }

    public function searchProducts($requestData)
    {
        $products = Product::where('status', 'active')
            ->with(['categories']);

        if (isset($requestData['category_id']) && $requestData['category_id'] != '') {
            $products = $products->whereHas('categories', function ($query) use ($requestData) {
                return $query->where('category_id', $requestData['category_id']);
            });
        }

        if (isset($requestData['search']) && $requestData['search']) {
            $products = $products->where('name', 'like', "%{$requestData['search']}%");
        }

        return $products->paginate(config('app.select_options_count'), ['*'], 'page', request()->get('page'));

    }

    public function searchCategoryProducts($term, $categoryId)
    {
        return  $products = Product::with('categories')->where('name', 'like', "%{$term}%")
            ->whereHas('categories', function ($query) use ($categoryId) {
                if (isset($categoryId)) {
                    $query->where('category_id', $categoryId);
                }
            })
            ->paginate(config('app.select_options_count'), ['*'], 'page', request()->get('page'));

    }

    public function saveProductCategory($data)
    {
        if ($productCategories = ProductCategories::create($data)) {
            return $productCategories;
        }

        return false;
    }

    public function getProductCategories($productId)
    {
        return $produtCategories = ProductCategories::where('product_id', $productId)->pluck('category_id')->toArray();
    }

    public function deleteCategories($productId)
    {
        return ProductCategories::where('product_id', $productId)->delete();
    }

    public function getSelectedProducts($id, $data)
    {
        $products = Product::whereIn('id', $id)->with(['categories', 'productPriceIndices'])
            ->select('id', 'status', 'name', 'sku', 'price', 'stock_status', 'customer_id', 'tax_category_id')
            ->where(function (Builder $query) use ($data) {
                if (isset($data['status']) && $data['status']) {
                    $query->where('status', '=', $data['status']);
                }
            });

        if (isset($data['category_id']) && $data['category_id'] != '') {
            $products = $products->whereHas('categories', function ($query) use ($data) {
                return $query->where('category_id', $data['category_id']);
            });
        }

        if (isset($data['customer_group_id']) && $data['customer_group_id'] != '') {
            $products = $products->whereHas('productPriceIndices', function ($query) use ($data) {
                $query->where('customer_group_id', $data['customer_group_id']);
            });
        }

        if (config('settings.inventory.cataloginventory_options_show_out_of_stock') == 0) {
            $products->where('stock_status', '!=', 'outofstock');
        }

        if (isset($data['search']['value'])) {
            $products = $products->where('name', 'like', "%{$data['search']['value']}%");
        }

        return $products;
    }

    public function getProductByCatatagories($catagoryIds, $operator)
    {
        if (gettype($catagoryIds) == 'string') {
            $catagoryIds = json_decode($catagoryIds, true);
        }

        if ($operator == '{}') {
            return ProductCategories::whereIn('category_id', $catagoryIds)
                ->pluck('product_id')
                ->toArray();
        } else {
            return ProductCategories::whereNotIn('category_id', $catagoryIds)
                ->pluck('product_id')
                ->toArray();
        }
    }

    public function getProductByAttributes($attributeIds, $condition)
    {
        if ($condition['attribute'] == 'attribute_set') {
            $productAttributes = Product::whereIn('attribute_set_id', $attributeIds)
                ->distinct()
                ->pluck('id')
                ->toArray();
        } else {
            if ($condition['operator'] == '{}') {
                $productAttributes = ProductAttribute::where('attribute_id', $attributeIds)
                    ->whereIn('value', [$condition['value']])
                    ->distinct()
                    ->pluck('product_id')
                    ->toArray();
            } elseif ($condition['operator'] == '!{}') {
                $productAttributes = ProductAttribute::where('attribute_id', $attributeIds)
                    ->whereNotIn('value', [$condition['value']])
                    ->distinct()
                    ->pluck('product_id')
                    ->toArray();
            } else {
                $productAttributes = ProductAttribute::where('attribute_id', $attributeIds)
                    ->where('value', $condition['operator'], $condition['value'])
                    ->distinct()
                    ->pluck('product_id')
                    ->toArray();
            }
        }

        return $productAttributes;
    }

    public function saveProductIndices($data)
    {
        if ($productsPriceIndices = ProductPriceIndices::insert($data)) {
            return $productsPriceIndices;
        }

        return false;
    }

    public function getProduct($productId)
    {
        return Product::find($productId);
    }

    public function saveRelatedProducts($data)
    {
        if ($productRelation = ProductRelation::create($data)) {
            return $productRelation;
        }

        return false;
    }

    public function deleteRelatedProducts($productId)
    {
        return ProductRelation::where('product_id', $productId)->delete();
    }

    public function getPorductsByCategory($category_ig)
    {
        return ProductCategories::with('product')->where('category_id', $category_ig)->get();
    }

    public function getAllProducts($requestData)
    {
        $products = Product::with(['categories', 'productAttributes'])->where('name', 'like', "%{$requestData['search_text']}%")
            ->where('status', 'active')->where('type','!=','configurable_product');

        if (isset($requestData['offset']) && isset($requestData['limit'])) {
            $products = $products->offset($requestData['offset'])->limit($requestData['limit']);
        }

        if (isset($requestData['category_id']) && $requestData['category_id'] != null) {
            $products = $products->whereHas('categories', function ($query) use ($requestData) {
                return $query->where('category_id', $requestData['category_id']);
            });
        }

        if (isset($requestData['productFilterData']) && $requestData['productFilterData'] != null) {
            foreach ($requestData['productFilterData'] as $key => $data) {
                if ($data != null) {
                    $products = $products->whereHas('productAttributes', function ($query) use ($data, $key) {
                        return $query->where('attribute_id', $key)
                            ->orwhereIn('value', $data);
                    });
                }
            }
        }

        return $products->get();
    }

    public function getProductById($productId)
    {
        $product = Product::where('id', $productId)
            ->with(['productImages', 'productAttributes', 'variations'])
            ->first();

        return $product;
    }
    public function isSkuExist($sku)
    {
        return Product::where('sku', $sku)->exists();
    }

    public function getPendingProducts($data)
    {
        $products = Product::with('categories', 'customer')
            ->where('status', 'pending')
            ->select('id', 'status', 'name', 'sku', 'type', 'price', 'stock_status', 'customer_id', 'tax_category_id')
            ->where(function (Builder $query) use ($data) {
                if (isset($data['status']) && $data['status']) {
                    $query->where('status', '=', $data['status']);
                }

                if (isset($data['stock_status']) && $data['stock_status']) {
                    $query->where('stock_status', '=', $data['stock_status']);
                }
            });

        if (isset($data['category_id']) && $data['category_id'] != '') {
            $products = $products->whereHas('categories', function ($query) use ($data) {
                return $query->where('category_id', $data['category_id']);
            });
        }

        return $products;
    }

    public function getProductsForQuote($data)
    {
        $products = Product::with('categories', 'customer')

            ->select('id', 'status', 'type', 'name', 'sku', 'price', 'stock_status', 'customer_id', 'tax_category_id', 'min_rfq_quantity')
            ->where('status', 'publish')
            ->where(function (Builder $query) use ($data) {
                if (isset($data['status']) && $data['status']) {
                    $query->where('status', '=', $data['status']);
                }
            });

        if (isset($data['sub_category_id']) && $data['sub_category_id'] != '') {
            $products = $products->whereHas('categories', function ($query) use ($data) {
                $query->where('category_id', $data['sub_category_id']);
            });
        } elseif (isset($data['category_id']) && $data['category_id'] != '') {
            $subcategory = Category::where('parent_category_id', $data['category_id'])->pluck('id')->toArray();
            $products = $products->whereHas('categories', function ($query) use ($subcategory) {
                $query->whereIn('category_id', $subcategory);
            });
        }

        if (config('settings.inventory.cataloginventory_options_show_out_of_stock') == 0) {
            $products->where('stock_status', '!=', 'outofstock');
        }

        if (isset($data['search']['value'])) {
            $products = $products->where('name', 'like', "%{$data['search']['value']}%");
        }

        $products = $products->whereIn('type', ['virtual_product', 'simple_product']);

        return $products;
    }

    public function searchVendorProducts($data)
    {
        $products = Product::with('customer', 'categories')
            ->where('status', 'publish')
            ->whereHas('categories', function ($query) use ($data) {
                if (isset($data['category_id'])) {
                    $query->where('category_id', $data['category_id']);
                }
            });

        if (isset($data['customer_id'])) {
            $products = $products->where('customer_id', $data['customer_id']);
        }

        return $products->paginate(config('app.select_options_count'), ['*'], 'page', request()->get('page'));
    }

    public function featuredProductSave($input)
    {
        if ($products = ProductFeatured::create($input)) {
            return $products;
        }

        return false;
    }

    public function deleteFeaturedProduct($id)
    {
        $products = ProductFeatured::find($id);

        return $products->delete();
    }

    public function incrementProductQty($productId, $qty)
    {
        return Product::where('id', $productId)->increment('quantity', $qty);
    }

    public function getRealatedProducts($requestData)
    {
        $products = ProductRelation::with('product')->where('product_id',$requestData['product_id']);
        $products = $products->whereHas('product', function ($query) use ($requestData) {
            if (isset($requestData['offset']) && isset($requestData['limit'])) {
                $products = $query->offset($requestData['offset'])->limit($requestData['limit'])->where('type','!=','configurable_product');
            }
            if (isset($requestData['search_text']) && isset($requestData['search_text'])) {
                $products = $query->where('name', 'like', "%{$requestData['search_text']}%");
            }
        });

        return $products->get();
    }

    public function productSearch($requestData)
    {
        $products = Product::where('status', 'active');
        if (isset($requestData['term']) && $requestData['term']) {
            $products = $products->where('name', 'like', "%{$requestData['term']}%");
        }
        if (isset($requestData['offset']) && isset($requestData['limit'])) {
            $products = $products->offset($requestData['offset'])->limit($requestData['limit']);
        }

        return $products->where('type','!=','configurable_product')->get();
    }

    public function getVariantProduct($productId, $parentId)
    {
        return Product::where('id', '!=', $productId)->where('parent_id', $parentId)->get();
    }

    public function getVariations($id)
    {
        return Product::where('parent_id', $id)->with('productImages')->get();
    }
}
