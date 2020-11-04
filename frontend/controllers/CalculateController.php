<?php

namespace frontend\controllers;

use Yii;

class CalculateController extends \yii\web\Controller {

    public static function allowedDomains() {
        return [
            // '*',                        // star allows all domains
            Yii::$app->params['frontendURL']
        ];
    }

    public function behaviors() {
        return array_merge(parent::behaviors(), [

            // For cross-domain AJAX request
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
                    // restrict access to domains:
                    'Origin' => static::allowedDomains(),
                    'Access-Control-Request-Method' => ['POST', 'GET'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age' => 3600, // Cache (seconds)
                ],
            ],
        ]);
    }

    public function actionIndex() {
        //    $sum = Yii::$app->Calculate->income(1);
        //    $sum = Yii::$app->Calculate->debit(1);
        $sum = Yii::$app->Calculate->expenditures(1, $cat = false, $payed = 'payed');
    }

}
