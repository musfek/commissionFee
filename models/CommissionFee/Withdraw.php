<?php

namespace app\models\CommissionFee;


class Withdraw implements WithdrawInterface
{
    const BUSINESS_USER_FEE_IN_PERCENT = 0.5;
    const PRIVATE_USER_FEE_IN_PERCENT = 0.3;
    const PRIVATE_USER_MAX_FREE_AMOUNT_LIMIT_PER_WEEK = 1000;
    const PRIVATE_USER_MAX_FREE_OPERATION_LIMIT_PER_WEEK = 3;

    public static array $userWiseArray = [];

    public function calculate($amount, $userId, $userType, $currency, $date, $currencies)
    {
        if ($userType == CommissionFee::USER_TYPE_BUSINESS) {
            return self::calculateBusinessUserFee($amount);
        } else if ($userType == CommissionFee::USER_TYPE_PRIVATE) {
            return self::calculatePrivateUserFee($amount, $userId, $currency, $date, $currencies);
        }
        return 0;
    }

    private function calculateBusinessUserFee($amount)
    {
        return $amount * self::BUSINESS_USER_FEE_IN_PERCENT / 100;
    }

    private function calculatePrivateUserFee($amount, $userId, $currency, $date, $currencies)
    {
        $currencyClass = new Currency();
        $amount = $currencyClass->calculate($currencies, $currency, $amount);
        $withdraw = new Withdraw();
        $userWiseArray = $withdraw->userWiseCalculation($amount, $userId, $date);
        $chargeAbleAmount = $withdraw->calculateExceededAmount($amount,$userWiseArray[$userId]["weekWiseITotalAmount"]);
        if ($userWiseArray[$userId]["weekWiseITotalAmount"] <= self::PRIVATE_USER_MAX_FREE_AMOUNT_LIMIT_PER_WEEK && $userWiseArray[$userId]["operation"] <= self::PRIVATE_USER_MAX_FREE_OPERATION_LIMIT_PER_WEEK) {
            return 0;
        }
        return $chargeAbleAmount * self::PRIVATE_USER_FEE_IN_PERCENT / 100;
    }

    private function calculateExceededAmount($amount,$weekWiseITotalAmount)
    {
        if ($amount >= self::PRIVATE_USER_MAX_FREE_AMOUNT_LIMIT_PER_WEEK) {
            $amount = $weekWiseITotalAmount - self::PRIVATE_USER_MAX_FREE_AMOUNT_LIMIT_PER_WEEK;
        }
        return $amount;
    }

    private function dateToWeek($date)
    {
        $dateIntoTime = strtotime($date);
        return date("W", $dateIntoTime);
    }

    private function userWiseCalculation($amount, $userId, $date)
    {
        $week = self::dateToWeek($date);
        if (isset(self::$userWiseArray[$userId])) {
            if (self::$userWiseArray[$userId]["week"] == $week && self::dateDiff(self::$userWiseArray[$userId]["date"], $date) <= 6) {
                self::$userWiseArray[$userId]["weekWiseITotalAmount"] += $amount;
                self::$userWiseArray[$userId]["operation"] += 1;
            } else {
                self::$userWiseArray[$userId]["weekWiseITotalAmount"] = $amount;
                self::$userWiseArray[$userId]["operation"] = 1;
            }
            self::$userWiseArray[$userId]["date"] = $date;
            self::$userWiseArray[$userId]["week"] = $week;

        } else {
            self::$userWiseArray[$userId] = ['date' => $date, 'week' => $week, 'weekWiseITotalAmount' => $amount, 'operation' => 1];
        }
        return self::$userWiseArray;
    }

    private function dateDiff($firstDate, $secondDate)
    {
        return round((strtotime($secondDate) - strtotime($firstDate)) / (60 * 60 * 24));
    }


}