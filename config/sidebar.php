<?php

return [
    [
        'label' => 'Dashboard',
        'route' => 'dashboard',
        'icon' => 'dashboard',
        'active_pattern' => 'dashboard',
    ],
    [
        'label' => 'Checkout',
        'route' => 'checkout.index',
        'icon' => 'point_of_sale',
        'active_pattern' => 'checkout.*',
    ],
    [
        'label' => 'Categories',
        'route' => 'categories.index',
        'icon' => 'category',
        'active_pattern' => 'categories.*',
        'manager_only' => true,
    ],
    [
        'label' => 'Products',
        'route' => 'products.index',
        'icon' => 'shopping_cart',
        'active_pattern' => ['products.index', 'products.create', 'products.store', 'products.show', 'products.edit', 'products.update', 'products.destroy'],
        'manager_only' => true,
    ],
    [
        'label' => 'Stock Movements',
        'route' => 'stock-movements.index',
        'icon' => 'inventory',
        'active_pattern' => 'stock-movements.*',
        'manager_only' => true,
    ],
    [
        'label' => 'Low Stock Alerts',
        'route' => 'products.low-stock',
        'icon' => 'warning',
        'active_pattern' => 'products.low-stock',
        'manager_only' => true,
    ],
    [
        'label' => 'Transactions',
        'route' => 'transactions.index',
        'icon' => 'receipt',
        'active_pattern' => 'transactions.*',
        'manager_only' => true,
    ],
    [
        'label' => 'Sales Report',
        'route' => 'reports.sales',
        'icon' => 'bar_chart',
        'active_pattern' => 'reports.*',
        'manager_only' => true,
    ],
    [
        'label' => 'Users',
        'route' => 'users.index',
        'icon' => 'group',
        'active_pattern' => 'users.*',
        'admin_only' => true,
    ],
];
