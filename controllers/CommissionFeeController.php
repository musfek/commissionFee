<?php

namespace app\controllers;


use app\models\CommissionFee\CommissionFee;
use app\models\CommissionFee\Currency;
use app\models\CommissionFee\ReadFile;
use yii\web\Controller;

class CommissionFeeController extends Controller
{
    public function actionIndex($fileName)
    {
        $readFile = new ReadFile();
        $operations = $readFile->csvToArray($fileName);

        $currency = new Currency();
        $currencies = $currency->get();

        $commissionFee = new CommissionFee();
        $calculationArray = $commissionFee->calculate($operations,$currencies);

        $commissionFee->show($calculationArray);
    }

}
