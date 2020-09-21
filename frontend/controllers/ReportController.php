<?php

namespace frontend\controllers;

use Yii;

class ReportController extends \yii\web\Controller {

    public function actionIndex() {
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
