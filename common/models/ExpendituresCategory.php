<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "expenditures_category".
 *
 * @property int $id
 * @property string $name
 * @property int|null $type
 *
 * @property Expenditures[] $expenditures
 */
class ExpendituresCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expenditures_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['type'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'type' => 'Type',
        ];
    }

    /**
     * Gets query for [[Expenditures]].
     *
     * @return \yii\db\ActiveQuery|ExpendituresQuery
     */
    public function getExpenditures()
    {
        return $this->hasMany(Expenditures::className(), ['expendituresCategoryID' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return ExpendituresCategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ExpendituresCategoryQuery(get_called_class());
    }
}
