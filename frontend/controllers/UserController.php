<?php

namespace frontend\controllers;

use common\models\User;

class UserController extends \yii\web\Controller {

    public function actionProfile() {

        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;

        if (isset($_GET['user_id'])) {
            if (is_numeric($_GET['user_id'])) {

                $user_id = User::find()->select(['id', 'username', 'email', 'name', 'date_khoms', 'leadership_legalID'])->where(['id' => $_GET['user_id']])->leftJoin('profile', '`user`.`id` = `profile`.`user_id`')->one();

                var_dump($user_id);
                die;
                if (count($user_id) > 0) {
                    return array('status' => true, 'data' => $user_id);
                }
            }
        }


        return array('status' => false, 'data' => 'User Not found');
    }

}
