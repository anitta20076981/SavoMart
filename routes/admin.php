<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Route;

Route::middleware('guest:admin')->name('admin_')->group(function () {
    Route::get('/login', [Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [Auth\LoginController::class, 'login']);
});

Route::middleware('auth:admin')->name('admin_')->group(function () {
    Route::post('/logout', [Auth\LoginController::class, 'destroy'])->name('logout');

    /**
     * Dashboard management
     */
    Route::get('/', [DashboardController::class, 'home'])->name('dashboard');
    Route::prefix('dashboard')->name('dashboard_')->group(function () {
        Route::get('/', [DashboardController::class, 'home'])->name('dashboard');
    });


    /**
     * Banner
     */
    Route::middleware('auth')->prefix('banner')->name('banner_')->group(function () {
        Route::get('/', [BannerController::class, 'listBanner'])->name('list');
        Route::post('table', [BannerController::class, 'bannerListData'])->name('table');
        Route::get('view', [BannerController::class, 'view'])->name('view');
        Route::get('add', [BannerController::class, 'addBanner'])->name('add');
        Route::post('create', [BannerController::class, 'createBanner'])->name('create');
        Route::get('edit', [BannerController::class, 'editBanner'])->name('edit');
        Route::get('remove_file', [BannerController::class, 'removeFile'])->name('remove_file');
        Route::post('update_banner', [BannerController::class, 'updateBanner'])->name('update_banner');
        Route::get('banner_delete', [BannerController::class, 'bannerDelete'])->name('banner_delete');
        Route::post('image_save', [BannerController::class, 'imageSave'])->name('image_save');
        Route::get('fetch_image', [BannerController::class, 'imageData'])->name('fetch_image');
        Route::get('image_delete', [BannerController::class, 'imageDelete'])->name('image_delete');
        Route::post('link_update', [BannerController::class, 'linkUpdate'])->name('link_update');
    });

    /**
     * Customer
     */
    Route::middleware('auth')->prefix('customer')->name('customer_')->group(function () {
        Route::get('/', [CustomerController::class, 'list'])->name('list');
        Route::post('table', [CustomerController::class, 'table'])->name('table');
        Route::get('view', [CustomerController::class, 'view'])->name('view');
        Route::get('add', [CustomerController::class, 'add'])->name('add');
        Route::post('create', [CustomerController::class, 'create'])->name('create');
        Route::get('edit', [CustomerController::class, 'edit'])->name('edit');
        Route::post('update', [CustomerController::class, 'update'])->name('update');
        Route::get('delete', [CustomerController::class, 'delete'])->name('delete');
        Route::post('approve', [CustomerController::class, 'approve'])->name('approve');
        Route::post('quote_request_table', [CustomerController::class, 'quoteRequestTable'])->name('quoteRequest_table');
        Route::post('quote_table', [CustomerController::class, 'quoteTable'])->name('quote_table');
        Route::middleware('auth')->prefix('address')->name('address_')->group(
            function () {
                Route::post('table', [CustomerController::class, 'addressList'])->name('table');
                Route::get('add', [CustomerController::class, 'addCustomerAddress'])->name('add');
                Route::post('save', [CustomerController::class, 'saveCustomerAddress'])->name('save');
                Route::post('update', [CustomerController::class, 'updateCustomerAddress'])->name('update');
                Route::get('view', [CustomerController::class, 'customerAddressView'])->name('view');
                Route::get('delete', [CustomerController::class, 'deleteCustomerAddress'])->name('delete');
                Route::get('customer_address', [CustomerController::class, 'customerAddress'])->name('customer_address');
            }
        );

        Route::middleware('auth')->prefix('brands')->name('brands_')->group(function () {
            Route::post('brand_table', [CustomerController::class, 'brandList'])->name('brand_table');
            Route::get('add', [CustomerController::class, 'brandAdd'])->name('add');
            Route::post('save', [CustomerController::class, 'saveBrand'])->name('save');
            Route::get('edit', [CustomerController::class, 'BrandEdit'])->name('edit');
            Route::post('update', [CustomerController::class, 'updateCustomerBrand'])->name('update');
            Route::get('view', [CustomerController::class, 'customerBrandView'])->name('view');
            Route::post('approve', [CustomerController::class, 'approveCustomerBrand'])->name('approve');
            Route::get('delete', [CustomerController::class, 'deleteCustomerBrand'])->name('delete');
        });
    });

    /**
     * Options management
     */
    Route::prefix('options')->name('options_')->group(function () {
        Route::get('timezones', [OptionsController::class, 'timezones'])->name('timezones');
        Route::get('countries', [OptionsController::class, 'countries'])->name('countries');
        Route::get('country_code', [OptionsController::class, 'countryCodes'])->name('country_code');
        Route::get('currencies', [OptionsController::class, 'currencies'])->name('currencies');
        Route::get('roles', [OptionsController::class, 'roles'])->name('roles');
        Route::get('banner_section', [OptionsController::class, 'bannerSection'])->name('banner_section');
        Route::get('customers', [OptionsController::class, 'customers'])->name('customers');
        Route::get('states', [OptionsController::class, 'states'])->name('states');
        Route::get('content_categories', [OptionsController::class, 'contentCategories'])->name('content_categories');
        Route::get('categoryBasedProducts', [OptionsController::class, 'categoryBasedProducts'])->name('categoryBasedProducts');
        Route::get('categories', [OptionsController::class, 'categories'])->name('categories');
        Route::get('attribute_sets', [OptionsController::class, 'attributeSets'])->name('attribute_sets');
        Route::get('attribute_options', [OptionsController::class, 'attributeOptions'])->name('attribute_options');
        Route::get('rule_attribute_options', [OptionsController::class, 'attributeOptionsForRule'])->name('rule_attribute_options');
        Route::get('products', [OptionsController::class, 'products'])->name('products');
        Route::get('product_attributes', [OptionsController::class, 'productAttributes'])->name('product_attributes');
        Route::get('product_attribute_set', [OptionsController::class, 'productAttributeSet'])->name('product_attribute_set');

    });


    /**
     * Brand
     */
    Route::prefix('application')->name('application_')->group(function () {
        Route::get('/', [ApplicationController::class, 'list'])->name('list');
        Route::post('table', [ApplicationController::class, 'ApplicationListData'])->name('table');
        Route::get('view', [ApplicationController::class, 'view'])->name('view');
        Route::get('add', [ApplicationController::class, 'addApplication'])->name('add');
        Route::post('create', [ApplicationController::class, 'createApplication'])->name('create');
        Route::post('save', [ApplicationController::class, 'save'])->name('save');
        Route::get('edit', [ApplicationController::class, 'editApplication'])->name('edit');
        Route::get('remove_file', [ApplicationController::class, 'removeFile'])->name('remove_file');
        Route::post('update', [ApplicationController::class, 'updateApplication'])->name('update');
        Route::get('delete', [ApplicationController::class, 'deleteApplication'])->name('delete');
        Route::post('status', [ApplicationController::class, 'status'])->name('status');
    });




    /**
     * Contents
     */
    Route::middleware('auth')->prefix('contents')->name('contents_')->group(function () {
        Route::get('/', [ContentsController::class, 'list'])->name('list');
        Route::post('table', [ContentsController::class, 'table'])->name('table');
        Route::get('add', [ContentsController::class, 'add'])->name('add');
        Route::post('save', [ContentsController::class, 'save'])->name('save');
        Route::get('edit', [ContentsController::class, 'edit'])->name('edit');
        Route::post('update', [ContentsController::class, 'update'])->name('update');
        Route::get('delete', [ContentsController::class, 'delete'])->name('delete');
        Route::post('status', [ContentsController::class, 'status'])->name('status');
        Route::get('remove_file', [ContentsController::class, 'removeFile'])->name('remove_file');
    });



    /**
     * Role management
     */
    Route::middleware('auth')->prefix('role')->name('role_')->group(function () {
        Route::get('/', [RolesController::class, 'listRoles'])->name('list');
        Route::get('add', [RolesController::class, 'addRole'])->name('add');
        Route::post('create', [RolesController::class, 'createRole'])->name('create');
        Route::get('view', [RolesController::class, 'viewRole'])->name('view');
        Route::post('users_table', [RolesController::class, 'rolesUsersListData'])->name('users_table');
        Route::get('edit', [RolesController::class, 'editRole'])->name('edit');
        Route::post('update', [RolesController::class, 'updateRole'])->name('update');
        Route::post('status_change', [RolesController::class, 'statusChange'])->name('status_change');
        Route::get('delete', [RolesController::class, 'deleteRole'])->name('delete');
    });

    /**
     * Settings management
     */
    Route::middleware('auth:admin')->prefix('system/store')->name('settings_store_')->group(function () {
        Route::prefix('branding')->name('branding_')->group(function () {
            Route::get('/', [SettingsController::class, 'branding'])->name('view');
        });
        Route::prefix('config')->name('config_')->group(function () {
            Route::get('/', [SettingsController::class, 'configuration'])->name('view');
        });
        Route::prefix('social_config')->name('social_config_')->group(function () {
            Route::get('/', [SettingsController::class, 'socialSettings'])->name('view');
        });
        Route::post('settings/save', [SettingsController::class, 'saveSettings'])->name('save_settings');
    });

    /**
     * User management
     */
    Route::middleware('auth:admin')->prefix('user')->name('user_')->group(function () {
        Route::get('/', [UsersController::class, 'listUsers'])->name('list');
        Route::post('table', [UsersController::class, 'userListData'])->name('table');
        Route::get('add', [UsersController::class, 'addUser'])->name('add');
        Route::post('create', [UsersController::class, 'createUser'])->name('create');
        Route::get('view', [UsersController::class, 'editUser'])->name('edit');
        Route::post('update', [UsersController::class, 'updateUser'])->name('update');
        Route::get('change_password', [UsersController::class, 'changeUserPassword'])->name('change_password');
        Route::post('update_password', [UsersController::class, 'updateUserPassword'])->name('update_password');
        Route::post('status_change', [UsersController::class, 'statusChange'])->name('status_change');
        Route::get('delete', [UsersController::class, 'deleteUser'])->name('delete');
    });

    /**
     * Attribute
     */
    Route::prefix('attribute')->name('attribute_')->group(function () {
        Route::get('/', [AttributeController::class, 'list'])->name('list');
        Route::post('table', [AttributeController::class, 'attributeListData'])->name('table');
        Route::get('view', [AttributeController::class, 'view'])->name('view');
        Route::get('add', [AttributeController::class, 'addAttribute'])->name('add');
        Route::post('create', [AttributeController::class, 'createAttribute'])->name('create');
        Route::post('save', [AttributeController::class, 'save'])->name('save');
        Route::get('edit', [AttributeController::class, 'editAttribute'])->name('edit');
        Route::get('remove_file', [AttributeController::class, 'removeFile'])->name('remove_file');
        Route::post('update', [AttributeController::class, 'updateAttribute'])->name('update');
        Route::get('delete', [AttributeController::class, 'deleteAttribute'])->name('delete');
        Route::post('status', [AttributeController::class, 'status'])->name('status');
    });

    /**
     * Attribute Sets
     */
    Route::prefix('attribute_set')->name('attribute_set_')->group(function () {
        Route::get('/', [AttributeSetController::class, 'list'])->name('list');
        Route::post('table', [AttributeSetController::class, 'attributeListData'])->name('table');
        Route::get('add', [AttributeSetController::class, 'addAttributeSet'])->name('add');
        Route::post('create', [AttributeSetController::class, 'createAttributeSet'])->name('create');
        Route::get('edit', [AttributeSetController::class, 'editAttributeSet'])->name('edit');
        Route::post('update', [AttributeSetController::class, 'updateAttributeSet'])->name('update');
        Route::get('delete', [AttributeSetController::class, 'deleteAttributeSet'])->name('delete');
    });

    /**
     * Categories
     */
    Route::prefix('categories')->name('categories_')->group(function () {
        Route::get('/', [CategoryController::class, 'list'])->name('list');
        Route::post('table', [CategoryController::class, 'table'])->name('table');
        Route::get('view', [CategoryController::class, 'view'])->name('view');
        Route::get('add', [CategoryController::class, 'add'])->name('add');
        Route::post('save', [CategoryController::class, 'save'])->name('save');
        Route::get('edit', [CategoryController::class, 'edit'])->name('edit');
        Route::post('update', [CategoryController::class, 'update'])->name('update');
        Route::get('delete', [CategoryController::class, 'delete'])->name('delete');
        Route::any('tree_form', [CategoryController::class, 'treeForm'])->name('tree_form');
    });


    /**
     * Order
     */
    Route::prefix('order')->name('order_')->group(function () {
        Route::get('/', [OrderController::class, 'list'])->name('list');
        Route::post('table', [OrderController::class, 'table'])->name('table');
        Route::get('view', [OrderController::class, 'view'])->name('view');
        Route::get('add', [OrderController::class, 'add'])->name('add');
        Route::post('save', [OrderController::class, 'save'])->name('save');
        Route::get('edit', [OrderController::class, 'edit'])->name('edit');
        Route::post('update', [OrderController::class, 'update'])->name('update');
        Route::get('delete', [OrderController::class, 'delete'])->name('delete');
        Route::post('products_able', [OrderController::class, 'productsTable'])->name('productsTable');
        Route::post('editproducts_table', [OrderController::class, 'editproductsTable'])->name('editproductsTable');
        Route::get('added_products', [OrderController::class, 'addedProducts'])->name('addedProducts');
        Route::post('ordered_products_table', [OrderController::class, 'orderedProductsTable'])->name('ordered_products_table');
        Route::get('delivered', [OrderController::class, 'deliveryOrder'])->name('delivered');
        Route::get('add_from_qoute', [OrderController::class, 'add'])->name('add_from_qoute');
        Route::get('address_edit', [OrderController::class, 'addressEdit'])->name('address_edit');
        Route::post('address_update', [OrderController::class, 'addressUpdate'])->name('address_update');
        Route::get('cancel', [OrderController::class, 'cancelOrder'])->name('cancel');
        Route::get('pending_list', [OrderController::class, 'pendingList'])->name('pending_list');
        Route::post('pending_table', [OrderController::class, 'pendingTable'])->name('pending_table');
        Route::get('generate_payment_link', [OrderController::class, 'generatePaymentLink'])->name('generate_payment_link');
        Route::post('recent_order_table', [OrderController::class, 'recentOrders'])->name('recent_order_table');
        Route::post('order_graph', [OrderController::class, 'orderGraph'])->name('order_graph');
        Route::post('quantity_update', [OrderController::class, 'quantityUpdate'])->name('quantity_update');

        Route::prefix('invoice')->name('invoice_')->group(
            function () {
                Route::get('add_invoice', [OrderController::class, 'invoiceAdd'])->name('add_invoice');
                Route::post('invoice_create', [OrderController::class, 'invoiceCreate'])->name('invoice_create');
                Route::get('invoice_view', [OrderController::class, 'invoiceView'])->name('invoice_view');
            }
        );

        Route::prefix('shipment')->name('shipment_')->group(
            function () {
                Route::get('add_shipment', [OrderController::class, 'shipmentAdd'])->name('add_shipment');
                Route::post('shipment_create', [OrderController::class, 'shipmentCreate'])->name('shipment_create');
                Route::get('shipment_view', [OrderController::class, 'shippingView'])->name('shipment_view');
            }
        );
    });

     /**
     * Order Returns
     */
    Route::prefix('order_return')->name('order_return_')->group(function () {
        Route::get('/', [OrderReturnController::class, 'list'])->name('list');
        Route::post('table', [OrderReturnController::class, 'table'])->name('table');
        Route::get('view', [OrderReturnController::class, 'view'])->name('view');
        Route::get('add', [OrderReturnController::class, 'add'])->name('add');
        Route::post('save', [OrderReturnController::class, 'save'])->name('save');
        Route::get('edit', [OrderReturnController::class, 'edit'])->name('edit');
        Route::post('update', [OrderReturnController::class, 'update'])->name('update');
        Route::get('delete', [OrderReturnController::class, 'delete'])->name('delete');
        Route::post('products_able', [OrderReturnController::class, 'productsTable'])->name('productsTable');
        Route::post('editproductsTable', [OrderReturnController::class, 'editproductsTable'])->name('editproductsTable');

        Route::get('added_products', [OrderReturnController::class, 'addedProducts'])->name('addedProducts');
        Route::post('image_save', [OrderReturnController::class, 'saveImage'])->name('image_save');
        Route::get('image_delete', [OrderReturnController::class, 'imageDelete'])->name('image_delete');
        Route::get('fetch_image', [OrderReturnController::class, 'imageData'])->name('fetch_image');
        Route::get('status_update', [OrderReturnController::class, 'statusUpdate'])->name('status_update');
    });


    /**
     * Products
     */
    Route::prefix('products')->name('products_')->group(function () {
        Route::get('/', [ProductController::class, 'list'])->name('list');
        Route::post('table', [ProductController::class, 'table'])->name('table');
        Route::get('add', [ProductController::class, 'add'])->name('add');
        Route::post('save', [ProductController::class, 'save'])->name('save');
        Route::get('view', [ProductController::class, 'view'])->name('view');
        Route::get('edit', [ProductController::class, 'edit'])->name('edit');
        Route::post('update', [ProductController::class, 'update'])->name('update');
        Route::get('delete', [ProductController::class, 'delete'])->name('delete');
        Route::post('image_save', [ProductController::class, 'saveImage'])->name('image_save');
        Route::get('fetch_image', [ProductController::class, 'imageData'])->name('fetch_image');
        Route::get('image_delete', [ProductController::class, 'imageDelete'])->name('image_delete');
        Route::get('attribute_form', [ProductController::class, 'attributeForm'])->name('attribute_form');
        Route::post('sku_validation', [ProductController::class, 'skuValidation'])->name('sku_validation');
        Route::get('pending_list', [ProductController::class, 'pendingProducts'])->name('pending_list');
        Route::post('pending_table', [ProductController::class, 'pendingProductsTable'])->name('pending_table');
        Route::post('accept', [ProductController::class, 'publishOrRejectProduct'])->name('accept');
        Route::get('reviews', [ProductController::class, 'listProductReviews'])->name('reviews');
        Route::get('review_update', [ProductController::class, 'updateReview'])->name('review_update');
        Route::get('category_tree_load', [ProductController::class, 'categoryTreeLoad'])->name('category_tree_load');
        Route::get('details', [ProductController::class, 'productDetails'])->name('details');



        /**
         * Configuration
         */
        Route::get('configuration_form', [ProductController::class, 'configurationForm'])->name('configuration_form');
        Route::post('attribute_table', [ProductController::class, 'attributeListData'])->name('attribute_table');
        Route::get('attribute_option_list', [ProductController::class, 'attributeOptionList'])->name('attribute_option_list');
        Route::post('configuartion_summery', [ProductController::class, 'attributeConfigurationSummary'])->name('configuration_summary');
        Route::post('add_variation_list', [ProductController::class, 'addVariationList'])->name('add_variation_list');
        Route::post('edit_variation_list', [ProductController::class, 'editVariationList'])->name('edit_variation_list');
    });

    /**
     * Data Transfer
     */
    Route::prefix('data-transfer')->name('data_transfer_')->group(function () {

        Route::get('/', [ DataTransferController::class, 'index' ])->name('index');

        /**
         *
         * Import
         *
         */
        Route::prefix('import')->name('import_')->group(function () {
            Route::get('/', [ DataTransferController::class, 'importIndex' ])->name('index');
            Route::post('/handle', [ DataTransferController::class, 'handleImport' ])->name('handle');
            Route::get('/dowload', [ DataTransferController::class, 'dowloadSample' ])->name('dowload_sample');

        });
        /**
         *
         * Export
         *
         */
        Route::prefix('export')->name('export_')->group(function () {
            Route::get('/', [ DataTransferController::class, 'exportIndex' ])->name('index');
            Route::post('/handle', [ DataTransferController::class, 'handleExport' ])->name('handle');
        });
    });

    /** terms And Condition */
    Route::prefix('pages')->name('pages_')->group(function () {
        Route::get('/', [PagesController::class, 'list'])->name('list');
        Route::post('table', [PagesController::class, 'table'])->name('table');
        Route::get('add', [PagesController::class, 'add'])->name('add');
        Route::post('save', [PagesController::class, 'save'])->name('save');
        Route::get('edit', [PagesController::class, 'edit'])->name('edit');
        Route::post('update', [PagesController::class, 'update'])->name('update');
        Route::get('delete', [PagesController::class, 'delete'])->name('delete');
        Route::post('status', [PagesController::class, 'status'])->name('status');
    });


});
