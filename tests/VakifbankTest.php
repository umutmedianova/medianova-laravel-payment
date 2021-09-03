<?php

namespace Medianova\LaravelPayment\Test;

use Medianova\LaravelPayment\Facades\Payment;

class VakifbankTest extends TestCase
{
    /**
     * Charge
     * @return void
     */
    public function testCharge()
    {
        $response = Payment::provider('vakifbank')->charge([
            'CurrencyAmount' => '1.00',
            'CurrencyCode' => '949',
            'Pan' => '4938460158754205',
            'Expiry' => '202411',
            'Cvv' => '715',
            'ClientIp' => '212.2.199.55'
        ]);

        $res = (array)json_decode($response);
        $this->assertEquals(400, $res['code']);

    }

}
