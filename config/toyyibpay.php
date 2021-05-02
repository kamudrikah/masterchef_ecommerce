<?php

return [
    'endpoint' => env('TOYYIBPAY_API', 'https://toyyibpay.com'),
    'secret_key' => env('TOYYIBPAY_SECRET', ''),
    'products' => [
        [
            'category_id' => '8v75b2yr',
            'name' => 'Sandbox Product 1',
            'description' => 'Sandbox Product 1 description',
            'price' => '10000',
        ],
        [
            'category_id' => 'mflyd1i3',
            'name' => 'MasterChef Product 1',
            'description' => 'Product 1 description',
            'price' => '10000',
        ]
    ],
];
