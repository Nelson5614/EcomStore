<?php

return [
    // M-Pesa API Configuration
    'api_key' => env('MPESA_API_KEY'),
    'public_key' => env('MPESA_PUBLIC_KEY'),
    
    // API Endpoints
    'base_url' => env('MPESA_BASE_URL', 'openapi.m-pesa.com'),
    'session_path' => env('MPESA_SESSION_PATH', '/sandbox/ipg/v2/vodacomLES/getSession/'),
    'c2b_payment_path' => env('MPESA_C2B_PAYMENT_PATH', '/sandbox/ipg/v2/vodacomLES/c2bPayment/singleStage/'),
    
    // Service Provider Configuration
    'service_provider_code' => env('MPESA_SERVICE_PROVIDER_CODE'),
    'country' => env('MPESA_COUNTRY', 'LES'),
    'currency' => env('MPESA_CURRENCY', 'LSL'),
    
    // Request Configuration
    'timeout' => env('MPESA_TIMEOUT', 300),
    'session_cache_duration' => env('MPESA_SESSION_CACHE_DURATION', 1800), // 30 minutes
    
    // Payment Configuration
    'min_amount' => env('MPESA_MIN_AMOUNT', 1),
    'max_amount' => env('MPESA_MAX_AMOUNT', 50000),
    
    // Callback URLs
    'callback_url' => env('MPESA_CALLBACK_URL', env('APP_URL', 'http://localhost') . '/api/v1/mpesa/callback'),
    'result_url' => env('MPESA_RESULT_URL', env('APP_URL', 'http://localhost') . '/api/v1/mpesa/result'),
    
    // Environment
    'environment' => env('MPESA_ENVIRONMENT', 'sandbox'), // sandbox or production
    
    // Logging
    'log_requests' => env('MPESA_LOG_REQUESTS', true),
    'log_responses' => env('MPESA_LOG_RESPONSES', true),
];
