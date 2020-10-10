<?php

namespace frontend\controllers;

use Yii;

class KhomsController extends \yii\web\Controller {

    public function actionCalculate() {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
        $userID = Yii::$app->Proccess->userID(isset($_GET['user_id']) ? $_GET['user_id'] : null);

        if ($userID == false) {
            return array('status' => false, 'data' => 'User id connot be blank');
        }

        $asset = \common\models\Income::sum($userID, false, false, 'notPayed', 2, false);


        $income = \common\models\Income::sum($userID, false, false, 'notPayed', 1, false);

        $expenditures = \common\models\Expenditures::sum($userID, false, false, 'notPayed');
        $debit = \common\models\Debit::sum($userID, FALSE, FALSE);

        $khoms = Yii::$app->Calculate->khomsStatic($income, $asset, $debit, $expenditures);



        var_dump($khoms);
        var_dump($asset);
        var_dump($income);
        var_dump($expenditures);
        var_dump($debit);
        die;
        return $this->render('calculate');
    }

}
