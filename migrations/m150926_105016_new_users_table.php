<?php

use yii\db\Schema;
use yii\db\Migration;

class m150926_105016_new_users_table extends Migration
{
    public function up()
    {
        $this->createTable('users', [
            'id' => Schema::TYPE_PK,
            'id_manager' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'auth_key' => Schema::TYPE_STRING . '(32) NULL DEFAULT NULL',
            'email_confirm_token' => Schema::TYPE_STRING . ' NULL DEFAULT NULL',
            'password_hash' => Schema::TYPE_STRING . ' NOT NULL',
            'password_reset_token' => Schema::TYPE_STRING . ' NULL DEFAULT NULL',
            'email' => Schema::TYPE_STRING . ' NULL DEFAULT NULL',
            'username' => Schema::TYPE_STRING . ' NOT NULL',
            'firstname' => Schema::TYPE_STRING . ' NULL DEFAULT NULL',
            'secondname' => Schema::TYPE_STRING . ' NULL DEFAULT NULL',
            'role' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 1',
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 10',
            
        ]);

    }

    public function down()
    {
        echo "m150926_105016_new_users_table cannot be reverted.\n";

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
