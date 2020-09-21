<?php

namespace common\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "expenditures".
 *
 * @property int $id
 * @property int $amount
 * @property int $expendituresCategoryID
 * @property int|null $date
 * @property int|null $userID
 * @property int|null $khoms_payedID
 * @property string|null $name 
 * @property int|null $active
 * @property int|null $incomeID
 * @property int|null $debitID
 *
 * @property User $user
 * @property ExpendituresCategory $expendituresCategory
 * @property PayKhoms $khomsPayed
 * @property Income $income
 * @property Debit $debit
 */
class Expenditures extends \yii\db\ActiveRecord {

    const SCENARIO_CREATE = 'create';

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'expenditures';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['amount', 'expendituresCategoryID'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['amount', 'expendituresCategoryID', 'date', 'userID', 'khoms_payedID', 'active', 'incomeID', 'debitID'], 'integer'],
            [['userID'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userID' => 'id']],
            [['expendituresCategoryID'], 'exist', 'skipOnError' => true, 'targetClass' => ExpendituresCategory::className(), 'targetAttribute' => ['expendituresCategoryID' => 'id']],
            [['khoms_payedID'], 'exist', 'skipOnError' => true, 'targetClass' => PayKhoms::className(), 'targetAttribute' => ['khoms_payedID' => 'id']],
            [['incomeID'], 'exist', 'skipOnError' => true, 'targetClass' => Income::className(), 'targetAttribute' => ['incomeID' => 'id']],
            [['debitID'], 'exist', 'skipOnError' => true, 'targetClass' => Debit::className(), 'targetAttribute' => ['debitID' => 'id']],
        ];
    }

    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['amount', 'expendituresCategoryID', 'userID', 'incomeID'];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'amount' => 'Amount',
            'expendituresCategoryID' => 'Expenditures Category ID',
            'date' => 'Date',
            'userID' => 'User ID',
            'khoms_payedID' => 'Khoms Payed ID',
            'active' => 'Active',
            'incomeID' => 'Income ID',
            'debitID' => 'Debit ID',
        ];
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
     * Gets query for [[ExpendituresCategory]].
     *
     * @return \yii\db\ActiveQuery|ExpendituresCategoryQuery
     */
    public function getExpendituresCategory() {
        return $this->hasOne(ExpendituresCategory::className(), ['id' => 'expendituresCategoryID'])->via('name');
    }

    /**
     * Gets query for [[KhomsPayed]].
     *
     * @return \yii\db\ActiveQuery|PayKhomsQuery
     */
    public function getKhomsPayed() {
        return $this->hasOne(PayKhoms::className(), ['id' => 'khoms_payedID']);
    }

    /**
     * Gets query for [[Income]].
     *
     * @return \yii\db\ActiveQuery|IncomeQuery
     */
    public function getIncome() {
        return $this->hasOne(Income::className(), ['id' => 'incomeID']);
    }

    /**
     * Gets query for [[Debit]].
     *
     * @return \yii\db\ActiveQuery|DebitQuery
     */
    public function getDebit() {
        return $this->hasOne(Debit::className(), ['id' => 'debitID']);
    }

    /**
     * {@inheritdoc}
     * @return ExpendituresQuery the active query used by this AR class.
     */
    public static function find() {
        return new ExpendituresQuery(get_called_class());
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

    public static function data($userID, $cat, $allOrNot) {




        $query = new Query;
// compose the query
        $query->select('*')
                ->from('expenditures');


        $query->where(['userID' => $userID]);

        if ($cat !== false) {
            $query->andWhere(['expendituresCategoryID' => $cat]);
        }


        // allOrNot true is all payed or not payed
        // allOrNot false is only notPayed {khoms_payedID null}
        // allOrnot 'payed" only payed khoms return for result
        if ($allOrNot == true) {
            //     $query->andWhere(['khoms_payedID' => !NULL]);
        }
        if ($allOrNot == false) {
            $query->andWhere(['khoms_payedID' => NULL]);
        }
        if ($allOrNot == 'payed') {
            $query->andWhere(['khoms_payedID' => !NULL]);
        }
// build and execute the query
        $rows = $query->all();
// alternatively, you can create DB command and execute it
        $command = $query->createCommand();
// $command->sql returns the actual SQL
        $rows = $command->queryAll();

        return $rows;
    }

    public static function reportMonthly($userID) {

        $expenditures = [];

        for ($x = 0; $x <= 11; $x++) {
            $information = Yii::$app->Calculate->dateWithNumber($x);
            $expenditures[$x]['sum'] = \common\models\Expenditures::find()->where(['userID' => $userID])->andWhere(['between', 'date', $information['start'], $information['end']])->sum('amount');
            $expenditures[$x]['name'] = $information['name'];
        }

        return $expenditures;
    }

}
