<?php

use yii\db\Migration;

class m260930_020541_create_tracker_tables extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%expense}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(120)->notNull(),
            'amount' => $this->decimal(10, 2)->notNull(),
            'category' => $this->string(40)->notNull(),
            'spent_on' => $this->date()->notNull(),
        ]);

        $this->createTable('{{%budget}}', [
            'id' => $this->primaryKey(),
            'month' => $this->char(7)->notNull()->unique(), // Format: YYYY-MM
            'amount' => $this->decimal(10, 2)->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%budget}}');
        $this->dropTable('{{%expense}}');
    }
}