<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\components;

use hoomanMirghasemi\jdf\Jdf;
use Yii;
use yii\base\Component;

class Calculate extends Component {

    public function debit($userID) {
        $debit = \common\models\Debit::sum($userID);

        return $debit;
    }

    public function income($userID) {
        $income = \common\models\Income::sum($userID, false, false, 'allNotPayedKhoms');
        return $income;
    }

    public function expenditures($userID, $cat = false, $allOrNot = false) {

        $data = \common\models\Expenditures::data($userID, $cat, $allOrNot);
        return $data;
    }

    public function calculation_formula_khoms($userID, $date, $type) {
        
    }

    public function dateWithNumber($number = 0) {

        $date = [];

        $thisMonth = Jdf::jdate('n');
        $thisMonth = Yii::$app->Proccess->convert($thisMonth);

        if ($thisMonth > $number) {
            $month = $thisMonth - $number;
        } elseif ($thisMonth == $number) {
            $month = 12;
        } else {
            $month = 12 - ($number - $thisMonth);
        }

        //  $month = (int) $thisMonth - $number + 1;

        $thisYear = Jdf::jdate('Y');
        $start = Jdf::jmktime(0, 0, 1, $month, 1, $thisYear);
        $end = Jdf::jmktime(0, 0, 1, $month, 31, $thisYear);


        $date['name'] = Jdf::jdate('F', $start);

        $date['start'] = $start;
        $date['end'] = $end;

        return $date;
    }

}
