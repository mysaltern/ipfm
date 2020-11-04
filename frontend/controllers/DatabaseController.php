<?php

namespace frontend\controllers;

use Yii;

class DatabaseController extends \yii\web\Controller {

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

    public function actionClean() {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;

        if (!isset($_GET['clean']) or $_GET['clean'] !== 'true') {
            return array('status' => false, 'data' => 'Clean connot be blank');
        }


        $income = \common\models\Income::deleteAll();
        $debit = \common\models\Debit::deleteAll();
        $expenditures = \common\models\Expenditures::deleteAll();
        $pay = \common\models\PayKhoms::deleteAll();

        //   if ($pay !== false)
        {
            return array('status' => true, 'data' => 'Your database successfully cleaned');
        }
    }

}
