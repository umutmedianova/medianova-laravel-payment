<?php

namespace Medianova\LaravelPayment\Test;

use Medianova\LaravelPayment\Facades\Payment;

class QuickbooksTest extends TestCase
{
    /**
     * Create Customer
     * @return void
     */
    public function testCharge()
    {
        $response = Payment::provider('quickbooks')->charge([
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

        $res = (array) json_decode($response);
        $this->assertEquals(400, $res['code']);

    }
}
