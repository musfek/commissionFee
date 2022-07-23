<?php

namespace app\models\CommissionFee;


class CommissionFee
{
    const USER_TYPE_PRIVATE = 'private';
    const USER_TYPE_BUSINESS = 'business';
    const OPERATION_TYPE_DEPOSIT = 'deposit';
    const OPERATION_TYPE_WITHDRAW = 'withdraw';

    public function calculate($operations, $currencies)
    {
        $commissionFees = [];
        foreach ($operations as $key => $operation) {
            $commissionFees[] = self::commissionFeeFormat(self::operation($operation, $currencies));
        }
        return $commissionFees;
    }

    public function operation($operation, $currencies)
    {
        $date = $operation[0];
        $userId = $operation[1];
        $userType = $operation[2];
        $operationType = $operation[3];
        $amount = $operation[4];
        $currency = $operation[5];

        if ($operationType == self::OPERATION_TYPE_DEPOSIT) {
            $deposit = new Deposit();
            return $deposit->calculate($amount);
        } else if ($operationType == self::OPERATION_TYPE_WITHDRAW) {
            $withdraw = new Withdraw();
            return $withdraw->calculate($amount, $userId, $userType, $currency, $date, $currencies);
        }
        return 0;
    }

    public function commissionFeeFormat($fee)
    {
        $feeRoundUp = self::roundUp($fee, 2);
        return number_format($feeRoundUp, 2);
    }

    public function roundUp($value, $precision)
    {
        $pow = pow(10, $precision);
        return (ceil($pow * $value) + ceil($pow * $value - ceil($pow * $value))) / $pow;
    }

    public function show($commissionFees)
    {
        foreach ($commissionFees as $commissionFee) {
            echo $commissionFee . "<br>";
        }
    }
}