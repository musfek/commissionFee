<?php


namespace app\models\CommissionFee;


interface WithdrawInterface
{
    public function calculate($amount, $userId, $userType, $currency, $date, $currencies);
}