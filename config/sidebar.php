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
        'active_pattern' => 'products.*',
    ],
    [
        'label' => 'Customers',
        'route' => 'customers.index',
        'icon' => 'person',
        'active_pattern' => 'customers.*',
    ],
    [
        'label' => 'Stock Movements',
        'route' => 'stock-movements.index',
        'icon' => 'inventory',
        'active_pattern' => 'stock-movements.*',
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
