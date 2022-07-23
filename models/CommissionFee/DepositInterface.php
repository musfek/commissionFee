<?php


namespace app\models\CommissionFee;


interface DepositInterface
{
    public function calculate($amount);
}