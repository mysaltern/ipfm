<?php

namespace frontend\controllers;

use Yii;
use common\models\Income;

class IncomeController extends \yii\web\Controller
{

    public $enableCsrfValidation = false;

    public static function allowedDomains()
    {
        return [
            // '*',                        // star allows all domains
            Yii::$app->params['frontendURL']
        ];
    }

    public function behaviors()
    {
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

    public function actionCreate()
    {

        Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;


        $income = new Income();

        $income->scenario = Income:: SCENARIO_CREATE;
        $income->attributes = \yii::$app->request->post();

        if ($income->validate())
        {

            $type = \common\models\IncomeCategory::find()->select('type')->where(['id' => $income->categoryID])->asArray()->one();

            $income->type = $type['type'];
            $date = $income->date;
            $timestamp = strtotime(str_replace('/', '-', $date));
            $income->amount = preg_replace('/\D/', '', $income->amount);
            $income->date = $timestamp;
            $income->save(false);
            return array('status' => true, 'data' => 'Income record is successfully Saved');
        }
        else
        {
            return array('status' => false, 'data' => $income->getErrors());
        }
    }

    public function actionSell($id)
    {
        Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;

        $income = Income::find()->where(['id' => $id])->one();

        $income->scenario = Income:: SCENARIO_SELL;
        $income->attributes = \yii::$app->request->post();

        if ($income->validate())
        {
            $income->id = $id;
            $income->date_outcome = time();
            $income->sell = 1;
//            $type = \common\models\IncomeCategory::find()->select('type')->where(['id' => $income->categoryID])->asArray()->one();
//
//            $income->type = $type['type'];
//            $date = $income->date;
//            $timestamp = strtotime(str_replace('/', '-', $date));
//            $income->amount = preg_replace('/\D/', '', $income->amount);
//            $income->date = $timestamp;
            $income->save(false);

            return array('status' => true, 'data' => 'Income record is successfully Updated');
        }
    }

    public function actionIndex()
    {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
        $userID = Yii::$app->Proccess->userID(isset($_GET['user_id']) ? $_GET['user_id'] : null);
        if ($userID == false)
        {
            return array('status' => false, 'data' => 'User id connot be blank');
        }
        if (isset($_GET['type']) == false)
        {
            return array('status' => false, 'data' => 'type connot be blank');
        }
        $income = Income::find()
                ->select(['income.id', 'sell', 'date_outcome', 'description', 'khomsID', 'name', 'income_category.title as category_name', 'income_category.id as category_id', 'amount', 'date'])
                ->join('INNER JOIN', 'income_category', 'income_category.id=income.categoryID')
                ->where(['userID' => $userID])
                ->andWhere(['income_category.type' => (int) $_GET['type']]);
        if (isset($_GET['sell']) and $_GET['sell'] == 1)
        {

            $income = $income->andWhere(['sell' => 1]);
            $sell = 1;
        }
        else
        {
            $income = $income->andWhere(['sell' => 0]);
            $sell = 0;
        }
        if (isset($_GET['categoryID']))
        {
            $income = $income->andWhere(['categoryID' => $_GET['categoryID']]);
        }
        $income = $income->orderBy('income.id desc')->asArray()
                ->all();

        $sum = \common\models\Income::sum($userID, false, false, true, (int) $_GET['type'], false, $sell);

        if (count($income) > 0)
        {
            return array('status' => true, 'data' => $income, 'sum' => $sum);
        }
        else
        {
            return array('status' => false, 'data' => 'No income Found');
        }
    }

}
