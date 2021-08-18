<?php

namespace Medianova\LaravelPayment\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

use Medianova\LaravelPayment\Interfaces\PaymentInterface;
use Medianova\LaravelPayment\Exceptions\LaravelPaymentException;

class VakifbankProvider implements PaymentInterface
{

    public $access_token;
    public $company_id;
    public $username;
    public $password;
    public $base_url;

    protected $dataService;
    protected $type;
    protected $response;

    protected $http_client;

    /**
     * VakifbankProvider constructor.
     * @throws LaravelPaymentException
     */
    public function __construct()
    {

        // Variables
        $this->base_url = config('payment.vakifbank.base_url');
        $this->username = config('payment.vakifbank.username');
        $this->password = config('payment.vakifbank.password');
        $this->company_id = config('payment.vakifbank.company_id');

        // Client
        $this->http_client = new Client(['base_uri' => $this->base_url]);

        // Update Token
        $access_token = Cache::get('payment-vakifbank-api-token', null);
        if (empty($access_token)) {
            $this->access_token = $this->login();
        } else {
            $this->access_token = $access_token;
        }

    }

    /**
     * @return mixed
     */
    public function login()
    {
        return Cache::remember('payment-vakifbank-api-token', 3500, function () {
            $this->response = $this->http_client->request('POST', $this->base_url . '/' . 'token', [
                'form_params' => [
                    'grant_type' => 'password',
                    'username' => $this->username,
                    'password' => $this->password,
                ]
            ]);

            $body = (array)json_decode($this->response->getBody());
            return $body['access_token'];
        });
    }


    /**
     * @param array $data 
     * @return Mixed
     */
    public function charge($data = [])
    {
        return $this;
    }
    
}
