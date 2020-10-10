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
        if (isset($_GET['type']) == false) {
            return array('status' => false, 'data' => 'type connot be blank');
        }
        $income = Income::find()
                ->select([ 'income.id', 'khomsID', 'name', 'income_category.title as category_name', 'income_category.id as category_id', 'amount', 'date'])
                ->join('INNER JOIN', 'income_category', 'income_category.id=income.categoryID')
                ->where(['userID' => $userID])
                ->andWhere(['income_category.type' => (int) $_GET['type']]);

        if (isset($_GET['categoryID'])) {
            $income = $income->andWhere(['categoryID' => $_GET['categoryID']]);
        }
        $income = $income->orderBy('income.id desc')->asArray()
                ->all();

        $sum = \common\models\Income::sum($userID, false, false, 'notPayed', (int) $_GET['type'], false);

        if (count($income) > 0) {
            return array('status' => true, 'data' => $income, 'sum' => $sum);
        } else {
            return array('status' => false, 'data' => 'No income Found');
        }
    }

}
