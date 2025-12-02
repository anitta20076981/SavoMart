<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Category\CategoryByProductRequest;
use App\Http\Requests\Api\Category\HomeCategoryRequest;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Products\ProductsRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{

    private CategoryRepositoryInterface $categoryRepo;
    private ProductsRepositoryInterface $productsRepo;

    public function __construct(CategoryRepositoryInterface $categoryRepo, ProductsRepositoryInterface $productsRepo)
    {
        $this->categoryRepo = $categoryRepo;
        $this->productsRepo = $productsRepo;
    }

    public function chiledCategory($id, $language_type)
    {
        $category = $this->categoryRepo->get($id);

        $data = $category->childrenRecursive->map(function ($items, $key) use ($language_type) {
            return [
                'id' => $items->id,
                'name' => ($language_type == 'en') ? $items->name : $items->name_ar,
                'logo' => $items->logo != '' ? Storage::disk('grocery')->url($items->logo) : '',
                'icon' => $items->icon != '' ? Storage::disk('grocery')->url($items->icon) : '',
                'status' => $items->status,
            ];

        });
        return $data;
    }

    public function getCategory(Request $request)
    {
        $requestData = $request->all();
        $requestData['sort_by'] = $request->sort_by;
        $requestData['limit'] = $request->has('limit') && $request->limit ? $request->limit : 50;
        $requestData['page'] = $request->has('page') && $request->page ? $request->page : 1;
        $requestData['offset'] = ($requestData['page'] - 1) * $requestData['limit'];
        $requestData['search_text'] = $request->has('search_text') && $request->search_text ? $request->search_text : '';

        $category = $this->categoryRepo->getAllParentCatagories($requestData);
        $language_type = $request->language_type;
        $category = $category->map(function ($items, $key) use ($language_type) {
            return [
                'id' => $items->id,
                'name' => ($language_type == 'en') ? $items->name : $items->name_ar,
                'logo' => $items->logo != '' ? Storage::disk('grocery')->url($items->logo) : '',
                'icon' => $items->icon != '' ? Storage::disk('grocery')->url($items->icon) : '',
                'status' => $items->status,
                'sub_category' => $this->chiledCategory($items->id, $language_type),
            ];

        });

        return \response()->json(['status' => true, 'category' => $category], 200);
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

    public function getproductByCategoryId($category_id, $language_type, $searchText)
    {
        $categoryData = $this->categoryRepo->get($category_id);

        if ($categoryData->parent_category_id !== 1) {
            $categorys = $this->categoryRepo->getCategoryProducts($category_id,$searchText);
        } else {
            $categorys = $this->categoryRepo->getCategoryWithAllProducts($category_id, $searchText);
        }

        $category = $categorys->map(function ($items, $key) use ($language_type) {
           if(isset($items->product->id)){
            return [
                'id' => $items->product->id,
                'sku' => $items->product->sku,
                'name' => ($language_type == 'en') ? $items->product->name : $items->product->name_ar,
                'quantity' => $items->product->quantity,
                'status' => $items->product->status,
                'type' => $items->product->type,
                'description' => ($language_type == 'en') ? $items->product->description : $items->product->description_ar,
                'price' => $items->product->price,
                'special_price' => $items->product->special_price,
                'special_price_to' => $items->product->special_price_to,
                'special_price_from' => $items->product->special_price_from,
                'discount_id' => $items->product->discount_id,
                'discount_percentage' => $items->product->discount_percentage,
                'discount_amount' => $items->product->discount_amount,
                'final_price' => $items->product->final_price,
                'image' => $this->getProductImage($items->product->id),
            ];
           }
        });

        return $category;

    }

    public function getParentCategoryAndProduct($category_id, $language_type)
    {
        $category = $this->categoryRepo->get($category_id);

        return [
            'id' => $category->id,
            'name' => ($language_type == 'en') ? $category->name : $category->name_ar,
            'logo' => $category->logo != '' ? Storage::disk('grocery')->url($category->logo) : '',
            'icon' => $category->icon != '' ? Storage::disk('grocery')->url($category->icon) : '',
            'status' => $category->status,
            'parent_products' => $this->getproductByCategoryId($category->id, $language_type,$searchText=""),
        ];

    }

    public function getCategoryByProduct(CategoryByProductRequest $request)
    {

        $categories = $this->categoryRepo->getCatagoriesWhitProduct($request->category_id);
        $language_type = $request->language_type;
        $searchText = $request->has('search_text') ? $request->search_text : null;
        $category = [
            'id' => $categories->id,
            'name' => ($language_type == 'en') ? $categories->name : $categories->name_ar,
            'parent_category_id' => $categories->parent_category_id,
            'logo' => $categories->logo != '' ? Storage::disk('grocery')->url($categories->logo) : '',
            'icon' => $categories->icon != '' ? Storage::disk('grocery')->url($categories->icon) : '',
            'status' => $categories->status,
            'products' => $this->getproductByCategoryId($categories->id, $language_type,$searchText),

        ];

        return response()->json(['status' => true, 'items' => $category], 200);
    }

    public function homeCategoryWiseProduct(HomeCategoryRequest $request)
    {
        $productLimit = $request->product_limit;
        $categoryLimit = $request->category_limit;
        $category = $this->categoryRepo->getCategories();
        $language_type = $request->language_type;
        // Use array_filter to remove null values from the array
        $category = array_filter($category->toArray());

        return \response()->json(['status' => true, 'category' => $category], 200);
    }

    public function getproductByCategoryIdWithLimit($category_id, $language_type,$productLimit)
    {
        $categoryData = $this->categoryRepo->get($category_id);

        if ($categoryData->parent_category_id !== 1) {
            $categorys = $this->categoryRepo->getCategoryProducts($category_id,$searchText="");
        } else {
            $categorys = $this->categoryRepo->getCategoryWithAllProducts($category_id,$searchText="");
        }

        $products = $categorys->map(function ($items, $key) use ($language_type, $productLimit) {
            // Check if $items->product is not null before accessing its properties
            if ($items->product && $key < $productLimit) {
                if(isset( $items->product->id) && $items->product->type != "configurable_product"){
                    return [
                        'id' => $items->product->id,
                        'sku' => $items->product->sku,
                        'name' => ($language_type == 'en') ? $items->product->name : $items->product->name_ar,
                        'quantity' => $items->product->quantity,
                        'status' => $items->product->status,
                        'type' => $items->product->type,
                        'description' => ($language_type == 'en') ? $items->product->description : $items->product->description_ar,
                        'price' => $items->product->price,
                        'special_price' => $items->product->special_price,
                        'special_price_to' => $items->product->special_price_to,
                        'special_price_from' => $items->product->special_price_from,
                        'discount_id' => $items->product->discount_id,
                        'discount_percentage' => $items->product->discount_percentage,
                        'discount_amount' => $items->product->discount_amount,
                        'image' => $this->getProductImage($items->product->id),
                    ];
                }
            }
        })->filter()->values(); // No need for array_filter, we can use filter() directly

        return $products;

    }

    // public function getCategoryByProduct(CategoryByProductRequest $request)
    // {

    //     $categories = $this->categoryRepo->getCatagoriesWhitProduct($request->category_id);

    //     $category = [
    //         'id' => $categories->id,
    //         'name' => $categories->name,
    //         'parent_category_id' => $categories->parent_category_id,
    //         'logo' => $categories->logo != '' ? Storage::disk('grocery')->url($categories->logo) : '',
    //         'icon' => $categories->icon != '' ? Storage::disk('grocery')->url($categories->icon) : '',
    //         'status' => $categories->status,
    //         'products' => $this->getproductByCategoryId($categories->id),
    //         'parent_category' => ($categories->parent_category_id !== 1) ? $this->getParentCategoryAndProduct($categories->parent_category_id) : null,
    //     ];

    //     return response()->json(['status' => true, 'items' => $category], 200);
    // }

}
