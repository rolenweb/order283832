<?php
namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class Password extends Model
{
    
    public $username;
    public $password;
    public $new_password;
    public $new_password_repeat;

    private $_user = false;



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password', 'new_password', 'new_password_repeat'], 'required'],
            ['password', 'validatePassword'],
            ['password', 'string', 'min' => 6],

            ['new_password', 'string', 'min' => 6],

            ['new_password_repeat', 'string', 'min' => 6],
            ['new_password_repeat', 'compare', 'compareAttribute'=>'new_password', 'message'=>'Пароли не совпадают'],
        ];
    }

    public function attributeLabels()
    {
        return [
            
            

        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Вы вели неверный имя пользователя или пароль.');
            }
            elseif ($user && $user->status == User::STATUS_DELETED) {
                $this->addError($attribute, 'Ваша учетная запись заблокирована.');
            }
            elseif ($user && $user->status == User::STATUS_PENDING) {
                $this->addError($attribute, 'Ваша учетная запись заблокирована.');
            }
        }
    }

   
    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
