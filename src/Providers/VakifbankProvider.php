<?php

namespace Medianova\LaravelPayment\Providers;

use Medianova\LaravelPayment\Interfaces\PaymentInterface;
use Medianova\LaravelPayment\Exceptions\LaravelPaymentException;

class VakifbankProvider implements PaymentInterface
{

    public $base_url;
    public $company_id;
    public $pos_number;
    public $password;

    /**
     * @param array $data
     * @return Mixed
     * @throws LaravelPaymentException
     */
    public function charge($data = [])
    {

        // Variables
        $this->base_url = config('payment.vakifbank.base_url');
        $this->company_id = config('payment.vakifbank.company_id');
        $this->password = config('payment.vakifbank.password');
        $this->pos_number = config('payment.vakifbank.pos_number');

        //Data
        $TransactionType = $data['TransactionType'] || 'Sale';
        $TransactionId = $data['TransactionId'] || 'SIP_'.time();
        $CurrencyAmount = $data['CurrencyAmount'] || null;
        $CurrencyCode = $data['CurrencyCode'] || '949';
        $Pan = $data['Pan'] || null;
        $Expiry = $data['Expiry'] || null;
        $Cvv = $data['Cvv'] || null;
        $ClientIp = $data['ClientIp'] || null;

        $PosXML = 'prmstr=<VposRequest><MerchantId>' . $this->company_id . '</MerchantId>';
        $PosXML = $PosXML . '<Password>' . $this->password . '</Password>';
        $PosXML = $PosXML . '<TerminalNo>' . $this->pos_number . '</TerminalNo>';
        if ($TransactionType != null) {
            $PosXML = $PosXML . '<TransactionType>' . $TransactionType . '</TransactionType>';
        }
        if ($TransactionId != null) {
            $PosXML = $PosXML . '<TransactionId>' . $TransactionId . '</TransactionId>';
        }
        if ($CurrencyAmount != null) {
            $PosXML = $PosXML . '<CurrencyAmount>' . $CurrencyAmount . '</CurrencyAmount>';
        }
        if ($CurrencyCode != null) {
            $PosXML = $PosXML . '<CurrencyCode>' . $CurrencyCode . '</CurrencyCode>';
        }
        if ($Pan != null) {
            $PosXML = $PosXML . '<Pan>' . $Pan . '</Pan>';
        }
        if ($Expiry != null) {
            $PosXML = $PosXML . '<Expiry>' . $Expiry . '</Expiry>';
        }
        if ($Cvv != null) {
            $PosXML = $PosXML . '<Cvv>' . $Cvv . '</Cvv>';
        }
        $PosXML = $PosXML . '<TransactionDeviceSource>0</TransactionDeviceSource>';
        if ($ClientIp != null) {
            $PosXML = $PosXML . '<ClientIp>' . $ClientIp . '</ClientIp>';
        }
        $PosXML = $PosXML . '</VposRequest>';

        try {

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->base_url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $PosXML);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, 59);
            curl_setopt($ch, CURLOPT_SSLVERSION, "CURL_SSLVERSION_TLSv1_1");
            $response = curl_exec($ch);
            curl_close($ch);
            return $response;

        } catch (\Exception $e) {
            throw new LaravelPaymentException($e->getMessage(), 1);
        }

    }

}
