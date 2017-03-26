<?php

use yii\db\Migration;

/**
 * Handles the creation of table `transactions`.
 */
class m170326_152737_create_transactions_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('transactions', [
            'id' => $this->primaryKey(),
            'user_from' => $this->integer(),
            'user_to' => $this->integer(),
            'date' => $this->dateTime(),
        ], 'ENGINE=InnoDB');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('transactions');
    }
}
