<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Leadership]].
 *
 * @see Leadership
 */
class LeadershipQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Leadership[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Leadership|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
