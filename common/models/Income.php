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
        $scenarios['create'] = ['amount', 'name', 'date', 'khomsID', 'userID'];
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
            [['amount', 'date', 'khomsID', 'categoryID', 'userID'], 'integer'],
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

    public static function sum($userID, $startDate = false, $endDate = false, $allOrNot = false) {

//all income
        if ($allOrNot === true) {
            $sum = Income::find()->select('sum(amount)')->where(['userID' => $userID])->asArray()->one();
            return $sum;
        }
        //all  payed khoms income
        elseif ($allOrNot == false) {
            $sum = Income::find()->select('sum(amount)')->where(['userID' => $userID])->andWhere(['not', ['khomsID' => null]])->asArray()->one();
            return $sum;
        }
        //all not payed khoms income
        else {
            $sum = Income::find()->select('sum(amount)')->where(['userID' => $userID])->andWhere(['khomsID' => null])->asArray()->one();
            return $sum;
        }
    }

    public static function reportMonthly($userID) {



        $income = [];

        for ($x = 0; $x <= 11; $x++) {
            $information = Yii::$app->Calculate->dateWithNumber($x);
            $income[$x]['sum'] = \common\models\Income::find()->where(['userID' => $userID])->andWhere(['between', 'date', $information['start'], $information['end']])->sum('amount');
            $income[$x]['name'] = $information['name'];
        }



        return $income;
    }

}