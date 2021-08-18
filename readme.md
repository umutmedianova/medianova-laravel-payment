# Medianova Laravel Payment

### Support Libraries

- Quickbooks 
- Vakıfbank 

### Installation

You can install the package via composer:

```bash
composer require medianova/laravel-payment
```

configuration in `config/payment.php`

```php
return [
    'provider'=>env('PAYMENT_PROVIDER', 'quickbooks'),
    'vakifbank'=>[
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
        'base_url' =>  env('PAYMENT_BASE_URL', 'development'),
    ],
];
```

## Usage

```php
<?php

use Medianova\LaravelPayment\Facades\Payment;

Payment::charge([]);

```

## Or use by choosing a provider

### Charge 
```php
Payment::provider('quickbooks')->charge([
  "amount" => "10.55",
  "currency" => "USD",
  "card" => [
      "name" => "emulate=0",
      "number" => "4111111111111111",
      "address" => [
        "streetAddress" => "1130 Kifer Rd",
        "city" => "Sunnyvale",
        "region" => "CA",
        "country" => "US",
        "postalCode" => "94086"
      ],
      "expMonth" => "02",
      "expYear" => "2020",
      "cvc" => "123"
  ],
  "context" => [
    "mobile" => "false",
    "isEcommerce" => "true"
  ]
]);
```