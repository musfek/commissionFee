<?php


namespace app\models\CommissionFee;


class Currency
{
    const DEFAULT_CURRENCY = 'EUR';

    public function get()
    {
        $url = 'https://developers.paysera.com/tasks/api/currency-exchange-rates';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $result = json_decode($response, true);
        curl_close($ch); // Close the connection
        return $result;

    }

    public function calculate($currencies, $currency, $amount)
    {
        if ($currency != self::DEFAULT_CURRENCY && isset($currencies["rates"][$currency])) {
            $currencyRate = $currencies["rates"][$currency];
            $commissionFee = new CommissionFee();
            // if ($currency == 'USD') $currencyRate = 1.1497;
            // if ($currency == 'JPY') $currencyRate = 129.53;
            return $commissionFee->roundUp($amount / $currencyRate, 2);
        }

        return $amount;
    }
}