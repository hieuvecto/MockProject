<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    private $_className;
    private $_model;

    function __construct($className) 
    {
        parent::__construct();

        $this->_className = $className;
    }

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
            $model = $this->getModel();
            if (!$model || !$model->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect email or password.');
            }
        }
    }

    /**
     * Logs in a model using the provided email and password.
     *
     * @return bool whether the model is logged in successfully
     */
    public function login()
    {   
        $array = explode('\\', $this->_className);
        $identityClass = strtolower($array[count($array)-1]);
        Yii::info($identityClass, 'Identity class');
        if ($this->validate()) {
            return Yii::$app->$identityClass
            ->login($this->getModel(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        
        return false;
    }

    /**
     * Finds owner by [[email]]
     *
     * @return Owner|null
     */
    protected function getModel()
    {
        if ($this->_model === null) {
            $this->_model = call_user_func($this->_className . '::findByEmail', $this->email);
        }

        return $this->_model;
    }
}
