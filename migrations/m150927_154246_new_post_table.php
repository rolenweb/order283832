<?php

use yii\db\Schema;
use yii\db\Migration;

class m150927_154246_new_post_table extends Migration
{
    public function up()
    {
        $this->createTable('post', [
            'id' => Schema::TYPE_PK,
            'id_router' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'urlimg' => Schema::TYPE_STRING . ' NULL NULL',
            'text' => Schema::TYPE_TEXT . ' NULL NULL',
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 10',
            
        ]);

    }

    public function down()
    {
        echo "m150927_154246_new_post_table cannot be reverted.\n";

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
