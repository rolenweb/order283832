<?php

use yii\db\Schema;
use yii\db\Migration;

class m150927_123218_new_router_table extends Migration
{
    public function up()
    {
        $this->createTable('router', [
            'id' => Schema::TYPE_PK,
            'id_object' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'title' => Schema::TYPE_STRING . ' NOT NULL',
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 10',
            
        ]);

    }

    public function down()
    {
        echo "m150927_123218_new_router_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
