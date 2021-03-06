<?php

return [
    'provider'=>env('PAYMENT_PROVIDER', 'quickbooks'),
    'vakifbank'=>[
        'base_url' => env('PAYMENT_BASE_URL', 'https://onlineodemetest.vakifbank.com.tr:4443/VposService/v3/Vposreq.aspx'),
        'company_id' =>  env('PAYMENT_NUMBER', '000000000000000'),
        'pos_number' => env('PAYMENT_POS_NUMBER', '00000000'),
        'password' => env('PAYMENT_PASSWORD', '00000000'),
    ],
    'quickbooks'=>[
        'access_token' => env('PAYMENT_ACCESS_TOKEN', 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'),
        'refresh_token' => env('PAYMENT_REFRESH_TOKEN', 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'),
        'real_me_id' => env('PAYMENT_REAL_ME_ID', 'XXXXXXXXXXXXXXXXXXXX'),
        'client_id' => env('PAYMENT_CLIENT_ID', 'XXXXXXXXXXXXX'),
        'client_secret' => env('PAYMENT_CLIENT_SECRET', 'XXXXXXXXXXXXX'),
        'redirect_url' => env('PAYMENT_REDIRECT_URI', 'https://developer.intuit.com/v2/OAuth2Playground/RedirectUrl'),
        'scope' => env('PAYMENT_OAUTH_SCOPE', 'com.intuit.quickbooks.accounting, openID, profile, phone, address'),
        'base_url' =>  env('PAYMENT_QUICKBOOKS_BASE_URL', 'development'),
    ],
];
