<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use common\models\Project;

/**
 * User model
 *
 * @property integer $id
 * @property string $firstname
 * @property string $lastname
 * @property string $acronym
 * @property string $phone
 * @property string $nip
 * @property string $street
 * @property string $street_no
 * @property string $postcode
 * @property string $city
 * @property string $email
 * @property string $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $type
 * @property string $info
 * @property integer $attendant Description
 */
class Client extends ActiveRecord
{
    const STATUS_ACTIVE = 'active';
    const STATUS_DELETED = 'deleted';
    const STATUS_LOCKED = 'locked';
    
    const TYPE_CUSTOMER = 'customer';
    const TYPE_COMPANY = 'company';

    private $name;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%client}}';
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
            [['acronym', 'firstname', 'type'], 'required'],
            ['acronym', 'unique', 'message' => 'Klient o takim akronimie już istnieje'],
            ['nip', 'unique', 'message' => 'Klient o takim numerze NIP już istnieje'],
            [['firstname', 'lastname', 'acronym', 'street', 'street_no', 'postcode', 'city', 'type', 'nip', 'info'], 'string'],
            ['attendant', 'integer'],
            ['email', 'email']
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'firstname' => $this->type ? ($this->type == self::TYPE_CUSTOMER ? Yii::t('app', 'Imię') : Yii::t('app', 'Nazwa')) : 'Imię lub nazwa firmy',
            'lastname' => Yii::t('app', 'Nazwisko'),
            'acronym' => Yii::t('app', 'Akronim'),
            'phone' => Yii::t('app', 'Telefon'),
            'nip' => Yii::t('app', 'NIP'),
            'street' => Yii::t('app', 'Ulica'),
            'street_no' => Yii::t('app', 'Numer budynku'),
            'postcode' => Yii::t('app', 'Kod pocztowy'),
            'city' => Yii::t('app', 'Miasto'),
            'status' => Yii::t('app', 'Status'),
            'info' => Yii::t('app', 'Opis'),
            'created_at' => Yii::t('app', 'Utworzony'),
            'created' => Yii::t('app', 'Utworzony'),
            'updated_at' => Yii::t('app', 'Edytowany'),
            'type' => Yii::t('app', 'Typ'),
            'name' => Yii::t('app', 'Nazwa'),
            'attendant' => Yii::t('app', 'Opiekun')
            
        ];
    }
    
    public static function listStatuses(){
        return [
            self::STATUS_ACTIVE => Yii::t('app', 'Aktywny'),
            self::STATUS_DELETED => Yii::t('app', 'Usunięty'),
            self::STATUS_LOCKED => Yii::t('app', 'Zablokowany')
        ];
    }
    
    public static function listTypes(){
        return [
            self::TYPE_COMPANY => Yii::t('app', 'Firma'),
            self::TYPE_CUSTOMER => Yii::t('app', 'Osoba fiz.'),
        ];
    }
    

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByFirstname($firstname)
    {
        return static::findOne(['firstname' => $firstname, 'status' => self::STATUS_ACTIVE]);
    }
    
    public static function findByAcronym($acronym)
    {
        return static::findOne(['acronym' => $acronym, 'status' => self::STATUS_ACTIVE]);
    }


    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

   
    public static function findAllClients()
    {
        $query = (new \yii\db\Query)->select(["CONCAT(firstname, ' ', COALESCE(lastname, ''), ' (', acronym, ')') AS fullname"])
                    ->from(self::tableName())
                    ->where(['!=', 'status', User::STATUS_DELETED])
                    ->indexBy('id')
                    ->column();
        return $query;
    }
    
    public static function getAllClientsNames(){
                $query = (new \yii\db\Query)->select(["CONCAT(firstname, ' ', COALESCE(lastname, '')) AS fullname"])
                    ->from(self::tableName())
                    ->where(['!=', 'status', User::STATUS_DELETED])
                    ->indexBy('id')
                    ->column();
        return $query;
    }

    public function getProjects()
    {
        return $this->hasMany(Project::className(), ['id' => 'client_id']);
    }
    
    public function getName(){
        if (empty($this->firstname) && empty($this->lastname)){
            return 'brak danych';
        }
        $this->setName();
        return $this->name;
    }
    
    public function setName(){
        if ($this->type == self::TYPE_CUSTOMER){
            $this->name = $this->firstname. ' '. $this->lastname;
        } else {
            $this->name = $this->firstname;
        }
    }
    
    public function getCreator(){
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
    
    public function getClientAttendant(){
        return $this->hasOne(User::className(), ['id' => 'attendant']);
    }
}
