<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transactions".
 *
 * @property integer $id
 * @property integer $user_from
 * @property integer $user_to
 * @property string $date
 * @property string $sum
 */
class Transact extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transactions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_from', 'user_to', 'date', 'sum'], 'required'],
            [['user_from', 'user_to'], 'integer'],
            [['date'], 'safe'],
            [['sum'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_from' => 'User From',
            'user_to' => 'User To',
            'date' => 'Date',
            'sum' => 'Sum',
        ];
    }
}
