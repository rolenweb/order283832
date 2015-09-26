<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property integer $id_manager
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $auth_key
 * @property string $email_confirm_token
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $username
 * @property string $firstname
 * @property string $secondname
 * @property integer $role
 * @property integer $status
 */
class Users extends \yii\db\ActiveRecord
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
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_manager', 'created_at', 'updated_at', 'role', 'status'], 'integer'],
            [['auth_key'], 'string', 'max' => 32],
            [['email_confirm_token', 'password_hash', 'password_reset_token', 'username', 'firstname', 'secondname'], 'string', 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            //['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => 'app\models\User', 'message' => 'Этот e-mail уже используется.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_manager' => 'Id Manager',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'auth_key' => 'Auth Key',
            'email_confirm_token' => 'Email Confirm Token',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'username' => 'Username',
            'firstname' => 'Firstname',
            'secondname' => 'Secondname',
            'role' => 'Role',
            'status' => 'Status',
        ];
    }
}
