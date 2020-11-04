<?php

namespace frontend\controllers;

use common\models\Expenditures;
use Yii;

class ExpendituresController extends \yii\web\Controller {

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

        $expenditures = new Expenditures();

        $expenditures->scenario = Expenditures:: SCENARIO_CREATE;
        $expenditures->attributes = \yii::$app->request->post();


        if ($expenditures->validate()) {

            $date = $expenditures->date;

            $timestamp = strtotime(str_replace('/', '-', $date));


            $expenditures->date = $timestamp;
            $expenditures->save(false);
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
                ->andWhere(['active' => 1])
                ->orderBy('expenditures.id desc');

        if (isset($category_id)) {
            $data = $expenditures->andWhere(['expenditures_category.id' => $category_id]);
        }

        //         ->groupBy('expenditures.expendituresCategoryID')
        $data = $expenditures->asArray()->all();

        if (count($data) > 0) {
            $sum = 0;
            foreach ($data as $item) {
                $sum += $item['amount'];
            }
            return array('status' => true, 'data' => $data, 'sum' => $sum);
        } else {
            return array('status' => false, 'data' => 'No expenditures Found');
        }
        return $data;
    }

}
