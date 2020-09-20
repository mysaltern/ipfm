<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "leadership_details".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $active
 * @property int|null $leadershipID
 *
 * @property Leadership $leadership
 * @property Profile[] $profiles
 */
class LeadershipDetails extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'leadership_details';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['active', 'leadershipID'], 'integer'],
            [['name'], 'string', 'max' => 150],
            [['leadershipID'], 'exist', 'skipOnError' => true, 'targetClass' => Leadership::className(), 'targetAttribute' => ['leadershipID' => 'id']],
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
            'active' => 'Active',
            'leadershipID' => 'Leadership ID',
        ];
    }

    /**
     * Gets query for [[Leadership]].
     *
     * @return \yii\db\ActiveQuery|LeadershipQuery
     */
    public function getLeadership()
    {
        return $this->hasOne(Leadership::className(), ['id' => 'leadershipID']);
    }

    /**
     * Gets query for [[Profiles]].
     *
     * @return \yii\db\ActiveQuery|ProfileQuery
     */
    public function getProfiles()
    {
        return $this->hasMany(Profile::className(), ['leadership_legalID' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return LeadershipDetailsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LeadershipDetailsQuery(get_called_class());
    }
}
