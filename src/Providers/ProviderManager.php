<?php

namespace Medianova\LaravelPayment\Providers;

use Medianova\LaravelPayment\Interfaces\PaymentInterface;
use Medianova\LaravelPayment\Exceptions\LaravelPaymentException;
use Medianova\LaravelPayment\Exceptions\LaravelPaymentProviderException;

class ProviderManager
{

    /**
     * @var
     */
    protected $id;

    /**
     * @var
     */
    protected $type;

    /**
     * @var
     */
    protected $data;

    /**
     * @var
     */
    protected $provider;

    /**
     * ProviderManager constructor.
     * @throws LaravelPaymentException
     */
    public function __construct()
    {
        $this->provider(config('payment.provider'));
    }

    /**
     * @param $class_name
     */
    public function __autoload($class_name)
    {
        include_once($class_name . ".php");
    }

    /**
     * Load Payment Provider
     *
     * @param String $provider
     * @return Mixed
     */
    public function provider($provider)
    {
        if ($provider == null) {
            return false;
        } else {

            $class_name = ucfirst($provider) . 'Provider';

            $file = dirname(__FILE__) . '/' . $class_name . ".php";
            if (!file_exists($file)) {
                throw new LaravelPaymentProviderException("We could not found Provider  : {$file}");
            }
            $provider = resolve("Medianova\\LaravelPayment\\Providers\\" . $class_name);
            $this->provider = $provider;

            if (!$this->provider instanceof PaymentInterface) {
                throw new LaravelPaymentProviderException("Provider must implement on LaravelPaymentInterface");
            }
            return $this;
        }
    }

    /**
     * Charge function
     *
     * @param $data
     * @return mixed
     * @throws LaravelPaymentException
     */
    public function charge($data = null)
    {

        if ($data == null) {
            return false;
        } else {

            try {
                return $this->provider->charge($data);
            } catch (LaravelPaymentProviderException $e) {
                throw new LaravelPaymentProviderException("Charge Error!");
            }
        }
    }

}
