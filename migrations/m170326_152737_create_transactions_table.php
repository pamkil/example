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
            'user_from' => $this->integer()->notNull(),
            'user_to' => $this->integer()->notNull(),
            'date' => $this->dateTime()->notNull(),
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
