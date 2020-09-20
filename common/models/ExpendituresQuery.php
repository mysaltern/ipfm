<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Expenditures]].
 *
 * @see Expenditures
 */
class ExpendituresQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Expenditures[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Expenditures|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
