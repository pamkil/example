<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property string $name
 * @property string $balance
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'balance'], 'required'],
            [['balance'], 'number'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'balance' => 'Balance',
        ];
    }

    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['user_id' => 'id']);
    }

    public function getToTransaction()
    {
        return $this->hasMany(Transaction::className(), ['user_to' => 'id']);
    }

    public function getFromTransaction()
    {
        return $this->hasMany(Transaction::className(), ['user_from' => 'id']);
    }


}
