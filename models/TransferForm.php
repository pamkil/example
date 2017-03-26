<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class TransferForm extends Model
{
    public $user_to;
    public $user_from;
    public $sum;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['user_from', 'user_to', 'sum'], 'required'],
        ];
    }


    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function transfer()
    {
        if ($this->validate()) {
            return $this->transferMoney($this->sum, $this->user_from, $this->user_to);
        }
        return false;
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
        $transactionDB = User::getDb()->beginTransaction();
        $user_from = User::findOne($user_from);
        $user_to = User::findOne($user_to);
        if (empty($user_from) || empty($user_to)){
            $transactionDB->rollBack();
            throw new \Exception('Не найден один из пользователей');
        }
        if ($sum < 0)
        {
            $transactionDB->rollBack();
            throw new \Exception('Сумма не должна быть меньше 0');
        }
        try
        {
            $user_to->balance += $sum;
            $user_to->save();
            $user_from->balance -= $sum;
            $user_from->save();
            $transact = new Transact();
            $transact->setAttributes([
                'user_from' => $user_from,
                'user_to' => $user_to,
                'sum' => $sum,
                'date' => Yii::$app->formatter->asDate('now', 'Y-m-d H:i:s'),
            ]);
            $transact->save();
            $transactionDB->commit();
        } catch(\Exception $e) {
            $transactionDB->rollBack();
            throw $e;
        } catch(\Throwable $e) {
            $transactionDB->rollBack();
            throw $e;
        }
        return true;
    }
}
