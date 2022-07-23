<?php


namespace app\models\CommissionFree;


class Deposit
{
    const FEE_IN_PERCENT = '0.03';

    public function calculate($amount)
    {
        return $amount * self::FEE_IN_PERCENT / 100;
    }
}