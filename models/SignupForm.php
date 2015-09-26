<?php
namespace app\models;

use app\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $email_repeat;
    public $password;
    public $password_repeat;
    public $verifyCode;
    public $role;
    public $id_manager;
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
            //['iagree', 'required', 'requiredValue' => 1, 'message' => ''],

            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => 'app\models\User', 'message' => 'Это имя пользователя уже занято.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            //['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => 'app\models\User', 'message' => 'Этот e-mail уже используется.'],

            ['email_repeat', 'filter', 'filter' => 'trim'],
            //['email_repeat', 'required'],
            ['email_repeat', 'email'],
            ['email_repeat', 'compare', 'compareAttribute'=>'email', 'message'=>'Электронные адреса не совпадают'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['password_repeat', 'required'],
            ['password_repeat', 'string', 'min' => 6],
            ['password_repeat', 'compare', 'compareAttribute'=>'password', 'message'=>'Пароли не совпадают'],

            ['role', 'integer'],
            ['id_manager', 'integer'],

            //['verifyCode', 'captcha', 'captchaAction'=>'/users/default/captcha'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'iagree' => 'Соглашение с правилами',
            'username' => 'Логин',
            'email' => 'Электронная почта',
            'email_repeat' => 'Электронная почта',
            'password' => 'Пароль',
            'password_repeat' => 'Пароль',
            

        ];
    }
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->role = $this->role;
            $user->id_manager = $this->id_manager;
            $user->setPassword($this->password);
            $user->status = User::STATUS_ACTIVE;
            $user->generateAuthKey();
            $user->generateEmailConfirmToken();
                        
            if ($user->save()) {
                                
                return $user;
            }
        }

        return null;
    }
}
