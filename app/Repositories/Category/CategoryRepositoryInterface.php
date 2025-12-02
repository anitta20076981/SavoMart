<?php

namespace App\Repositories\Category;

interface CategoryRepositoryInterface
{
    public function getForDatatable($data);

    public function save($input);

    public function delete($categoryId);

    public function get($categoryId);

    public function update(array $input);

    public function searchCategory($term);

    public function getTree();

    public function getAllCatagories();

    public function getCategories();

    public function getCatagoriesWhitProduct($categoryId);

    public function searchCategoryWithOffset($filterData);

    public function searchParentCategory($term);

    public function searchSubCategory($term, $categoryId);

    public function getCategoryProducts($categoryId,$searchText);

    public function getCategoryWithAllProducts($categoryId, $searchText);

    public function getAllParentCatagories($requestData);

}
