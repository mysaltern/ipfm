<?php

namespace frontend\controllers;

class IncomecategoryController extends \yii\web\Controller {

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
