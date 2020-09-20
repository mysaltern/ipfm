<?php

namespace frontend\controllers;

use Yii;

class CalculateController extends \yii\web\Controller {

    public function actionIndex() {
        //    $sum = Yii::$app->Calculate->income(1);
        //    $sum = Yii::$app->Calculate->debit(1);
        $sum = Yii::$app->Calculate->expenditures(1, $cat = false, $payed = 'payed');
    }

}
