<?php

namespace Medianova\LaravelPayment\Providers;

use Illuminate\Support\Facades\Cache;
use QuickBooksOnline\API\DataService\DataService;

use Medianova\LaravelPayment\Interfaces\PaymentInterface;
use Medianova\LaravelPayment\Exceptions\LaravelPaymentException;

use QuickBooksOnline\Payments\PaymentClient;
use QuickBooksOnline\Payments\Operations\ChargeOperations;

class QuickbooksProvider implements PaymentInterface
{

    public $auth_mode;
    public $client_id;
    public $client_secret;
    public $access_token;
    public $refresh_token;
    public $real_me_id;
    public $username;
    public $password;
    public $redirect_url;
    public $scope;
    public $base_url;

    protected $dataService;
    protected $data;
    protected $type;
    protected $response;

    /**
     * QuickbooksProvider constructor.
     * @throws LaravelPaymentException
     */
    public function __construct()
    {

        // Variables
        $this->auth_mode = config('payment.quickbooks.auth_mode') ?? null;
        $this->client_id = config('payment.quickbooks.client_id') ?? null;
        $this->client_secret = config('payment.quickbooks.client_secret') ?? null;
        $this->access_token = config('payment.quickbooks.access_token') ?? null;
        $this->refresh_token = config('payment.quickbooks.refresh_token') ?? null;
        $this->real_me_id = config('payment.quickbooks.real_me_id') ?? null;
        $this->username = config('payment.quickbooks.username') ?? null;
        $this->password = config('payment.quickbooks.password') ?? null;
        $this->redirect_url = config('payment.quickbooks.redirect_url') ?? null;
        $this->scope = config('payment.quickbooks.scope') ?? null;
        $this->base_url = config('payment.quickbooks.base_url') ?? null;

        // Options
        $options = array(
            'auth_mode' => 'oauth2',
            'ClientID' => $this->client_id,
            'ClientSecret' => $this->client_secret,
            'accessTokenKey' => $this->access_token,
            'refreshTokenKey' => $this->refresh_token,
            'QBORealmID' => $this->real_me_id,
            'RedirectURI' => $this->redirect_url,
            'scope' => $this->scope,
            'baseUrl' => 'development'
        );

        $this->dataService = DataService::Configure($options);
        $this->login = $this->login();

        if ($this->login != null) {
            $this->dataService->updateOAuth2Token($this->login);
            $this->access_token = $this->login->getAccessToken();
        } else {
            throw new LaravelPaymentException("Access token must not null");
        }

    }


    /**
     * @return mixed
     */
    public function login()
    {
        $OAuth2LoginHelper = $this->dataService->getOAuth2LoginHelper();
        return $OAuth2LoginHelper->refreshAccessTokenWithRefreshToken($this->refresh_token);
    }


    /**
     * @param array $data
     * @return Mixed
     */
    public function charge($data = [])
    {

        $client = new PaymentClient([
            'access_token' => $this->access_token,
            'environment' => 'development'
        ]);

        $charge = ChargeOperations::buildFrom($this->transformData($data));
        $response = $client->charge($charge);

        $res = [];

        if ($response->failed()) {
            $code = $response->getStatusCode();
            $errorMessage = $response->getBody();
            $res['status'] = 'FAILED';
            $res['code'] = $code;
            $res['message'] = $errorMessage;
        } else {
            $responseCharge = $response->getBody();
            $id = $responseCharge->id;
            $status = $responseCharge->status;
            $res['status'] = 'OK';
            $res['data'] = ['id' => $id, 'status' => $status];
        }

        return $res;
    }

    /**
     * @param $data
     * @return array
     */
    function transformData($data){

        $CardName = $data['CardName'] ?? null;
        $CurrencyAmount = $data['CurrencyAmount'] ?? null;
        $CurrencyCode = $data['CurrencyCode'] ?? null;
        $Pan = $data['Pan'] ?? null;
        $Expiry = $data['Expiry'] ?? null;
        $Cvv = $data['Cvv'] ?? null;
        $Ip = $data['$Ip'] ?? null;

        return [
            "amount" => $CurrencyAmount,
            "currency" => $CurrencyCode,
            "card" => [
                "name" => $CardName,
                "number" => $Pan,
                "expMonth" => (($Expiry != null) ? substr($Expiry,2,2) : null),
                "expYear" => (($Expiry != null) ? substr($Expiry,0,4) : null),
                "cvc" => $Cvv
            ],
            "context" => [
                "mobile" => "false",
                "isEcommerce" => "true"
            ]
        ];
    }

}
