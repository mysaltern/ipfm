<?php

namespace frontend\controllers;

use common\models\Debit;
use Yii;

class DebitController extends \yii\web\Controller {

    public $enableCsrfValidation = false;

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
