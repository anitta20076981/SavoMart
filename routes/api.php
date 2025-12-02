<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

 /**
 * Auth
 */
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'Login']);
Route::post('forgot_password', [AuthController::class, 'forgotPassword'])->name('forgot_password');
Route::post('otp_generate', [AuthController::class, 'OtpGenerate']);
Route::post('otp_verify', [AuthController::class, 'OtpVerify']);


Route::middleware('auth:sanctum')->group(function () {

     /**
     * Auth
     */
    Route::post('logout', [AuthController::class, 'logout']);

    /**
     * Category
     */
    Route::prefix('category')->group(function () {
        Route::post('/get_all_category', [CategoryController::class, 'getCategory']);
        Route::post('/get_category_with_product', [CategoryController::class, 'getCategoryByProduct']);
        Route::post('/home_category_wise_product', [CategoryController::class, 'homeCategoryWiseProduct']);
    });

    /**
     * Cart
     */
    Route::prefix('cart')->group(function () {
        Route::post('/add_to_cart', [CartController::class, 'addToCart']);
        Route::get('/list_cart', [CartController::class, 'listCart']);
        Route::get('/cancel_cartItem', [CartController::class, 'cancelCartItem']);
        Route::get('/count', [CartController::class, 'cartCount']);
    });

     /**
     * Order
     */
    Route::prefix('order')->group(function () {
        Route::post('/add_to_order', [OrderController::class, 'addToOrder']);
        Route::post('/list_order', [OrderController::class, 'listOrder']);
        Route::get('/cancel_order', [OrderController::class, 'cancelOrderItem']);
        Route::get('/order_details', [OrderController::class, 'orderDetails']);
    });

    /**
    * Order-Return
    */

    Route::prefix('order_return')->group(function () {
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/add_return', [OrderReturnController::class, 'addReturn']);
            Route::post('/edit_return', [OrderReturnController::class, 'editReturn']);
            Route::post('/delete_return', [OrderReturnController::class, 'deleteReturn']);
            Route::post('/list_return', [OrderReturnController::class, 'listReturn']);
            Route::post('/return_status_change', [OrderReturnController::class, 'statusChange']);
        });
    });

    /**
     * Cms
     */
    Route::prefix('cms')->group(function () {
        Route::get('/get_home_banner', [CmsController::class, 'getHomeBanner']);
    });

    /**
     * Products
     */
    Route::prefix('products')->group(function () {
        Route::any('/get_product_by_id', [ProductsController::class, 'getProductById']);
        Route::get('/get_all_featured', [ProductsController::class, 'getAllFeatured']);
        Route::post('/list_product', [ProductsController::class, 'listAllProducts']);
        Route::post('/list_related_products', [ProductsController::class, 'listRelatedProducts']);
        Route::post('/product_search', [ProductsController::class, 'productSearch']);
    });

    /**
     * whishlist
     */
    Route::prefix('whishlist')->group(function () {
        Route::post('/add_whishlist', [WishlistController::class, 'addWhishlist']);
        Route::get('/wishlist', [WishlistController::class, 'wishList']);
        Route::post('/allwishlist', [WishlistController::class, 'allWishList']);
    });

    /**
    * customer
    */
    Route::prefix('customer')->group(function () {
        Route::get('/get_customer', [CustomerController::class, 'getCustomer']);
        Route::post('/change_password', [CustomerController::class, 'changeUserPassword']);
        Route::post('/add_address', [CustomerController::class, 'addAdress']);
        Route::get('/list_address', [CustomerController::class, 'listAdress']);
        Route::post('/select_address', [CustomerController::class, 'selectAddress']);
        Route::get('/get_selected_address', [CustomerController::class, 'getSelectedAddress']);
        Route::post('/delete_address', [CustomerController::class, 'deleteAddress']);
        Route::post('/update', [CustomerController::class, 'customerUpdate']);
        Route::post('/edit_address', [CustomerController::class, 'updateAddress']);
        Route::post('/edit_profile_image', [CustomerController::class, 'updateProfileImage']);

    });

});

/**
 * Settings
 */
Route::prefix('settings')->group(function () {
    Route::get('/get_settings', [SettingsController::class, 'getSettings']);
});
Route::prefix('pages')->group(function () {
    Route::get('/terms_and_condition', [PagesController::class, 'termsAndCondition']);
});



