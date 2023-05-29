<?php
/**
 * Admin form
 * @author Athellow
 * @version 1.0
 */
namespace backend\models;

use Yii;
use yii\captcha\CaptchaValidator;
use backend\components\Model;

class AdminForm extends Model
{
    /** 表单类型 默认为添加表单 */
    public $formId = 'add';
    /** 当前登录的管理员ID */
	public $adminId = 0;

    public $username;
    public $password;
    public $email;
    public $status;
    public $avatar;

    public $filekey;
    public $captcha;
    public $rememberMe = true;

    private $_user;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        switch ($this->formId) {
            case 'login':
                $rules = [
                    // username and password are both required
                    [['username', 'password'], 'required'],
                    // rememberMe must be a boolean value
                    ['rememberMe', 'boolean'],
                    // password is validated by validatePassword()
                    ['password', 'validatePassword'],
                ];
        
                if (isset(Yii::$app->params['captcha']['status']) && Yii::$app->params['captcha']['status']) {
                    $rules[] = ['captcha', 'required'];
                    $rules[] = ['captcha', CaptchaValidator::class, 'captchaAction' => 'auth/auth/captcha'];
                }
                break;
            case 'edit':
                $rules = [
                    [['username', 'email'], 'required'],
                    ['status', 'integer'],
                    ['avatar', 'file'],
                ];
                break;
            default:
                $rules = [
                    [['username', 'password', 'email'], 'required'],
                    ['status', 'integer'],
                    ['avatar', 'file'],
                ];
        }
        
        return $rules;
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
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            $res = $user ? Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0) : false;

            if ($res) {
                $user->last_ip = Yii::$app->request->getUserIP();
                $user->last_time = time();
                $user->login_count = (int)$user['login_count'] + 1;
                $user->save();
            }

            return $res;
        }
        
        return false;
    }

    /**
     * 保存用户
     *
     * @return bool whether the user is logged in successfully
     */
    public function save($vaid)
    {p($this);
        if ($this->validate()) {
            $user = $this->getUser();
            $res = $user ? Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0) : false;

            if ($res) {
                $user->last_ip = Yii::$app->request->getUserIP();
                $user->last_time = time();
                $user->login_count = (int)$user['login_count'] + 1;
                $user->save();
            }

            return $res;
        }
        
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return Admin|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Admin::findByUsername($this->username);
        }

        return $this->_user;
    }
}