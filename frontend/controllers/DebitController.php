<?php

namespace frontend\controllers;

use common\models\Debit;
use Yii;

class DebitController extends \yii\web\Controller {

    public $enableCsrfValidation = false;

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

    public function actionCreate() {

        Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;

        $debit = new Debit();

        $debit->scenario = Debit:: SCENARIO_CREATE;
        $debit->attributes = \yii::$app->request->post();
        if ($debit->validate()) {
            $debit->save();
            return array('status' => true, 'data' => 'debit record is successfully Saved');
        } else {
            return array('status' => false, 'data' => $debit->getErrors());
        }
    }

    public function actionIndex() {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
        $userID = Yii::$app->Proccess->userID(isset($_GET['user_id']) ? $_GET['user_id'] : null);
        if ($userID == false) {
            return array('status' => false, 'data' => 'User id connot be blank');
        }
        $debit = Debit::find()->where(['userID' => $userID])->all();
        if (count($debit) > 0) {
            return array('status' => true, 'data' => $debit);
        } else {
            return array('status' => false, 'data' => 'No debit Found');
        }
    }

}
