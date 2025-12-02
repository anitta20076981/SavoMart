const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Admin Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

/**
 * misc
 **/
mix.copy('resources/images/admin', 'public/images/admin');
mix.copy('node_modules/tinymce/skins', 'public/css/admin/tinymce/skins');

/**
 * layouts
 */
mix.sass('resources/sass/admin/layouts/plugins.scss', 'public/css/admin/layouts');
mix.sass('resources/sass/admin/layouts/app.scss', 'public/css/admin/layouts');

/**
 * Auth
 */
mix.js('resources/js/admin/auth/login.js', 'public/js/admin/auth');
mix.sass('resources/sass/admin/auth/login.scss', 'public/css/admin/auth');

/**
 * Dashboard
 */
mix.js('resources/js/admin/dashboard/home.js', 'public/js/admin/dashboard');
mix.sass('resources/sass/admin/dashboard/home.scss', 'public/css/admin/dashboard');


/**
 * Applications
 */
mix.js('resources/js/admin/application/listApplication.js', 'public/js/admin/application');
mix.sass('resources/sass/admin/application/listApplication.scss', 'public/css/admin/application');
mix.js('resources/js/admin/application/addApplication.js', 'public/js/admin/application');
mix.sass('resources/sass/admin/application/addApplication.scss', 'public/css/admin/application');
mix.js('resources/js/admin/application/editApplication.js', 'public/js/admin/application');
mix.sass('resources/sass/admin/application/editApplication.scss', 'public/css/admin/application');


/**
 * Banner
 */
mix.js('resources/js/admin/banner/listBanner.js', 'public/js/admin/banner');
mix.sass('resources/sass/admin/banner/listBanner.scss', 'public/css/admin/banner');
mix.js('resources/js/admin/banner/addBanner.js', 'public/js/admin/banner');
mix.sass('resources/sass/admin/banner/addBanner.scss', 'public/css/admin/banner');
mix.js('resources/js/admin/banner/editBanner.js', 'public/js/admin/banner');
mix.sass('resources/sass/admin/banner/editBanner.scss', 'public/css/admin/banner');

/*
 * Customer
 */
mix.js('resources/js/admin/customer/addCustomer.js', 'public/js/admin/customer');
mix.sass('resources/sass/admin/customer/addCustomer.scss', 'public/css/admin/customer');
mix.js('resources/js/admin/customer/editCustomer.js', 'public/js/admin/customer');
mix.sass('resources/sass/admin/customer/editCustomer.scss', 'public/css/admin/customer');
mix.js('resources/js/admin/customer/listCustomer.js', 'public/js/admin/customer');
mix.sass('resources/sass/admin/customer/listCustomer.scss', 'public/css/admin/customer');

/**
 * Contents
 */
mix.js('resources/js/admin/contents/addContents.js', 'public/js/admin/contents');
mix.sass('resources/sass/admin/contents/addContents.scss', 'public/css/admin/contents');
mix.js('resources/js/admin/contents/editContents.js', 'public/js/admin/contents');
mix.sass('resources/sass/admin/contents/editContents.scss', 'public/css/admin/contents');
mix.js('resources/js/admin/contents/listContents.js', 'public/js/admin/contents');
mix.sass('resources/sass/admin/contents/listContents.scss', 'public/css/admin/contents');

/**
 * Pages
 */
mix.js('resources/js/admin/pages/addPages.js', 'public/js/admin/pages');
mix.sass('resources/sass/admin/pages/addPages.scss', 'public/css/admin/pages');
mix.js('resources/js/admin/pages/editPages.js', 'public/js/admin/pages');
mix.sass('resources/sass/admin/pages/editPages.scss', 'public/css/admin/pages');
mix.js('resources/js/admin/pages/listPages.js', 'public/js/admin/pages');
mix.sass('resources/sass/admin/pages/listPages.scss', 'public/css/admin/pages');
/**
 * Roles
 */
mix.js('resources/js/admin/roles/addRole.js', 'public/js/admin/roles');
mix.sass('resources/sass/admin/roles/addRole.scss', 'public/css/admin/roles');
mix.js('resources/js/admin/roles/editRole.js', 'public/js/admin/roles');
mix.sass('resources/sass/admin/roles/editRole.scss', 'public/css/admin/roles');
mix.js('resources/js/admin/roles/listRoles.js', 'public/js/admin/roles');
mix.sass('resources/sass/admin/roles/listRoles.scss', 'public/css/admin/roles');
mix.js('resources/js/admin/roles/viewRole.js', 'public/js/admin/roles');
mix.sass('resources/sass/admin/roles/viewRole.scss', 'public/css/admin/roles');

/**
 * Settings
 */
mix.js('resources/js/admin/settings/branding.js', 'public/js/admin/settings');
mix.sass('resources/sass/admin/settings/branding.scss', 'public/css/admin/settings');
mix.js('resources/js/admin/settings/configuration.js', 'public/js/admin/settings');
mix.sass('resources/sass/admin/settings/configuration.scss', 'public/css/admin/settings');
mix.js('resources/js/admin/settings/socialSettings.js', 'public/js/admin/settings');
mix.sass('resources/sass/admin/settings/socialSettings.scss', 'public/css/admin/settings');

/**
 * Users
 */
mix.js('resources/js/admin/users/addUser.js', 'public/js/admin/users');
mix.sass('resources/sass/admin/users/addUser.scss', 'public/css/admin/users');
mix.js('resources/js/admin/users/changeUserPassword.js', 'public/js/admin/users');
mix.sass('resources/sass/admin/users/changeUserPassword.scss', 'public/css/admin/users');
mix.js('resources/js/admin/users/editUser.js', 'public/js/admin/users');
mix.sass('resources/sass/admin/users/editUser.scss', 'public/css/admin/users');
mix.js('resources/js/admin/users/listUser.js', 'public/js/admin/users');
mix.sass('resources/sass/admin/users/listUser.scss', 'public/css/admin/users');


/*
 * Order
 */
mix.js('resources/js/admin/order/addOrder.js', 'public/js/admin/order');
mix.sass('resources/sass/admin/order/addOrder.scss', 'public/css/admin/order');
// mix.js('resources/js/admin/order/editOrder.js', 'public/js/admin/order');
mix.sass('resources/sass/admin/order/editOrder.scss', 'public/css/admin/order');
mix.js('resources/js/admin/order/listOrder.js', 'public/js/admin/order');
mix.sass('resources/sass/admin/order/listOrder.scss', 'public/css/admin/order');
mix.js('resources/js/admin/order/viewOrder.js', 'public/js/admin/order');
mix.sass('resources/sass/admin/order/viewOrder.scss', 'public/css/admin/order');
mix.js('resources/js/admin/order/invoice.js', 'public/js/admin/order');
mix.sass('resources/sass/admin/order/invoice.scss', 'public/css/admin/order');
mix.js('resources/js/admin/order/shipment.js', 'public/js/admin/order');
mix.sass('resources/sass/admin/order/shipment.scss', 'public/css/admin/order');

/*
 * Order Returns
 */
mix.js('resources/js/admin/orderReturn/addOrderReturn.js', 'public/js/admin/orderReturn');
mix.sass('resources/sass/admin/orderReturn/addOrderReturn.scss', 'public/css/admin/orderReturn');
mix.js('resources/js/admin/orderReturn/editOrderReturn.js', 'public/js/admin/orderReturn');
mix.sass('resources/sass/admin/orderReturn/editOrderReturn.scss', 'public/css/admin/orderReturn');
mix.js('resources/js/admin/orderReturn/listOrderReturn.js', 'public/js/admin/orderReturn');
mix.sass('resources/sass/admin/orderReturn/listOrderReturn.scss', 'public/css/admin/orderReturn');

/**
 * Attribute
 */
mix.js('resources/js/admin/attribute/listAttribute.js', 'public/js/admin/attribute');
mix.sass('resources/sass/admin/attribute/listAttribute.scss', 'public/css/admin/attribute');
mix.js('resources/js/admin/attribute/addAttribute.js', 'public/js/admin/attribute');
mix.sass('resources/sass/admin/attribute/addAttribute.scss', 'public/css/admin/attribute');
mix.js('resources/js/admin/attribute/editAttribute.js', 'public/js/admin/attribute');
mix.sass('resources/sass/admin/attribute/editAttribute.scss', 'public/css/admin/attribute');

/**
 * Attribute Set
 */
mix.js('resources/js/admin/attributeSet/listAttributeSet.js', 'public/js/admin/attributeSet');
mix.sass('resources/sass/admin/attributeSet/listAttributeSet.scss', 'public/css/admin/attributeSet');
mix.js('resources/js/admin/attributeSet/addAttributeSet.js', 'public/js/admin/attributeSet');
mix.sass('resources/sass/admin/attributeSet/addAttributeSet.scss', 'public/css/admin/attributeSet');
mix.js('resources/js/admin/attributeSet/editAttributeSet.js', 'public/js/admin/attributeSet');
mix.sass('resources/sass/admin/attributeSet/editAttributeSet.scss', 'public/css/admin/attributeSet');

/**
 * Category
 */
mix.js('resources/js/admin/category/addCategory.js', 'public/js/admin/category');
mix.sass('resources/sass/admin/category/addCategory.scss', 'public/css/admin/category');
mix.js('resources/js/admin/category/editCategory.js', 'public/js/admin/category');
mix.sass('resources/sass/admin/category/editCategory.scss', 'public/css/admin/category');
mix.js('resources/js/admin/category/listCategory.js', 'public/js/admin/category');
mix.sass('resources/sass/admin/category/listCategory.scss', 'public/css/admin/category');


/**
 * Products
 */
mix.js('resources/js/admin/products/addProducts.js', 'public/js/admin/products');
mix.sass('resources/sass/admin/products/addProducts.scss', 'public/css/admin/products');
mix.js('resources/js/admin/products/viewProducts.js', 'public/js/admin/products');
mix.js('resources/js/admin/products/editProducts.js', 'public/js/admin/products');
mix.sass('resources/sass/admin/products/editProducts.scss', 'public/css/admin/products');
mix.js('resources/js/admin/products/listProducts.js', 'public/js/admin/products');
mix.sass('resources/sass/admin/products/listProducts.scss', 'public/css/admin/products');
mix.js('resources/js/admin/products/configurationForm.js', 'public/js/admin/products');

/**
 * Data Transfer
 */

mix.js('resources/js/admin/data/import.js', 'public/js/admin/data');
mix.js('resources/js/admin/data/export.js', 'public/js/admin/data');



mix.version();