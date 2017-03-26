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

    /**
     * @param $sum
     * @param $user_from
     * @param $user_to
     * @throws \Exception
     * @throws \Throwable
     */
    public function transferMoney($sum, $user_from, $user_to)
    {

        $transactionDB = self::getDb()->beginTransaction();
        $user_from = User::findOne($user_from);
        $user_to = User::findOne($user_to);
        if (empty($user_from) || empty($user_to)){
            throw new \Exception('Не найден один из пользователей');
        }
        if ($sum < 0)
        {
            throw new \Exception('Сумма не должна быть меньше 0');
        }
        try
        {
            $user_to->balance += $sum;
            $user_to->save();
            $user_from->balance -= $sum;
            $user_from->save();
            $transaction = new Transaction();
            $transaction->attributes = [
                'user_from' => $user_from,
                'user_to' => $user_to,
                'sum' => $sum,
                'date' => Yii::$app->formatter->asDate('now', 'Y-m-d H:i:s'),
            ];
            $transaction->save();
            $transactionDB->commit();
        } catch(\Exception $e) {
            $transactionDB->rollBack();
            throw $e;
        } catch(\Throwable $e) {
            $transactionDB->rollBack();
            throw $e;
        }
        return $user_from->balance;
    }
}
