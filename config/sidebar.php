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
    ],
    [
        'label' => 'Products',
        'route' => 'products.index',
        'icon' => 'shopping_cart',
        'active_pattern' => ['products.index', 'products.create', 'products.store', 'products.show', 'products.edit', 'products.update', 'products.destroy'],
    ],
    [
        'label' => 'Stock Movements',
        'route' => 'stock-movements.index',
        'icon' => 'inventory',
        'active_pattern' => 'stock-movements.*',
    ],
    [
        'label' => 'Low Stock Alerts',
        'route' => 'products.low-stock',
        'icon' => 'warning',
        'active_pattern' => 'products.low-stock',
    ],
    [
        'label' => 'Transactions',
        'route' => 'transactions.index',
        'icon' => 'receipt',
        'active_pattern' => 'transactions.*',
    ],
    [
        'label' => 'Transaction Items',
        'route' => 'transaction-items.index',
        'icon' => 'receipt_long',
        'active_pattern' => 'transaction-items.*',
    ],
];
