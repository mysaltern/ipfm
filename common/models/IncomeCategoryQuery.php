<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[IncomeCategory]].
 *
 * @see IncomeCategory
 */
class IncomeCategoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return IncomeCategory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return IncomeCategory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
