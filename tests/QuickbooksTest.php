<?php

namespace Medianova\LaravelPayment\Test;

use Medianova\LaravelPayment\Facades\Payment;

class QuickbooksTest extends TestCase
{
    /**
     * Create Customer
     * @return void
     */
    public function testCustomerCreate()
    {
        $response = Payment::provider('quickbooks')->customer([
            "BillAddr" => [
                "Line1" => "123 Main Street",
                "City" => "Mountain View",
                "Country" => "USA",
                "CountrySubDivisionCode" => "CA",
                "PostalCode" => "94042"
            ],
            "Notes" => "Here are other details.",
            "Title" => "Mr",
            "GivenName" => "Evil",
            "MiddleName" => "1B",
            "FamilyName" => "King",
            "Suffix" => "Jr",
            "FullyQualifiedName" => "Evil King",
            "CompanyName" => "King Evial",
            "DisplayName" => "Umut Cetinkaya",
            "PrimaryPhone" => [
                "FreeFormNumber" => "(555) 555-5555"
            ],
            "PrimaryEmailAddr" => [
                "Address" => "evilkingw@myemail.com"
            ]
        ])->create();

        $res = (array) json_decode($response);
        $this->assertEquals(400, $res['code']);

    }

    /**
     * Create Invoice
     * @return void
     */
    public
    function testInvoiceCreate()
    {
        $response = Payment::provider('quickbooks')->invoice([
            "Line" => [
                [
                    "Amount" => 100.00,
                    "DetailType" => "SalesItemLineDetail",
                    "SalesItemLineDetail" => [
                        "ItemRef" => [
                            "value" => 20,
                            "name" => "Hours"
                        ]
                    ]
                ]
            ],
            "CustomerRef" => [
                "value" => 59
            ],
            "BillEmail" => [
                "Address" => "Familiystore@intuit.com"
            ],
            "BillEmailCc" => [
                "Address" => "a@intuit.com"
            ],
            "BillEmailBcc" => [
                "Address" => "v@intuit.com"
            ]
        ])->create();
        
        $res = (array) json_decode($response);
        $this->assertEquals(400, $res['code']);

    }

}
