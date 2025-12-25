<?php

namespace App\Repositories\Category;

use App\Models\Category;
use App\Models\ProductCategories;
use Illuminate\Database\Eloquent\Builder;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getForDatatable($data)
    {
        $category = Category::select(['id', 'name', 'name_ar', 'status', 'parent_category_id', 'icon'])
            ->orderBy('created_at', 'desc')
            ->where(function (Builder $query) use ($data) {
                // if ($data['status'] != '') {
                //     $query->where('status', '=', $data['status']);
                // }

                if (isset($data['category_id']) && $data['category_id'] != '') {
                    $query->where('parent_category_id', '=', $data['category_id']);
                }
            });

        $category->where('id', '!=', '1');


        return $category;
    }

    public function save($input)
    {
        if ($category = Category::create($input)) {
            return $category;
        }

        return false;
    }

    public function update(array $input)
    {
        $category = Category::find($input['id']);
        unset($input['id']);

        if ($category->update($input)) {
            return $category;
        }

        return false;
    }

    public function get($categoryId)
    {
        return Category::with(['childrenRecursive'])->findOrFail($categoryId);
    }

    public function delete($categoryId)
    {
        $category = Category::findOrFail($categoryId);

        return $category->delete();
    }

    public function searchCategory($term)
    {
        return $categories = Category::where('id', '!=', '1')->where('name', 'like', "%{$term}%")->where('status', 1)
            ->where('parent_category_id', '=', '1')
            ->paginate(config('app.select_options_count'), ['*'], 'page', request()->get('page'));
    }

    public function searchAllCategory($term)
    {
        return $categories = Category::where('id', '!=', '1')->where('name', 'like', "%{$term}%")->where('status', 'active')
            ->paginate(config('app.select_options_count'), ['*'], 'page', request()->get('page'));
    }

    public function getTree()
    {
        $categoryTree = Category::with(['childrenRecursive'])
            // ->where('status', 1)
            ->where('id', '!=', '1')
            ->where('parent_category_id', 1);

        return $categoryTree->get();
    }

    public function getAllCatagories()
    {
        return $categories = Category::with(['childrenRecursive'])
            // ->where('status', 1)
            ->where('id', '!=', '1')

            ->get();
    }

    public function getCategories()
    {
        $categories = Category::with(['products' => function($query) {
                $query->where('status', 'active')->where('type','!=','configurable_product');
            }])
            ->where('id', '!=', 1)
            ->get();

        return $categories;
    }

    public function getAllParentCatagories($requestData)
    {
        $categories = Category::with(['childrenRecursive'])
            ->where('id', '!=', '1')
            ->where('parent_category_id', '=', '1');
            if (isset($requestData['offset']) && isset($requestData['limit'])) {
                $categories = $categories->offset($requestData['offset'])->limit($requestData['limit']);
            }
            if (isset($requestData['search_text']) && isset($requestData['search_text'])) {
                $categories = $categories->where('name', 'like', "%{$requestData['search_text']}%");
            }
        return $categories->get();
    }

    public function getCatagoriesWhitProduct($categoryId)
    {
        $query = Category::with(['productCatagory'])
            ->where('id', '!=', '1')
            ->where('id', $categoryId)
            ->where('status', 'active');

        return $query->first();
    }

    public function searchCategoryWithOffset($filterData)
    {
        $categories = Category::where('name', 'like', "%{$filterData['search_text']}%")
            ->where('status', 'active')
            ->where('id', '!=', '1')
            ;

        if (isset($filterData['offset']) && isset($filterData['limit'])) {
            $categories = $categories->offset($filterData['offset'])->limit($filterData['limit']);
        }

        return $categories->get();
    }

    public function searchParentCategory($term)
    {
        return $categories = Category::where('name', 'like', "%{$term}%")
            ->where('status', 'active')
            ->where('parent_category_id', 0)
            ->where('id', '!=', '1')
            ->paginate(config('app.select_options_count'), ['*'], 'page', request()->get('page'));
    }

    public function searchSubCategory($term, $categoryId)
    {
        return $categories = Category::where('name', 'like', "%{$term}%")
            ->where('status', 'active')
            ->where('parent_category_id', $categoryId)
            ->where('id', '!=', '1')
            ->paginate(config('app.select_options_count'), ['*'], 'page', request()->get('page'));
    }

    public function getCategoryProducts($categoryId,$searchText)
    {
        // return $categories = ProductCategories::with('product')->where('category_id', $categoryId)->get();
        $products = ProductCategories::with('product')
        ->where('category_id', $categoryId);
        if (isset($searchText) && $searchText != null) {
            $products = $products->whereHas('product', function ($query) use ($searchText) {
                return $query->where('name', $searchText)->where('status', 'active')->where('type','!=','configurable_product');
            });
        }else {
            $products = $products->whereHas('product', function ($query) {
                $query->where('status', 'active')->where('type','!=','configurable_product'); // Add condition for active status
            });
        }

        return  $products->get();
    }

    public function getCategoryWithAllProducts($parentCategoryId,$searchText)
    {
        $allCategories = Category::where('parent_category_id', $parentCategoryId)->pluck('id')->toArray();

        array_push($allCategories, $parentCategoryId);

        $products = ProductCategories::with('product')
        ->whereIn('category_id', $allCategories);
        if (isset($searchText) && $searchText != null) {
            $products = $products->whereHas('product', function ($query) use ($searchText) {
                return $query->where('name', $searchText)->where('status', 'active')->where('type','!=','configurable_product');
            });
        }else {
            $products = $products->whereHas('product', function ($query) {
                $query->where('status', 'active')->where('type','!=','configurable_product'); // Add condition for active status
            });
        }

        return  $products->get();



    }

}