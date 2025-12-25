<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Includes\Product\Configuration;
use App\Http\Controllers\Admin\Includes\Product\Image;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Products\ProductsAddRequest;
use App\Http\Requests\Admin\Products\ProductsCreateRequest;
use App\Http\Requests\Admin\Products\ProductsDeleteRequest;
use App\Http\Requests\Admin\Products\ProductsEditRequest;
use App\Http\Requests\Admin\Products\ProductsListDataRequest;
use App\Http\Requests\Admin\Products\ProductsListRequest;
use App\Http\Requests\Admin\Products\ProductsUpdateRequest;
use App\Repositories\Attribute\AttributeRepositoryInterface as AttributeRepository;
use App\Repositories\AttributeSet\AttributeSetRepositoryInterface as AttributeSetRepository;
use App\Repositories\Category\CategoryRepositoryInterface as CategoryRepository;
use App\Repositories\Products\ProductsRepositoryInterface as ProductsRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use stdClass;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    use Image;
    use Configuration;

    private $attributeSetRepo;

    private $attributeRepo;

    private $productRepo;

    public function __construct(
        AttributeSetRepository $attributeSetRepo,
        AttributeRepository $attributeRepo,
        ProductsRepository $productRepo,
        CategoryRepository $categoryRepo,
    ) {
        $this->attributeSetRepo = $attributeSetRepo;
        $this->attributeRepo = $attributeRepo;
        $this->productRepo = $productRepo;
        $this->categoryRepo = $categoryRepo;
    }

    public function list(ProductsListRequest $request)
    {
        $breadcrumbs = [
            ['name' => 'Products'],
        ];

        $products = $this->productRepo->getProducts($request->all())->get();

        return view('admin.products.listProducts', compact('breadcrumbs','products'));
    }

    public function table(ProductsListDataRequest $request)
    {
        $products = $this->productRepo->getProducts($request->all());
        $dataTableJSON = DataTables::of($products)
            ->addIndexColumn()
            ->editColumn('name', function ($products) {
                $data['url'] = request()->user()->can('products_view') ? route('admin_products_edit', ['id' => $products->id]) : '';
                $data['text'] = $products->name;

                return view('admin.elements.listLink', compact('data'));
            })
            ->editColumn('name_ar', function ($products) {
                $data['url'] = request()->user()->can('products_view') ? route('admin_products_edit', ['id' => $products->id]) : '';
                $data['text'] = $products->name_ar;

                return view('admin.elements.listLink', compact('data'));
            })
            ->editColumn('stock_status', function ($products) {
                return view('admin.products.stockStatus')->with('data', $products);
            })
            ->editColumn('status', function ($products) {
                return view('admin.products.listStatus')->with('data', $products);
            })
            ->editColumn('type', function ($products) {
                return view('admin.products.listType')->with('data', $products);
            })
            ->addColumn('action', function ($products) use ($request) {
                $data['edit_url'] = request()->user()->can('products_update') ? route('admin_products_edit', ['id' => $products->id]) : '';
                $data['delete_url'] = request()->user()->can('products_delete') && $products->can_delete ? route('admin_products_delete', ['id' => $products->id]) : '';

                return view('admin.elements.listAction', compact('data'));
            })
            ->make();

        return $dataTableJSON;
    }

    public function add(ProductsAddRequest $request, CategoryRepository $categoryRepo)
    {
        $breadcrumbs = [
            ['link' => 'admin_products_list', 'name' => 'Products', 'permission' => 'products_create'],
            ['name' => 'Add Products'],
        ];
        $categories = $categoryRepo->getTree();

        $old = [];

        if (old('attribute_set_id', 1)) {
            $old['attribute_set_id'] = $this->attributeSetRepo->getAttributeSet(old('attribute_set_id', 1));
        }

        return view('admin.products.addProducts', compact('breadcrumbs', 'categories', 'old'));
    }

    public function save(ProductsCreateRequest $request)
    {
        try {
            DB::beginTransaction();
            $status = $this->_productStatus($request->quantity);
            $input = [
                'name' => $request->productName,
                'name_ar' => $request->name_ar,
                'sku' => $request->sku,
                'attribute_set_id' => $request->attribute_set_id,
                'description' => $request->description,
                'description_ar' => $request->description_ar,
                'price' => $request->price,
                'quantity' => $request->quantity,
                'stock_status' => $status,
                'special_price_from' => isset($request->special_price_from) ? $request->special_price_from : null,
                'special_price_to' => isset($request->special_price_to) ? $request->special_price_to : null,
                'special_price' => $request->special_price,
                'discount_amount' => $request->discount_option == 'fixed_price' ? $request->discounted_price : 0,
                'discount_percentage' => $request->discount_option == 'percentage' ? $request->discount_percentage : 0,
                'discount_id' => $request->discount_option,
                'status' => $request->has('status') ? $request->status : 'publish',
                'delivery_expected_time' => $request->delivery_expected_time,
            ];

            if (isset($request->product_variations) && count($request->product_variations)) {
                $input['type'] = 'configurable_product';
            } else {
                $input['type'] = 'simple_product';
            }


            $product = $this->productRepo->save($input);

            // dd($product);
            //feati=ured -product
            if (isset($request->featured_product)) {
                $featuredInputData =
                    [
                        'product_id' => $product->id,
                        'from' => Carbon::now(),
                        'to' => Carbon::now()->addDays(10),
                    ];
                $this->productRepo->featuredProductSave($featuredInputData);
            }

            //related-product
            if ($request->has('related_product_id')) {
                foreach ($request->related_product_id as $relatedProductId) {
                    $relatedProducts = [
                        'product_id' => $product->id,
                        'related_product_id' => $relatedProductId,
                    ];
                    $this->productRepo->saveRelatedProducts($relatedProducts);
                }
            }


            if (isset($request->product_variations) && count($request->product_variations) && $product) {

                $this->_createProductVariations($request, $product->id);
            }



            if (isset($request->product_attributes) && count($request->product_attributes) && $product) {
                $this->_createProductAttributes($request, $product->id);
            }


            //product Image
            $productImages = [];

            if ($request->hasFile('thumbnail')) {
                $filePath = 'products/thumbnail';
                $productImages = [
                    'product_id' => $product->id,
                    'image_path' => Storage::disk('savomart')->putFile($filePath, $request->file('thumbnail')),
                    'image_role' => 'THUMBNAIL',
                ];
                $this->productRepo->saveImages($productImages);
            } elseif ($request->has('thumbnail_remove') && $request->thumbnail_remove) {
                $productImages = [
                    'product_id' => $product->id,
                    'image_path' => '',
                    'image_role' => 'THUMBNAIL',
                ];
            }

            if ($request->has('images')) {
                foreach ($request->images as $key => $image) {
                    $type = explode('/', $image);
                    $productImages = [
                        'product_id' => $product->id,
                        'image_path' => $image,
                        'image_role' => 'BASE',
                        'type' => $type[1] == 'videos' ? 'video' : 'image',
                    ];
                    $this->productRepo->saveImages($productImages);
                }
            }

            if ($request->has('categories')) {
                foreach ($request->categories as $key => $category) {
                    $productCategory = [
                        'product_id' => $product->id,
                        'category_id' => $category,
                    ];
                    $this->productRepo->saveProductCategory($productCategory);
                }
            }

            $event = auth()->user()->name . ' Added the Product with name ' . $request->productName;
            // activity('Products')->performedOn($product)->event($event)->withProperties(['product_id' => $product->id, 'data' => $request->all()])->log('Product Created');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage() . ' - ' . $e->getLine());
        }
        DB::commit();

        return redirect()->route('admin_products_list')->with('success', 'Product added successfully');
    }

    public function view(Request $request, ProductsRepository $productRepo)
    {
        $product = $productRepo->get($request->id);
        $image = $product ? ($product->thumbnail && Storage::disk('savomart')->exists($product->thumbnail) ? Storage::disk('savomart')->url($product->thumbnail) : asset('images/admin/logos/logo-trans.png')) : '';

        if ($request->expectsJson()) {
            $responce['html'] = (string) view('admin.products.viewProduct', compact('product', 'image'));
            $responce['scripts'][] = (string) mix('js/admin/products/viewProducts.js');

            return $responce;
        }
    }

    public function edit(
        ProductsEditRequest $request,
        ProductsRepository $productsRepo,
        CategoryRepository $categoryRepo,
    ) {
        $breadcrumbs = [
            ['link' => 'admin_products_list', 'name' => 'Products', 'permission' => 'products_update'],
            ['name' => 'Edit Products'],
        ];

        $product = $this->productRepo->get($request->id);

        // $pluckAttributeValue = $this->attributeRepo->getAttributesValueByTypeKey($request->id, 'color');
        // $pluckAttributeValue->dd();

        $thumbnail = $this->productRepo->getthumbnail($product->id);
        $categories = $categoryRepo->getTree();

        $selectedCategories = $this->productRepo->getProductCategories($request->id);

        $varientAttributeOptions = [];
        $variations = [];
        $varientAttribute = [];

        $varientProductIds = $this->productRepo->getVariationsUsingParentId($request->id);

        if (count($varientProductIds) && isset($varientProductIds)) {
            $varientAttributeOptions = $this->attributeRepo->getVarientProductsAttributeOptions($varientProductIds);
            $varientAttribute = $this->attributeRepo->getVarientProductsAttribute($varientProductIds);
        }

        if (isset($product->variations) && count($product->variations)) {
            $variations = $product->variations;

            $variations = $variations->map(function ($variation, $key) {
                $variation->thumbnail = '';

                if ($variation) {
                    $thumbnail = $this->productRepo->getthumbnail($variation->id);
                    $variation->thumbnail = $thumbnail;
                }

                return $variation;
            });
        }

        $old = [];
        $attributeSets = $this->attributeSetRepo->getAllAttributeSets();

        if (old('tax_category_id', $product->tax_category_id)) {
            $old['tax_category_id'] = $taxRepo->getTaxCategory(old('tax_category_id', $product->tax_category_id));
        }

        if (old('attribute_set_id', $product->attribute_set_id)) {
            $old['attribute_set_id'] = $this->attributeSetRepo->getAttributeSet(old('attribute_set_id', $product->attribute_set_id));
        }

        return view(
            'admin.products.editProducts',
            compact(
                'breadcrumbs',
                'product',
                'thumbnail',
                'categories',
                'selectedCategories',
                'old',
                'attributeSets',
                'varientAttributeOptions',
                'varientAttribute',
                'variations',
            )
        );
    }

    public function update(ProductsUpdateRequest $request)
    {
        $currentProduct = $this->productRepo->get($request->id);

        try {
            DB::beginTransaction();
            $status = $this->_productStatus($request->quantity);
            $input = [
                'id' => $request->id,
                'name' => $request->productName,
                'name_ar' => $request->name_ar,
                'sku' => $request->sku,
                'description' => $request->description,
                'description_ar' => $request->description_ar,
                'status' => $request->productStatus,
                'price' => $request->price,
                'quantity' => $request->quantity,
                // 'discount_id' => $request->discount_id,
                'attribute_set_id' => $request->attribute_set_id,
                'stock_status' => $status,
                'brand_id' => $request->brand_id,
                'special_price_from' => isset($request->special_price_from) ? $request->special_price_from : null,
                'special_price_to' => isset($request->special_price_to) ? $request->special_price_to : null,
                'special_price' => $request->special_price,
                'discount_amount' => $request->discount_option == 'fixed_price' ? $request->discounted_price : 0,
                'discount_percentage' => $request->discount_option == 'percentage' ? $request->discount_percentage : 0,
                'discount_id' => $request->discount_option,
                'customer_id' => isset($request->customer_id) ? $request->customer_id : null,
                'tax_category_id' => isset($request->tax_category_id) ? $request->tax_category_id : null,
                'min_rfq_quantity' => $request->min_rfq_quantity ? $request->min_rfq_quantity : 0,
                'is_return' => $request->has('is_return') && $request->is_return == '1' ? 'yes' : 'no',
                'return_policy' => $request->return_policies,
                'return_days' => $request->has('is_return') && $request->is_return == '1' ? $request->return_days : 0,
                'delivery_expected_time' => $request->delivery_expected_time
            ];

            switch ($currentProduct->type) {
                case 'virtual_product':
                    $input['type'] = 'virtual_product';

                    break;

                case 'configurable_product':
                    $input['type'] = 'configurable_product';

                    break;

                case 'simple_product':
                    $input['type'] = 'simple_product';

                    break;
            }

            if (isset($request->product_variations) && count($request->product_variations)) {
                $input['type'] = 'configurable_product';
            }

            $product = $this->productRepo->update($input);

            if (isset($request->product_attributes) && count($request->product_attributes) && $product) {
                $this->_updateProductAttributes($request, $product->id);
            }

            if ($product->type == 'configurable_product') {
                if (isset($request->available_product_variations) && count($request->available_product_variations) && $product) {
                    // $this->productRepo->deleteVariationsUsingParentId($product->id, $request->available_product_variations);
                } else {
                    $this->productRepo->deleteVariantsUsingParentId($product->id);
                    $this->attributeRepo->deleteProductConfiguredAttributes($product->id);
                }
            }

            if (isset($request->product_variations) && count($request->product_variations) && $product) {
                $this->_updateProductVariations($request, $product->id);
            }

            // featured-product

            if (isset($request->featured_product) && $currentProduct->featuredProduct == null) {
                $featuredInputData =
                    [
                        'product_id' => $currentProduct->id,
                        'from' => Carbon::now(),
                        'to' => Carbon::now()->addDays(10),
                    ];
                $this->productRepo->featuredProductSave($featuredInputData);
            }

            if (!(isset($request->featured_product)) && $currentProduct->featuredProduct != null) {
                $this->productRepo->deleteFeaturedProduct($currentProduct->featuredProduct->id);
            }

            //related -products
            $this->productRepo->deleteRelatedProducts($product->id);

            if ($request->has('related_product_id')) {
                foreach ($request->related_product_id as $relatedProductId) {
                    $relatedProducts = [
                        'product_id' => $product->id,
                        'related_product_id' => $relatedProductId,
                    ];
                    $this->productRepo->saveRelatedProducts($relatedProducts);
                }
            }

            if ($request->hasFile('thumbnail')) {
                $filePath = 'products/thumbnail';
                $productImages = [
                    'product_id' => $product->id,
                    'image_path' => Storage::disk('savomart')->putFile($filePath, $request->file('thumbnail')),
                    'image_role' => 'THUMBNAIL',
                ];
                $currentProduct->productThumbnail != null ? $this->productRepo->updateThumbnail($productImages) :
                    $this->productRepo->saveImages($productImages);
            } elseif ($request->has('thumbnail_remove') && $request->thumbnail_remove) {
                $productImages = [
                    'product_id' => $product->id,
                    'image_path' => '',
                    'image_role' => 'THUMBNAIL',
                ];
                $this->productRepo->updateThumbnail($productImages);
            }
            $productImageIds = [];

            if (isset($request->images) && $request->images) {
                foreach ($request->images as $id => $image) {
                    $type = explode('/', $image);
                    $productImages = [
                        'id' => $id,
                        'product_id' => $product->id,
                        'image_path' => $image,
                        'image_role' => 'BASE',
                        'type' => $type[1] == 'videos' ? 'video' : 'image',
                    ];
                    $productImage = $this->productRepo->saveImages($productImages);
                    $productImageIds[] = $productImage->id;
                }
            }
            $this->productRepo->deleteImages($product->id, $notIn = $productImageIds);

            $this->productRepo->deleteCategories($request->id);

            if ($request->has('categories')) {
                foreach ($request->categories as $key => $category) {
                    $productCategory = [
                        'product_id' => $request->id,
                        'category_id' => $category,
                    ];
                    $this->productRepo->saveProductCategory($productCategory);
                }
            }

            /**end */
            $event = auth()->user()->name . ' Updated the Product with name ' . $currentProduct->name;
            // activity('Products')->performedOn($product)->event($event)->withProperties(['product_id' => $product->id, 'data' => $request->all(), 'old' => $currentProduct])->log('Product Updated');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
        DB::commit();

        return redirect()
            ->route('admin_products_list')
            ->with('success', 'Product Updated successfully');
    }

    public function delete(ProductsDeleteRequest $request)
    {
        $products = $this->productRepo->get($request->id);

        if ($this->productRepo->delete($request->id)) {

            return response()->json(['status' => 1, 'message' => 'success']);
        }

        return response()->json(['status' => 0, 'message' => 'failed']);
    }

    public function attributeForm(Request $request)
    {
        $attributeSet = $this->attributeSetRepo->getAttributeSetForProducts($request->setId);
        $attributes = $attributeSet->attributes;
        $product = null;

        if ($request->productId) {
            $product = $this->productRepo->get($request->productId);
        }
        $attributes = $attributes->map(function ($attribute, $key) use ($product) {
            $attribute->value = '';
            $attribute->product_type = '';

            if ($product) {
                $attributeValue = $this->attributeRepo->getProductAttributeValue($product->id, $attribute->id);
                $attributeProductType = $this->attributeRepo->getProductAttributeProductType($product->id, $attribute->id);
                $attribute->value = $attributeValue;
                $attribute->product_type = $attributeProductType;

                if ($attribute->code == 'brand') {
                    $attribute->brand = $this->brandRepo->getBrand($attributeValue);
                }
            }

            return $attribute;
        });

        $response['html'] = (string) view('admin.products.attributeForm', compact('attributes'));

        return $response;
    }

    private function _createProductAttributes($request, $productId)
    {
        foreach ($request->product_attributes as $key => $attributeValue) {
            $attributeInputData = [
                'product_id' => $productId,
                'product_type' => 'simple',
                'attribute_id' => $key,
                'value' => $attributeValue ? $attributeValue : '',
            ];
            $this->attributeRepo->createProductAttributes($attributeInputData);
        }
    }

    private function _createProductVariationsAttributes($variationIds, $productId, $parentId)
    {
        $attributeValuesIds = preg_split("/\,/", $variationIds);

        $attributeValues = $this->attributeRepo->getAttributeUsingOptionIds($attributeValuesIds);

        if (isset($attributeValues) && count($attributeValues)) {
            foreach ($attributeValues as $attributeValue) {
                $attributeInputData = [
                    'product_id' => $productId,
                    'product_type' => 'virtual',
                    'attribute_id' => $attributeValue->attribute->id,
                    'value' => $attributeValue->value,
                ];
                $productAttribute = $this->attributeRepo->createProductAttributes($attributeInputData);

                $attributeInputData = [
                    'product_id' => $parentId,
                    'attribute_id' => $attributeValue->attribute->id,
                    'attribute_value_id' => $attributeValue->id,
                ];
                $this->attributeRepo->updateProductConfiguredAttributes($attributeInputData);
            }
        }
    }

    private function _updateProductAttributes($request, $productId)
    {
        foreach ($request->product_attributes as $key => $attributeValue) {
            $attributeInputData = [
                'product_id' => $productId,
                'product_type' => 'simple',
                'attribute_id' => $key,
                'value' => $attributeValue ? $attributeValue : '',
            ];

            $this->attributeRepo->updateProductAttributes($attributeInputData);
        }
    }

    private function _getRulesEffecting($productId)
    {
        $product = $this->productRepo->get($productId);
        $attributes = [];
        $productAttribs = [];

        if (isset($product->ProductAttributeSet)) {
            $attributes = array_merge($attributes, ['attribute_set']);
        }

        if (isset($product->categories)) {
            $attributes = array_merge($attributes, ['catagories']);
        }

        if (isset($product->productAttributes)) {
            foreach ($product->productAttributes as $key => $attribute) {
                $attributeCode = $this->attributeRepo->getAttributeCodeById($attribute->attribute_id);
                $productAttribs[] = $attributeCode;
            }
            $attributes = array_merge($attributes, $productAttribs);
        }
    }

    private function _productStatus($qty)
    {
        $lowStockthresholdQty = config('settings.inventory.cataloginventory_options_stock_threshold_qty');
        $outofStockThreshouldQty = config('settings.inventory.cataloginventory_item_options_min_qty');

        if ($qty <= $outofStockThreshouldQty) {
            $status = 'outofstock';
        } elseif ($qty <= $lowStockthresholdQty) {
            $status = 'lowstock';
        } else {
            $status = 'instock';
        }

        return $status;
    }

    public function pendingProducts(ProductsListRequest $request)
    {
        $breadcrumbs = [
            ['name' => 'Pending Products'],
        ];

        return view('admin.products.pendingProducts.listPendingProducts', compact('breadcrumbs'));
    }

    public function pendingProductsTable(ProductsListDataRequest $request)
    {
        $products = $this->productRepo->getPendingProducts($request->all());
        $dataTableJSON = DataTables::of($products)
            ->addIndexColumn()
            ->editColumn('name', function ($products) {
                $data['url'] = request()->user()->can('products_view') ? route('admin_products_edit', ['id' => $products->id]) : '';
                $data['text'] = $products->name;

                return view('admin.elements.listLink', compact('data'));
            })
            ->editColumn('stock_status', function ($products) {
                return view('admin.products.stockStatus')->with('data', $products);
            })
            ->editColumn('quantity', function ($products) {
                return formatStock($products->productInventory->quantity);
            })
            ->editColumn('status', function ($products) {
                return view('admin.products.listStatus')->with('data', $products);
            })
            ->editColumn('type', function ($products) {
                return view('admin.products.listType')->with('data', $products);
            })
            ->addColumn('action', function ($products) use ($request) {
                $data['edit_url'] = request()->user()->can('products_update') ? route('admin_products_edit', ['id' => $products->id]) : '';
                $data['delete_url'] = request()->user()->can('products_delete') ? route('admin_products_delete', ['id' => $products->id]) : '';

                return view('admin.elements.listAction', compact('data'));
            })
            ->make();

        return $dataTableJSON;
    }

    public function publishOrRejectProduct(Request $request)
    {
        $product = $this->productRepo->get($request->product_id);

        if ($request->action == 'publish') {
            if ($product->status == 'publish') {
                return response()->json(['status' => 0, 'message' => 'Product Already Published']);
            }

            if ($product->status == 'rejected') {
                return response()->json(['status' => 0, 'message' => 'Rejected Product Can not be Published']);
            }

            $message = 'Product Published successfully';
            $status = 'publish';
            $action = 'Published';
        } else {
            if ($product->status == 'rejected') {
                return response()->json(['status' => 0, 'message' => 'Product Already Rejected']);
            }

            if ($product->status == 'publish') {
                return response()->json(['status' => 0, 'message' => 'Published Product Can not be Rejected']);
            }

            $message = 'Product Rejected successfully';
            $status = 'rejected';
            $action = 'Rejected';
        }
        $input = [
            'id' => $request->product_id,
            'status' => $status,
        ];
        $this->productRepo->update($input);
        $event = auth()->user()->name . '' . $action . ' the Product with name ' . $request->productName;
        activity('Products')->performedOn($product)->event($event)->withProperties(['product_id' => $product->id, 'data' => $request->all()])->log('Product ' . $action);

        return response()->json(['status' => 1, 'message' => $message]);
    }

    public function listProductReviews(Request $request)
    {
        $review = $this->productRepo->getReview($request->id);
        $images = [];

        if ($review->images) {
            foreach ($review->images as $image) {
                $images[] = $image ? ($image->file && Storage::disk('savomart')->exists($image->file) ? Storage::disk('savomart')->url($image->file) : asset('images/admin/logos/logo-trans.png')) : '';
            }
        }

        if ($request->expectsJson()) {
            $responce['html'] = (string) view('admin.products.review', compact('review', 'images'));
            $responce['scripts'][] = (string) mix('js/admin/products/viewReview.js');

            return $responce;
        }
    }

    public function updateReview(Request $request)
    {
        $review = $this->productRepo->getReview($request->review_id);

        if ($request->action == 'publish') {
            if ($review->status == 'publish') {
                return response()->json(['status' => 0, 'message' => 'Already published']);
            }

            if ($review->status == 'rejected') {
                return response()->json(['status' => 0, 'message' => 'Rejected Review can not be published']);
            }
            $input = [
                'id' => $request->review_id,
                'status' => $request->action,
                'title' => $request->title,
            ];
        } else {
            if ($review->status == 'rejected') {
                return response()->json(['status' => 0, 'message' => 'Already Rejected']);
            }

            if ($review->status == 'publish') {
                return response()->json(['status' => 0, 'message' => 'Published Review can not be rejected']);
            }
            $input = [
                'id' => $request->review_id,
                'status' => $request->action,
            ];
        }
        $this->productRepo->updateReview($input);

        return response()->json(['status' => 1, 'message' => 'success']);
    }

    public function categoryTreeLoad(Request $request)
    {
        $categories = $this->categoryRepo->getAllCatagories();
        $productId = $request->product_id;
        $selectedCategories = $this->productRepo->getProductCategories($productId);
        $responce['html'] = (string) view('admin.category.treeForm', compact('categories', 'selectedCategories'));

        return $responce;
    }

    public function productDetails(Request $request)
    {
        $productDetails =  $this->productRepo->getProductById($request->product_id);
        return response()->json(['status' => 1, 'data' => $productDetails]);
    }
}