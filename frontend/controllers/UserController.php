<?php

namespace frontend\controllers;

use common\models\User;
use Yii;

class UserController extends \yii\web\Controller {

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

    public function actionLogin() {
        die(2);
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

    public function actionSignup() {
        
    }

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
