<?php
namespace App\Libs\Pay\Tubes;

use App\Order;

abstract class BasePay
{
    protected $config;
    public $specificRules = [];

    public abstract function scanPay(Order $order, $specificParams);

    public abstract function barPay(Order $order, $specificParams);

    public abstract function jsPay(Order $order, $specificParams);
}