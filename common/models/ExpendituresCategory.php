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
class ExpendituresCategory extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'expenditures_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['name'], 'required'],
            [['type'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
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
    public function getExpenditures() {
        return $this->hasMany(Expenditures::className(), ['expendituresCategoryID' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return ExpendituresCategoryQuery the active query used by this AR class.
     */
    public static function find() {
        return new ExpendituresCategoryQuery(get_called_class());
    }

    public static function reportMonthly($userID) {




        $expenditures = [];

        for ($x = 0; $x <= 11; $x++) {
            $information = Yii::$app->Calculate->dateWithNumber($x);


            $data = \common\models\Expenditures::find()
                            ->select('expenditures_category.id , sum(amount) as amount,expenditures_category.name as category ')
//                ->select(['amount', 'expenditures.id', 'expenditures.name as expenditures_mame', 'expenditures.userID', 'expenditures_category.name as category_name'])->where(['userID' => $userID])
                            ->join('INNER JOIN', 'expenditures_category', 'expenditures_category.id=expenditures.expendituresCategoryID')
                            ->where(['expenditures.userID' => $userID])
                            ->andWhere(['between', 'date', $information['start'], $information['end']])
                            ->groupBy('expenditures.expendituresCategoryID')
                            ->asArray()->all();

//            $expenditures[$x]['sum'] = \common\models\Debit::find()->where(['userID' => $userID])->andWhere(['between', 'date', $information['start'], $information['end']])->sum('amount');
            $expenditures[$x]['sum'] = $data['amount'];
            $expenditures[$x]['name'] = $information['name'];
        }




        return $expenditures;
    }

    public static function report($userID) {


        $data = \common\models\Expenditures::find()
                        ->select('expenditures_category.id , sum(amount) as amount,expenditures_category.name as category ')
//                ->select(['amount', 'expenditures.id', 'expenditures.name as expenditures_mame', 'expenditures.userID', 'expenditures_category.name as category_name'])->where(['userID' => $userID])
                        ->join('INNER JOIN', 'expenditures_category', 'expenditures_category.id=expenditures.expendituresCategoryID')
                        ->where(['expenditures.userID' => $userID])
                        ->groupBy('expenditures.expendituresCategoryID')
                        ->asArray()->all();

        return $data;
    }

}
