<?php

namespace frontend\controllers;

use common\models\ExpendituresCategory;
use Yii;
use hoomanMirghasemi\jdf\Jdf;

class ExpenditurescategoryController extends \yii\web\Controller {

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
        $expenditures = \common\models\Expenditures::find()
//                ->select(['amount', 'expenditures.id', 'expenditures.name as expenditures_mame', 'expenditures.userID', 'expenditures_category.name as category_name'])->where(['userID' => $userID])
//                ->join('INNER JOIN', 'income_category.id=income.categoryID')
                ->groupBy('expenditures.expendituresCategoryID')
//                ->innerJoin('expenditures_category', 'expenditures_category.id = expenditures.expendituresCategoryID')
                ->all();
        var_dump($expenditures);
        die;
        //        
        //   $date['income'] = $incomeMonthly;
        $date['expenditures'] = $expenditures;
        //  $date['debit'] = $debit;

        return $date;
    }

}
