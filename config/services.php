<?php
return [
    'google'=>['client_id'=>env('GOOGLE_CLIENT_ID'),'client_secret'=>env('GOOGLE_CLIENT_SECRET'),'redirect'=>env('GOOGLE_REDIRECT_URI')],
    'sslcommerz'=>['enabled'=>(bool)env('SSLCOMMERZ_ENABLED',false),'sandbox'=>(bool)env('SSLCOMMERZ_SANDBOX',true),'store_id'=>env('SSLCOMMERZ_STORE_ID'),'store_password'=>env('SSLCOMMERZ_STORE_PASSWORD')],
];
