<?php

namespace app\models\CommissionFree;

use Yii;

class ReadFile
{
    public  function csvToArray($fileName)
    {
        $fileLocation = Yii::getAlias('@webroot') . "/file/" . $fileName;
        $file = fopen($fileLocation, "r");
        $fileArray = [];
        while (!feof($file)) {
            $fileArray[] = fgetcsv($file);
        }
        fclose($file);
        return $fileArray;
    }
}