<?php

namespace frontend\controllers;

use common\models\User;

class UserController extends \yii\web\Controller {

    public function actionProfile() {

        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;

        if (isset($_GET['user_id'])) {
            if (is_numeric($_GET['user_id'])) {

                $user_id = User::find()->select(['user.id', 'leadership_details.name as leader_law', 'leadership.name as leader_name', 'username', 'email', 'profile.name', 'last_clear_khoms', 'date_khoms', 'leadership_legalID'])->where(['user.id' => $_GET['user_id']])->leftJoin('profile', '`user`.`id` = `profile`.`user_id`')->leftJoin('leadership_details', '`leadership_details`.`id` = `profile`.`leadership_legalID`')
                                ->leftJoin('leadership', '`leadership_details`.`leadershipID` = `leadership`.`id`')
                                ->asArray()->one();


                if (is_array($user_id) and count($user_id) > 0) {
                    return array('status' => true, 'data' => $user_id);
                }
            }
        }


        return array('status' => false, 'data' => 'User Not found');
    }

}
