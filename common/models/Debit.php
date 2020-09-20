<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "debit".
 *
 * @property int $id
 * @property string $name
 * @property string $amount
 * @property int|null $date
 * @property int|null $userID
 *
 * @property User $user
 * @property Expenditures[] $expenditures
 */
class Debit extends \yii\db\ActiveRecord {

    const SCENARIO_CREATE = 'create';

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'debit';
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
            [['userID'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userID' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'amount' => 'Amount',
            'date' => 'Date',
            'userID' => 'User ID',
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
     * Gets query for [[Expenditures]].
     *
     * @return \yii\db\ActiveQuery|ExpendituresQuery
     */
    public function getExpenditures() {
        return $this->hasMany(Expenditures::className(), ['debitID' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return DebitQuery the active query used by this AR class.
     */
    public static function find() {
        return new DebitQuery(get_called_class());
    }

    public static function sum($userID, $startDate = false, $endDate = false) {

        $sum = Debit::find()->select('sum(amount)')->where(['userID' => $userID])->asArray()->one();
        return $sum;
    }

    public static function reportMonthly($userID) {

        $debit = [];

        for ($x = 0; $x <= 11; $x++) {
            $information = Yii::$app->Calculate->dateWithNumber($x);
            $debit[$x]['sum'] = \common\models\Debit::find()->where(['userID' => $userID])->andWhere(['between', 'date', $information['start'], $information['end']])->sum('amount');
            $debit[$x]['name'] = $information['name'];
        }

        return $debit;
    }

}
