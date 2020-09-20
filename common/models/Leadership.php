<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "leadership".
 *
 * @property int $id
 * @property string|null $name
 *
 * @property LeadershipDetails[] $leadershipDetails
 */
class Leadership extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'leadership';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
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
        ];
    }

    /**
     * Gets query for [[LeadershipDetails]].
     *
     * @return \yii\db\ActiveQuery|LeadershipDetailsQuery
     */
    public function getLeadershipDetails()
    {
        return $this->hasMany(LeadershipDetails::className(), ['leadershipID' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return LeadershipQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LeadershipQuery(get_called_class());
    }
}
