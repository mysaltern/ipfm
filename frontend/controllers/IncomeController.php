<?php

namespace frontend\controllers;

use Yii;
use common\models\Income;

class IncomeController extends \yii\web\Controller {

    public $enableCsrfValidation = false;

    public function actionCreate() {

        Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;

        $income = new Income();

        $income->scenario = Income:: SCENARIO_CREATE;
        $income->attributes = \yii::$app->request->post();
        if ($income->validate()) {
            $income->save();
            return array('status' => true, 'data' => 'Income record is successfully Saved');
        } else {
            return array('status' => false, 'data' => $income->getErrors());
        }
    }

    public function actionIndex() {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
        $userID = Yii::$app->Proccess->userID(isset($_GET['user_id']) ? $_GET['user_id'] : null);
        if ($userID == false) {
            return array('status' => false, 'data' => 'User id connot be blank');
        }
        $income = Income::find()->where(['userID' => $userID])->all();
        if (count($income) > 0) {
            return array('status' => true, 'data' => $income);
        } else {
            return array('status' => false, 'data' => 'No income Found');
        }
    }

}
