<?php

if (!function_exists('sideMenu')) {
    function sideMenu($data = [])
    {
        $sideMenuList = [
            [
                'name' => __('Cms'),
                'home_menu' => true,
                'icon' => '<i class="ki-outline ki-element-8 fs-2"></i>',
                'permission' => ['contents_read', 'banner_read'],
                'active' => routeMatch([
                    'admin_contents_list', 'admin_contents_add', 'admin_contents_edit',
                    'admin_banner_list', 'admin_banner_add', 'admin_banner_edit',
                ]),
                'child' => [
                    [
                        'name' => __('Contents'),
                        'permission' => ['contents_read'],
                        'active' => routeMatch(['admin_contents_list', 'admin_contents_add', 'admin_contents_edit']),
                        'url' => route('admin_contents_list'),
                    ],
                    [
                        'name' => __('Banner'),
                        'permission' => ['banner_read'],
                        'active' => routeMatch(['admin_banner_list', 'admin_banner_add', 'admin_banner_edit']),
                        'url' => route('admin_banner_list'),
                    ],
                ],
            ],
            [
                'name' => __('Users Management'),
                'home_menu' => true,
                'icon' => '<i class="ki-outline ki-security-user fs-2"></i>',
                'permission' => ['role_read', 'user_read'],
                'active' => routeMatch(['admin_role_list', 'admin_role_add', 'admin_role_edit', 'admin_role_view', 'admin_user_list', 'admin_user_add', 'admin_user_edit', 'admin_user_view','admin_customer_list', 'admin_customer_add', 'admin_customer_edit', 'admin_customer_view']),
                'child' => [
                    [
                        'name' => __('Users'),
                        'permission' => ['user_read'],
                        'active' => routeMatch(['admin_user_list', 'admin_user_add', 'admin_user_edit', 'admin_user_view']),
                        'url' => route('admin_user_list'),
                    ],
                    [
                        'name' => __('Mobile Users'),
                        'permission' => ['customer_read'],
                        'active' => routeMatch(['admin_customer_list', 'admin_customer_add', 'admin_customer_edit', 'admin_customer_view']),
                        'url' => route('admin_customer_list'),
                    ],
                    //  [
                    //     'name' => __('Roles'),
                    //     'permission' => ['role_read'],
                    //     'active' => routeMatch(['admin_role_list', 'admin_role_add', 'admin_role_edit']),
                    //     'url' => route('admin_role_list'),
                    // ],
                ],
            ],
            [
                'name' => __('Orders'),
                'home_menu' => true,
                'icon' => '<i class="fonticon-bank"></i>',
                'permission' => ['order_read'],
                'active' => routeMatch([
                    'admin_order_list', 'admin_order_add', 'admin_order_edit',
                    'admin_order_return_list', 'admin_order_return_add', 'admin_order_return_edit',
                ]),
                'child' => [
                    [
                        'name' => __('All Orders'),
                        'permission' => ['order_read'],
                        'active' => routeMatch(['admin_order_list', 'admin_order_add', 'admin_order_edit']),
                        'url' => route('admin_order_list'),
                    ],
                    [
                        'name' => __('Order Returns'),
                        'permission' => ['order_return_read'],
                        'active' => routeMatch(['admin_order_return_list', 'admin_order_return_add', 'admin_order_return_edit']),
                        'url' => route('admin_order_return_list'),
                    ],
                ],
            ],
            [
                'name' => __('Catalog'),
                'home_menu' => true,
                'icon' => '<i class="fonticon-globe"></i>',
                'permission' => ['categories_read', 'products_read'],
                'active' => routeMatch([
                    'admin_categories_list', 'admin_categories_add', 'admin_categories_edit',
                    'admin_products_list', 'admin_products_add', 'admin_products_edit',
                    'admin_attribute_list', 'admin_attribute_add', 'admin_attribute_edit',
                    'admin_attribute_set_list', 'admin_attribute_set_add', 'admin_attribute_set_edit'
                ]),
                'child' => [
                    [
                        'name' => __('Categories'),
                        'permission' => ['categories_read'],
                        'active' => routeMatch(['admin_categories_list', 'admin_categories_add', 'admin_categories_edit']),
                        'url' => route('admin_categories_list'),
                    ],
                    [
                        'name' => __('Product Attributes'),
                        'permission' => ['attribute_read'],
                        'active' => routeMatch(['admin_attribute_list', 'admin_attribute_add', 'admin_attribute_edit']),
                        'url' => route('admin_attribute_list'),
                    ], [
                        'name' => __('Attribute Sets'),
                        'permission' => ['attribute_set_read'],
                        'active' => routeMatch(['admin_attribute_set_list', 'admin_attribute_set_add', 'admin_attribute_set_edit']),
                        'url' => route('admin_attribute_set_list'),
                    ],
                    // [
                    //     'name' => __('Applications'),
                    //     'permission' => ['application_read'],
                    //     'active' => routeMatch(['admin_application_list', 'admin_application_add', 'admin_application_edit']),
                    //     'url' => route('admin_application_list'),
                    // ],
                    [
                        'name' => __('Products'),
                        'permission' => ['products_read'],
                        'active' => routeMatch(['admin_products_list', 'admin_products_add', 'admin_products_edit']),
                        'url' => route('admin_products_list'),
                    ],
                ],
            ],
           
        ];
        $user = auth()->guard('admin')->user();
        $sideMenu = renderList($sideMenuList, $user);

        return $sideMenu['view'];
    }

    function renderList($sideMenuList, $user)
    {
        $sideMenuView = '';
        $sideMenuActive = false;

        foreach ($sideMenuList as $sideMenu) {
            $menuRoles = isset($sideMenu['role']) ? $sideMenu['role'] : [];
            $menuPermissions = isset($sideMenu['permission']) ? $sideMenu['permission'] : [];
            $menuUrl = isset($sideMenu['url']) ? $sideMenu['url'] : '#';
            $menuIcon = isset($sideMenu['icon']) ? $sideMenu['icon'] : '';
            $menuName = isset($sideMenu['name']) ? $sideMenu['name'] : '';
            $menuActive = isset($sideMenu['active']) ? $sideMenu['active'] : false;

            $sideMenuActive = $sideMenuActive || $menuActive;

            $userCan = true;

            if (!empty($menuPermissions)) {
                $userCan = false;

                foreach ($menuPermissions as $permission) {
                    if ($user->can($permission)) {
                        $userCan = true;

                        break;
                    }
                }
            }

            if (!empty($menuRoles)) {
                $roleAccess = $user->hasAnyRole($menuRoles);
                $userCan = (!empty($menuPermissions)) ? ($userCan = $userCan || $roleAccess) : $roleAccess;
            }

            if ($userCan) {
                if (isset($sideMenu['child'])) {
                    $subMenu = renderList($sideMenu['child'], $user);

                    if (!empty($subMenu['view'])) {
                        $sideMenuView .= '<div data-kt-menu-trigger="click" class="menu-item ' . ($menuActive ? 'here show' : '') . ' menu-accordion">
                                    <span class="menu-link">
										<span class="menu-icon">' . $menuIcon . '</span>
                                        <span class="menu-title">' . $menuName . '</span>
                                        <span class="menu-arrow"></span>
                                    </span>
                                    <div class="menu-sub menu-sub-accordion">
                                    ' . $subMenu['view'] . '
                                    </div>
                                </div>';
                    }
                } else {
                    $sideMenuView .= '<div class="menu-item">
                                <a class="menu-link ' . ($menuActive ? 'active' : '') . '" href="' . $menuUrl . '">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">' . $menuName . '</span>
                                </a>
                            </div>';
                }
            }
        }

        return ['view' => $sideMenuView, 'active' => $sideMenuActive];
    }
}