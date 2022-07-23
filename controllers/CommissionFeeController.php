<?php

namespace app\controllers;


use app\models\CommissionFree\CommissionFee;
use app\models\CommissionFree\Currency;
use app\models\CommissionFree\ReadFile;
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
