<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginOwnerForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    private $_owner;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // email and password are both required
            [['email', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
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
            $owner = $this->getOwner();
            if (!$owner || !$owner->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect email or password.');
            }
        }
    }

    /**
     * Logs in a owner using the provided email and password.
     *
     * @return bool whether the owner is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->owner->login($this->getOwner(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        
        return false;
    }

    /**
     * Finds owner by [[email]]
     *
     * @return Owner|null
     */
    protected function getOwner()
    {
        if ($this->_owner === null) {
            $this->_owner = Owner::findByEmail($this->email);
        }

        return $this->_owner;
    }
}
