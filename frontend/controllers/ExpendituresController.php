<?php

namespace frontend\controllers;

use common\models\Expenditures;
use Yii;

class ExpendituresController extends \yii\web\Controller {

    public $enableCsrfValidation = false;

    public function actionCreate() {

        Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;

        $expenditures = new Expenditures();

        $expenditures->scenario = Expenditures:: SCENARIO_CREATE;
        $expenditures->attributes = \yii::$app->request->post();
        if ($expenditures->validate()) {
            $expenditures->save();
            return array('status' => true, 'data' => 'expenditure record is successfully Saved');
        } else {
            return array('status' => false, 'data' => $expenditures->getErrors());
        }
    }

}
