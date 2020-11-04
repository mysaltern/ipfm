<?php

namespace frontend\controllers;

use Yii;

class IncomecategoryController extends \yii\web\Controller {

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
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
        $incomeCat = \common\models\IncomeCategory::find();
        if (isset($_GET['type'])) {
            $incomeCat = $incomeCat->where(['type' => (int) $_GET['type']]);
        }
        $incomeCat = $incomeCat->all();
        if (count($incomeCat) > 0) {
            return array('status' => true, 'data' => $incomeCat);
        } else {
            return array('status' => false, 'data' => 'No expenditures Category Found');
        }
    }

}
