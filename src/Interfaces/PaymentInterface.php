<?php
namespace Medianova\LaravelPayment\Interfaces;

interface PaymentInterface
{
    public function charge($data);
}
