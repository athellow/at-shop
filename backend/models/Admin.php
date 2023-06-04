<?php
/**
 * Admin model
 * @author Athellow
 * @version 1.0
 */
namespace backend\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;
use backend\components\ActiveRecord;
use common\helpers\Time;

class Admin extends ActiveRecord implements IdentityInterface
{
    /** 新增场景 */
    // const SCENARIO_ADD = 'add';
    /** 编辑场景 */
    // const SCENARIO_EDIT = 'edit';

    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    public static $statusList = [
        self::STATUS_DELETED => '删除',
        self::STATUS_INACTIVE => '禁用',
        self::STATUS_ACTIVE => '启用',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin}}';
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        // 如果不覆盖 scenarios() 方法，表示所有只要出现在 rules() 中的属性都是 安全 的。
        // 提供一个别名为 safe 的验证器来申明 哪些属性是 安全 的不需要被验证。如 [['title', 'description'], 'safe']
        // 同 scenarios()，想验证一个属性但不想让他是安全的，可在 rules() 方法中属性名加一个惊叹号 !
        // 只有安全属性才能被块赋值。
        
        return [
            // username and password are both required
            [['username', '!password_hash', 'email'], 'required'],
            // email 属性必须是一个有效的电子邮箱地址
            ['email', 'email'],
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [(string)Admin::STATUS_ACTIVE, (string)Admin::STATUS_INACTIVE, (string)Admin::STATUS_DELETED]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // 用处：定义哪些属性应被验证，定义哪些属性安全
        // 块赋值只应用在模型当前 scenario 场景 scenarios() 方法 列出的称之为 安全属性 的属性上
        // 想验证一个属性但不想让他是安全的，可在 scenarios() 方法中属性名加一个惊叹号 !

        $scenarios = parent::scenarios();

        // $scenarios[self::SCENARIO_ADD] = ['username', 'password_hash', 'email', 'status'];
        // $scenarios[self::SCENARIO_EDIT] = ['username', 'password_hash', 'email', 'status'];
        
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password_hash' => '密码',
            'email' => '邮箱',
            'status' => '状态',
            'avatar' => '头像',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function fields()
    {
        $fields = parent::fields();

        // 去掉一些包含敏感信息的字段
        unset($fields['auth_key'], $fields['password_hash'], $fields['password_reset_token'], $fields['verification_token']);

        return $fields;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }
    
    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token)
    {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        if (!empty($password)) {
            $this->password_hash = Yii::$app->security->generatePasswordHash($password);
        }
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    
    /**
     * Adds an additional WHERE condition to the existing one.
     * @param array $params
     * @param \yii\db\Query $query
     * @param string $alias
     * @return \yii\db\Query
     */
    public static function getWhere($params, $query = null, $alias = '')
    {
        if ($query === null) {
            $query = self::find();
        }

        if ($alias) $alias .= '.';

        if (!empty($params['username'])) {
            $query->andWhere([$alias .'username' => $params['username']]);
        }
        
        if (!empty($params['email'])) {
            $query->andWhere([$alias .'username' => $params['email']]);
        }
        
        if (isset($params['status']) && $params['status'] !== '') {
            $query->andWhere([$alias .'status' => $params['status']]);
        }
        
        if (!empty($params['create_date']) && ($timeRange = Time::getRange($params['create_date']))) {
            $query->andWhere(['between', $alias .'created_at', $timeRange['startTime'], $timeRange['endTime']]);
        }

        return $query;
    }

}
