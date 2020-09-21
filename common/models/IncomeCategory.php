<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "income_category".
 *
 * @property int $id
 * @property string $title
 * @property int|null $active
 * @property int|null $type
 *
 * @property Income[] $incomes
 */
class IncomeCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'income_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['active', 'type'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'active' => 'Active',
            'type' => 'Type',
        ];
    }

    /**
     * Gets query for [[Incomes]].
     *
     * @return \yii\db\ActiveQuery|IncomeQuery
     */
    public function getIncomes()
    {
        return $this->hasMany(Income::className(), ['categoryID' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return IncomeCategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new IncomeCategoryQuery(get_called_class());
    }
}
