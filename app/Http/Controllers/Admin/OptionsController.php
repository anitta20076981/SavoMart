<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Attribute\AttributeRepositoryInterface as AttributeRepository;
use App\Repositories\AttributeSet\AttributeSetRepositoryInterface as AttributeSetRepository;
use App\Repositories\Banner\BannerRepositoryInterface as BannerRepository;
use App\Repositories\Category\CategoryRepositoryInterface as CategoryRepository;
use App\Repositories\Contents\ContentsRepositoryInterface as ContentsRepository;
use App\Repositories\Customer\CustomerRepositoryInterface as CustomerRepository;
use App\Repositories\Products\ProductsRepositoryInterface as ProductsRepository;
use App\Repositories\Settings\SettingsRepositoryInterface as SettingsRepository;
use App\Repositories\User\UserRepositoryInterface as userRepository;
use Illuminate\Http\Request;

class OptionsController extends Controller
{
    public function timezones(Request $request, SettingsRepository $settingsRepo)
    {
        $term = trim($request->search);
        $timezones = $settingsRepo->searchTimezones($term);
        $timezonesOptions = [];

        foreach ($timezones as $timezone) {
            $timezonesOptions[] = ['id' => $timezone->timezone, 'text' => $timezone->name];
        }

        return response()->json($timezonesOptions);
    }

    public function countries(Request $request, SettingsRepository $settingsRepo)
    {
        $term = trim($request->search);
        $countries = $settingsRepo->searchCountry($term);
        $countriesOptions = [];

        foreach ($countries as $country) {
            $countriesOptions[] = ['id' => $country->id, 'text' => $country->short_name];
        }

        return response()->json($countriesOptions);
    }

    public function countryCodes(Request $request, SettingsRepository $settingsRepo)
    {
        $term = trim($request->search);
        $countries = $settingsRepo->searchCountry($term);
        $countriesOptions = [];

        foreach ($countries as $country) {
            $countriesOptions[] = ['id' => $country->id, 'short_name' => $country->short_name, 'image' => asset('images/flags/' . $country->image), 'text' => '+' . $country->country_code . ' - ' . $country->short_name];
        }

        return response()->json($countriesOptions);
    }



    public function roles(UserRepository $userRepo, Request $request)
    {
        $term = trim($request->q);
        $users = $userRepo->searchRole($term);

        $userOptions = [];

        foreach ($users as $user) {
            $userOptions[] = ['id' => $user->id, 'text' => $user->name_display];
        }

        return response()->json($userOptions);
    }

    public function bannerSection(BannerRepository $bannerRepo, Request $request)
    {
        $term = trim($request->q);
        $bannerSection = $bannerRepo->searchBannerSection($term);
        $bannerSectionOptions = [];

        foreach ($bannerSection as $section) {
            $bannerSectionOptions[] = ['id' => $section->id, 'text' => $section->name];
        }

        return response()->json($bannerSectionOptions);
    }

    public function customers(CustomerRepository $customerRepo, Request $request)
    {
        $term = trim($request->search);
        $customers = $customerRepo->searchCustomers($term);
        $customerOptions = [];

        foreach ($customers as $customer) {
            $customerOptions[] = ['id' => $customer->id, 'text' => $customer->name];
        }

        return response()->json($customerOptions);
    }

    public function states(Request $request, SettingsRepository $settingsRepo)
    {
        $term = trim($request->search);
        $states = $settingsRepo->searchState($term);
        $statesOptions = [];

        foreach ($states as $state) {
            $statesOptions[] = ['id' => $state->id, 'text' => $state->name];
        }

        return response()->json($statesOptions);
    }

    public function contentCategories(ContentsRepository $contentRepo, Request $request)
    {
        $term = trim($request->q);
        $not = trim($request->not);
        $categories = $contentRepo->searchContentCategory($term, $not);
        $categoryOptions = [];
        foreach ($categories as $category) {
            $categoryOptions[] = ['id' => $category->id, 'text' => $category->name];
        }
        return response()->json($categoryOptions);
    }

    public function categories(CategoryRepository $categoryRepo, Request $request)
    {
        $term = trim($request->search);
        $categories = $categoryRepo->searchAllCategory($term);
        $categoryOptions = [];

        foreach ($categories as $category) {
            $categoryOptions[] = ['id' => $category->id, 'text' => $category->name];
        }

        return response()->json($categoryOptions);
    }

    public function attributeSets(AttributeSetRepository $attributeSetRepo, Request $request)
    {
        $term = trim($request->search);
        $attributeSets = $attributeSetRepo->searchAttributeSets($term);
        $attributeSetsOptions = [];

        foreach ($attributeSets as $attributeSet) {
            $attributeSetsOptions[] = ['id' => $attributeSet->id, 'text' => $attributeSet->name];
        }

        return response()->json($attributeSetsOptions);
    }

    public function attributeOptions(AttributeRepository $attributeRepo, Request $request)
    {
        $attributeOptions = $attributeRepo->getAttributeOptions($request->id);
        $attributeOptionValues = [];

        foreach ($attributeOptions as $attributeOption) {
            $attributeOptionValues[] = ['swatch' => $attributeOption->swatch, 'label' => $attributeOption->label, 'value' => $attributeOption->value];
        }

        return response()->json($attributeOptionValues);
    }

    public function categoryBasedProducts(ProductsRepository $productsRepo, Request $request)
    {

        $term = trim($request->search);
        $categoryId = $request->category_id;
        $products = $productsRepo->searchCategoryProducts($term, $categoryId);
        $productsOptions = [];

        foreach ($products as $product) {

            $productsOptions[] = ['id' => $product->id, 'text' => $product->name];
        }
        
        return response()->json($productsOptions);
    }

    public function products(ProductsRepository $productsRepo, Request $request)
    {
        $term = trim($request->search);
        $products = $productsRepo->searchProducts($term);
        $productsOptions = [];

        foreach ($products as $product) {
            $productsOptions[] = ['id' => $product->id, 'text' => $product->name];
        }

        return response()->json($productsOptions);
    }

    public function productAttributes(Request $request, AttributeRepository $attributeRepo)
    {
        $term = trim($request->search);
        $attributes = $attributeRepo->searchAttributes($term);
        $attributeOptions = [];

        foreach ($attributes as $attribute) {
            $attributeOptions[] = ['id' => $attribute->code, 'attribute_id' => $attribute->id, 'text' => $attribute->name, 'input_type' => $attribute->input_type];
        }

        return response()->json($attributeOptions);
    }

    public function productAttributeSet(Request $request, AttributeSetRepository $attributeSetRepo)
    {
        $term = trim($request->search);
        $attributeSet = $attributeSetRepo->searchAttributeSet($term);
        $attributeOptions = [];

        foreach ($attributeSet as $set) {
            $attributeOptions[] = ['id' => $set->id, 'text' => $set->name];
        }

        return response()->json($attributeOptions);
    }

    public function parentCategories(CategoryRepository $categoryRepo, Request $request)
    {
        $term = trim($request->search);
        $categories = $categoryRepo->searchParentCategory($term);
        $categoryOptions = [];

        foreach ($categories as $category) {
            $categoryOptions[] = ['id' => $category->id, 'text' => $category->name];
        }

        return response()->json($categoryOptions);
    }

    public function subCategories(CategoryRepository $categoryRepo, Request $request)
    {
        $term = trim($request->search);
        $categoryId = $request->category_id;
        $categories = $categoryRepo->searchSubCategory($term, $categoryId);
        $categoryOptions = [];

        foreach ($categories as $category) {
            $categoryOptions[] = ['id' => $category->id, 'text' => $category->name];
        }

        return response()->json($categoryOptions);
    }
}