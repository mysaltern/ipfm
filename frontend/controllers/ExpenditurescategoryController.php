<?php

namespace frontend\controllers;

use common\models\ExpendituresCategory;
use Yii;
use hoomanMirghasemi\jdf\Jdf;

class ExpenditurescategoryController extends \yii\web\Controller {

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
        $expendituresCategory = ExpendituresCategory::find()->all();
        if (count($expendituresCategory) > 0) {
            return array('status' => true, 'data' => $expendituresCategory);
        } else {
            return array('status' => false, 'data' => 'No expenditures Category Found');
        }
    }

    public function actionReport() {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
        $userID = Yii::$app->Proccess->userID(isset($_GET['user_id']) ? $_GET['user_id'] : null);
        if ($userID == false) {
            return array('status' => false, 'data' => 'User id connot be blank');
        }

        $incomeMonthly = \common\models\Income::reportMonthly($userID);
        $debit = \common\models\Debit::reportMonthly($userID);
//        $expenditures = \common\models\Expenditures::find()->select('expenditures_category.name')->where(['userID' => $userID])->innerJoin('expenditures_category', '`expenditures`.`expendituresCategoryID` = `expenditures_category`.`id`')->one();
//        var_dump($expenditures);
//        
//        
        $expenditures = \common\models\Expenditures::reportMonthly($userID);
        $expendituresCategory = \common\models\ExpendituresCategory::report($userID);

        $date['income'] = $incomeMonthly;
        $date['expenditures'] = $expenditures;
        $date['expendituresCategory'] = $expendituresCategory;
        $date['debit'] = $debit;

        return $date;
    }

}
