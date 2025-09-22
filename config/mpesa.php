<?php

return [
    // M-Pesa API Configuration
    'api_key' => env('MPESA_API_KEY', 'xV73zV4BKIoD7MLEXvxor7uT3BiICARD'),
    'public_key' => env('MPESA_PUBLIC_KEY', 'MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEArv9yxA69XQKBo24BaF/D+fvlqmGdYjqLQ5WtNBb5tquqGvAvG3WMFETVUSow/LizQalxj2ElMVrUmzu5mGGkxK08bWEXF7a1DEvtVJs6nppIlFJc2SnrU14AOrIrB28ogm58JjAl5BOQawOXD5dfSk7MaAA82pVHoIqEu0FxA8BOKU+RGTihRU+ptw1j4bsAJYiPbSX6i71gfPvwHPYamM0bfI4CmlsUUR3KvCG24rB6FNPcRBhM3jDuv8ae2kC33w9hEq8qNB55uw51vK7hyXoAa+U7IqP1y6nBdlN25gkxEA8yrsl1678cspeXr+3ciRyqoRgj9RD/ONbJhhxFvt1cLBh+qwK2eqISfBb06eRnNeC71oBokDm3zyCnkOtMDGl7IvnMfZfEPFCfg5QgJVk1msPpRvQxmEsrX9MQRyFVzgy2CWNIb7c+jPapyrNwoUbANlN8adU1m6yOuoX7F49x+OjiG2se0EJ6nafeKUXw/+hiJZvELUYgzKUtMAZVTNZfT8jjb58j8GVtuS+6TM2AutbejaCV84ZK58E2CRJqhmjQibEUO6KPdD7oTlEkFy52Y1uOOBXgYpqMzufNPmfdqqqSM4dU70PO8ogyKGiLAIxCetMjjm6FCMEA3Kc8K0Ig7/XtFm9By6VxTJK1Mg36TlHaZKP6VzVLXMtesJECAwEAAQ=='),
    
    // API Endpoints
    'base_url' => env('MPESA_BASE_URL', 'openapi.m-pesa.com'),
    'session_path' => env('MPESA_SESSION_PATH', '/sandbox/ipg/v2/vodacomLES/getSession/'),
    'c2b_payment_path' => env('MPESA_C2B_PAYMENT_PATH', '/sandbox/ipg/v2/vodacomLES/c2bPayment/singleStage/'),
    
    // Service Provider Configuration
    'service_provider_code' => env('MPESA_SERVICE_PROVIDER_CODE', '000000'),
    'country' => env('MPESA_COUNTRY', 'LES'),
    'currency' => env('MPESA_CURRENCY', 'LSL'),
    
    // Request Configuration
    'timeout' => env('MPESA_TIMEOUT', 300),
    'session_cache_duration' => env('MPESA_SESSION_CACHE_DURATION', 1800), // 30 minutes
    
    // Payment Configuration
    'min_amount' => env('MPESA_MIN_AMOUNT', 1),
    'max_amount' => env('MPESA_MAX_AMOUNT', 50000),
    
    // Callback URLs
    'callback_url' => env('MPESA_CALLBACK_URL', env('APP_URL', 'http://localhost') . '/api/mpesa/callback'),
    'result_url' => env('MPESA_RESULT_URL', env('APP_URL', 'http://localhost') . '/api/mpesa/result'),
    
    // Environment
    'environment' => env('MPESA_ENVIRONMENT', 'sandbox'), // sandbox or production
    
    // Logging
    'log_requests' => env('MPESA_LOG_REQUESTS', true),
    'log_responses' => env('MPESA_LOG_RESPONSES', true),
];
