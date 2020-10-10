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

    public function actionIndex() {

        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
        $userID = Yii::$app->Proccess->userID(isset($_GET['user_id']) ? $_GET['user_id'] : null);
        if ($userID == false) {
            return array('status' => false, 'data' => 'User id connot be blank');
        }
        if (isset($_GET['category_id'])) {
            $category_id = (int) $_GET['category_id'];
        }


        $expenditures = \common\models\Expenditures::find()
                ->select('expenditures.id as id ,expenditures.date,expenditures.amount,expenditures.incomeID,income.name as income_name,expenditures.debitID,debit.name as debit_name, expenditures_category.id as category_id,expenditures.name ,expenditures_category.name as category_name ')
                ->join('INNER JOIN', 'expenditures_category', 'expenditures_category.id=expenditures.expendituresCategoryID')
                ->join('LEFT JOIN', 'income', 'expenditures.incomeID=income.id')
                ->join('LEFT JOIN', 'debit', 'expenditures.debitID=expenditures.id')
                ->where(['expenditures.userID' => $userID])
                ->andWhere(['active' => 1]);

        if (isset($category_id)) {
            $data = $expenditures->andWhere(['expenditures_category.id' => $category_id]);
        }

        //         ->groupBy('expenditures.expendituresCategoryID')
        $data = $expenditures->asArray()->all();

        return $data;
    }

}
