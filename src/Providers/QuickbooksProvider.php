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
        $this->auth_mode = config('payment.quickbooks.auth_mode');
        $this->client_id = config('payment.quickbooks.client_id');
        $this->client_secret = config('payment.quickbooks.client_secret');
        $this->access_token = config('payment.quickbooks.access_token');
        $this->refresh_token = config('payment.quickbooks.refresh_token');
        $this->real_me_id = config('payment.quickbooks.real_me_id');
        $this->username = config('payment.quickbooks.username');
        $this->password = config('payment.quickbooks.password');
        $this->redirect_url = config('payment.quickbooks.redirect_url');
        $this->scope = config('payment.quickbooks.scope');
        $this->base_url = config('payment.quickbooks.base_url');

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
            'baseUrl' => "development"
        );

        // Update Token
        $access_token = Cache::get('payment-api-token', null);
        if (!empty($access_token)) {
            $this->access_token = $access_token;
            $this->dataService = DataService::Configure($options);
        } else {
            $this->dataService = DataService::Configure($options);
            $this->access_token = $this->login();
        }

        if ($this->access_token != null) {
            $this->dataService->updateOAuth2Token($this->access_token);
        } else {
            throw new LaravelPaymentException("Access token must not null");
        }

    }


    /**
     * @return mixed
     */
    public function login()
    {
        return Cache::remember('payment-api-token', 3500, function () {
            $OAuth2LoginHelper = $this->dataService->getOAuth2LoginHelper();
            return $OAuth2LoginHelper->refreshToken();
        });
    }


    /**
     * @param array $data
     * @return Mixed
     */
    public function charge($data = [])
    {

        $client = new PaymentClient([
            'access_token' => $this->access_token,
            'environment' => $this->base_url
        ]);

        $charge = ChargeOperations::buildFrom($data);
        $response = $client->charge($charge);

        $res = [];

        if ($response->failed()) {
            $code = $response->getStatusCode();
            $errorMessage = $response->getBody();
            $res['code'] = $code;
            $res['errorMessage'] = $errorMessage;
        } else {
            $responseCharge = $response->getBody();
            $id = $responseCharge->id;
            $status = $responseCharge->status;
            $res['id'] = $id;
            $res['status'] = $status;
        }

        return $res;
    }

}
