<?php

namespace frontend\controllers;

use Yii;
use common\models\PayKhoms;
use hoomanMirghasemi\jdf\Jdf;

class PaykhomsController extends \yii\web\Controller {

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

    public function actionIndex() {


        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
        $userID = Yii::$app->Proccess->userID(isset($_GET['user_id']) ? $_GET['user_id'] : null);
        if ($userID == false) {
            return array('status' => false, 'data' => 'User id connot be blank');
        }
        $data = \common\models\PayKhoms::find()
                ->select(['pay_khoms.id', 'date', 'amount'])
                //    ->join('INNER JOIN', 'income_category', 'income_category.id=income.categoryID')
                ->where(['user_id' => $userID, 'active' => 1, 'deleted' => 0]);
        //     ->andWhere(['type' => (int) $_GET['type']]);
        $data = $data->asArray()->all();
        $newArray = array();
        foreach ($data as $column) {
            $column['date'] = Jdf::jdate('Y/n/j', $column['date']);
            $newArray[] = $column;
        }
        $data = $newArray;
        $khoms = Yii::$app->Calculate->completeField($userID);

        if (count($khoms) > 0) {
            return array('status' => true, 'data' => $data, 'religion' => $khoms);
        } else {
            return array('status' => false, 'data' => 'No income Found');
        }
    }

    public function actionCreate() {

        Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;


        $pay = new PayKhoms();

        $pay->scenario = PayKhoms:: SCENARIO_CREATE;
        $pay->attributes = \yii::$app->request->post();

        if ($pay->validate()) {


            $pay->active = 1;
            $pay->deleted = 0;
            $pay->save();
//            $income = \common\models\Income::find()->where(['userID' => $pay->user_id])->all();
//            $income->khomsID = $pay->id;
//            $income->save(false);
            Yii::$app->db->createCommand()
                    ->update('income', ['khomsID' => $pay->id], "userID= $pay->user_id")
                    ->execute();

            Yii::$app->db->createCommand()
                    ->update('expenditures', ['khoms_payedID' => $pay->id], "userID= $pay->user_id")
                    ->execute();
            return array('status' => true, 'data' => 'Income record is successfully Saved');
        } else {
            return array('status' => false, 'data' => $pay->getErrors());
        }
    }

}
