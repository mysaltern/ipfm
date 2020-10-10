<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "pay_khoms".
 *
 * @property int $id
 * @property int|null $date
 * @property int|null $user_id
 * @property int|null $amount
 * @property int|null $active
 * @property int|null $deleted
 *
 * @property Expenditures[] $expenditures
 * @property Income[] $incomes
 * @property User $user
 */
class PayKhoms extends \yii\db\ActiveRecord {

    const SCENARIO_CREATE = 'create';

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'pay_khoms';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['date', 'user_id', 'amount', 'active', 'deleted'], 'integer'],
            [['user_id', 'amount'], 'required'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['date', 'user_id', 'amount', 'active', 'deleted'];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'user_id' => 'User ID',
            'amount' => 'Amount',
            'active' => 'Active',
            'deleted' => 'Deleted',
        ];
    }

    /**
     * Gets query for [[Expenditures]].
     *
     * @return \yii\db\ActiveQuery|ExpendituresQuery
     */
    public function getExpenditures() {
        return $this->hasMany(Expenditures::className(), ['khoms_payedID' => 'id']);
    }

    /**
     * Gets query for [[Incomes]].
     *
     * @return \yii\db\ActiveQuery|IncomeQuery
     */
    public function getIncomes() {
        return $this->hasMany(Income::className(), ['khomsID' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return PayKhomsQuery the active query used by this AR class.
     */
    public static function find() {
        return new PayKhomsQuery(get_called_class());
    }

}
