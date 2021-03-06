<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use common\models\Project;
use common\models\Task;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $firstname
 * @property string $lastname
 * @property string $client_id
 * @property string $phone
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $group_id
 * @property string $type
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_ACTIVE = 'active';
    const STATUS_DELETED = 'deleted';
    const STATUS_LOCKED = 'locked';
    
    const TYPE_ADMIN = 'admin';
    const TYPE_SUPERVISOR = 'supervisor';
    const TYPE_SERVICEMAN = 'serviceman';
    const TYPE_CLIENT = 'client';
    
    private $name;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            BlameableBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            [['username', 'email'], 'unique'],
            ['email', 'email'],
            [['firstname', 'lastname', 'username', 'type', 'phone'], 'string'],
            [['client_id', 'created_at', 'updated_at', 'created_by', 'updated_by', 'group_id'], 'integer']
        ];
    }
    
    
    public static function listStatuses(){
        return [
            self::STATUS_ACTIVE => Yii::t('app', 'aktywny'),
            self::STATUS_DELETED => Yii::t('app', 'usunięty'),
            self::STATUS_LOCKED => Yii::t('app', 'zablokowany'),
        ];
    }
    
    public static function listTypes(){
        return [
            self::TYPE_ADMIN => Yii::t('app', 'administrator'),
            self::TYPE_SUPERVISOR => Yii::t('app', 'kierownik'),
            self::TYPE_SERVICEMAN => Yii::t('app', 'pracownik'),
            self::TYPE_CLIENT => Yii::t('app', 'klient'),
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'firstname' => Yii::t('app', 'Imię'),
            'lastname' => Yii::t('app', 'Nazwisko'),
            'username' => Yii::t('app', 'Login'),
            'phone' => Yii::t('app', 'Telefon'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Utworzony'),
            'created' => Yii::t('app', 'Utworzony'),
            'updated_at' => Yii::t('app', 'Edytowany'),
            'type' => Yii::t('app', 'Typ'),
            'name' => Yii::t('app', 'Imię i nazwisko'),
            
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, ['in', 'status', [self::STATUS_ACTIVE, self::STATUS_LOCKED]]]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
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
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
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
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    
    public function isAdmin()
    {
        return $this->type == self::TYPE_ADMIN;
    }
    
    public function isSupervisor()
    {
        return $this->type == self::TYPE_SUPERVISOR;
    }
    
    public function isServiceman()
    {
        return $this->type == self::TYPE_SERVICEMAN;
    }
    
    public function isClient()
    {
        return $this->type == self::TYPE_CLIENT;
    }
    
    public static function findAllUsers($condition = false)
    {
        $query = (new \yii\db\Query)->select(['username'])
                    ->from(self::tableName())
                    ->where(['!=', 'status', User::STATUS_DELETED])
                    ->andWhere(['!=', 'type', User::TYPE_CLIENT]);
        if ($condition){
            $query->andWhere($condition);
        }
        $query->indexBy('id');
        return $query->column();
    }
    
    public static function findAllUsersClients()
    {
        $query = (new \yii\db\Query)->select(['username'])
                    ->from(self::tableName())
                    ->where(['!=', 'status', User::STATUS_DELETED])
                    ->andWhere(['type' => User::TYPE_CLIENT])
                    ->indexBy('id')
                    ->column();
        return $query;
    }
    
    public function getProjects()
    {
        return $this->hasMany(Project::className(), ['id' => 'owner_id']);
    }
    
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['id' => 'created_by']);
    }
    
    public function setName($name){
        $this->name = $name;
    }
    
    public function getName(){
        if (empty($this->firstname) && empty($this->lastname)){
            return $this->username;
        }
        
        $name = $this->firstname .' '. $this->lastname;
        $this->setName($name);
        return $name;
    }
}
