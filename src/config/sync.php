<?php

$esg_host = env('ESG_HOST', 'https://app.employeeapp.co.uk');

return [
    'tenant_id' => env('ESG_TENANT_ID'),
    'user' => [
        'username' => env('ESG_USERNAME'),
        'password' => env('ESG_PASSWORD'),
        'claim' => env("ESG_CLAIM", "ACCEPTED")
    ],
    'ep' => [
        'login' => $esg_host . "/api/v1/login",
        'export' => [
            'categories' => $esg_host . "/api/v1/categories",
            'pages' => $esg_host . "/api/v1/pages",
        ],
        'delete' => [
            'categories' => $esg_host . "/api/v1/categories",
            'pages' => $esg_host . "/api/v1/pages",
        ]
    ],
    'storage' => [
        'disk' => env('ESG_STORAGE_DISK', config('filesystem.default')),
        'categories_path' => env('ESG_CATEGORIES_PATH', 'categories'),
        'pages_path' => env('ESG_PAGES_PATH', 'pages'),
        'processed_path' => env('ESG_PROCESSED_PATH', 'processed'),
    ]
];