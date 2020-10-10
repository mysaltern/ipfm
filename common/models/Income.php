<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "income".
 *
 * @property int $id
 * @property int $amount
 * @property int|null $date
 * @property int|null $khomsID
 * @property int|null $userID
 *
 * @property int|null $categoryID 
 * @property Expenditures[] $expenditures
 * @property PayKhoms $khoms
 * @property User $user
 * @property int|null $type 
 */
class Income extends \yii\db\ActiveRecord {

    const SCENARIO_CREATE = 'create';

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'income';
    }

    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['amount', 'categoryID', 'name', 'date', 'khomsID', 'userID'];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['name', 'amount'], 'required'],
            [['date', 'userID'], 'integer'],
            [['name', 'amount'], 'string', 'max' => 255],
            [['amount', 'type', 'date', 'khomsID', 'categoryID', 'userID'], 'integer'],
            [['khomsID'], 'exist', 'skipOnError' => true, 'targetClass' => PayKhoms::className(), 'targetAttribute' => ['khomsID' => 'id']],
            [['userID'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userID' => 'id']],
            [['categoryID'], 'exist', 'skipOnError' => true, 'targetClass' => IncomeCategory::className(), 'targetAttribute' => ['categoryID' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'amount' => 'Amount',
            'date' => 'Date',
            'khomsID' => 'Khoms ID',
            'userID' => 'User ID',
            'type' => 'Type',
            'categoryID' => 'Category ID',
        ];
    }

    /**
     * Gets query for [[Expenditures]].
     *
     * @return \yii\db\ActiveQuery|ExpendituresQuery
     */
    public function getExpenditures() {
        return $this->hasMany(Expenditures::className(), ['incomeID' => 'id']);
    }

    public function getCategory() {
        return $this->hasOne(IncomeCategory::className(), ['id' => 'categoryID']);
    }

    /**
     * Gets query for [[Khoms]].
     *
     * @return \yii\db\ActiveQuery|PayKhomsQuery
     */
    public function getKhoms() {
        return $this->hasOne(PayKhoms::className(), ['id' => 'khomsID']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'userID']);
    }

    /**
     * {@inheritdoc}
     * @return IncomeQuery the active query used by this AR class.
     */
    public static function find() {
        return new IncomeQuery(get_called_class());
    }

    public static function sum($userID, $startDate = false, $endDate = false, $allOrNot = false, $type = false, $category = false) {



        $data = Income::find()->select('sum(amount) as price');
        $data = $data->where(['userID' => 1, 'income.type' => 0]);
        if ($type !== false) {
            $data = $data->leftJoin('income_category', 'income_category.id = income.categoryID')->andWhere(['income_category.type' => $type]);
        }
        if ($allOrNot === true) {
            
        }
        //        //all  payed khoms income
        elseif ($allOrNot == false) {
            $data = $data->andWhere(['not', ['khomsID' => null]]);
            return $sum;
        }
        //        //all not payed khoms income
        else {
            $data = $data->andWhere(['khomsID' => null]);
        }
        $data = $data->asArray()->one();
        $data = $data['price'];

        return $data;
////all income
//        if ($allOrNot === true) {
//            $sum = Income::find()->select('sum(amount)')->where(['userID' => $userID])->asArray()->one();
//            return $sum;
//        }
//
//        if ($type != false) {
//            
//        }
//
//        if ($category != false) {
//            
//        }
//        //all  payed khoms income
//        elseif ($allOrNot == false) {
//            $sum = Income::find()->select('sum(amount)')->where(['userID' => $userID])->andWhere(['not', ['khomsID' => null]])->asArray()->one();
//            return $sum;
//        }
//        //all not payed khoms income
//        else {
//            $sum = Income::find()->select('sum(amount)')->where(['userID' => $userID])->andWhere(['khomsID' => null])->asArray()->one();
//            return $sum;
//        }
    }

    public static function reportMonthly($userID) {



        $income = [];

        for ($x = 0; $x <= 11; $x++) {
            $information = Yii::$app->Calculate->dateWithNumber($x);
            $sum = (int) \common\models\Income::find()->where(['userID' => $userID])->andWhere(['between', 'date', $information['start'], $information['end']])->sum('amount');


            $income[$x]['sum'] = $sum;
            $income[$x]['name'] = $information['name'];
        }



        return $income;
    }

}
