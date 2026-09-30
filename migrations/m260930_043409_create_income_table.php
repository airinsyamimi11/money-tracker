<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%income}}`.
 */
class m260930_043409_create_income_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%income}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(80)->notNull(),
            'amount' => $this->decimal(10, 2)->notNull(),
            'frequency' => $this->string(10)->notNull()->defaultValue('monthly'),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%income}}');
    }
}
