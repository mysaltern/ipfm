<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[PayKhoms]].
 *
 * @see PayKhoms
 */
class PayKhomsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return PayKhoms[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return PayKhoms|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
