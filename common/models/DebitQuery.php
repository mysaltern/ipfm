<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Debit]].
 *
 * @see Debit
 */
class DebitQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Debit[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Debit|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
