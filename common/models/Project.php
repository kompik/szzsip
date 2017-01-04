<?php //

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use common\models\User;
use common\models\Client;
use yii\db\Expression;

/**
 * Project model
 *
 * @property integer $id
 * @property string $name
 * @property integer $owner_id
 * @property integer $client_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $status
 * @property string $description
 * 

 */

class Project extends ActiveRecord
{
    const STATUS_NEW = 1;
    const STATUS_IN_PROGRESS = 2;
    const STATUS_CLOSED = 3;
    const STATUS_DELETED = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%project}}';
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
            ['status', 'default', 'value' => self::STATUS_NEW],
            ['name', 'required', 'message' => 'Nazwa nie może zostać pusta'],
            [['name', 'description'], 'string'],
            [['status', 'owner_id', 'client_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            ['name', 'unique', 'message' => 'Projekt o takiej nazwie już istnieje']
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Nazwa'),
            'owner_id' => Yii::t('app', 'Właściciel projektu'),
            'owner' => Yii::t('app', 'Właściciel projektu'),
            'client_id' => Yii::t('app', 'Klient'),
            'client' => Yii::t('app', 'Klient'),
            'status' => Yii::t('app', 'Status'),
            'description' => Yii::t('app', 'Opis'),
            'created_at' => Yii::t('app', 'Utworzony'),
            'created' => Yii::t('app', 'Utworzony'),
            'actions' => Yii::t('app', 'Akcje')
        ];
    }
    
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }
    
    public function getOwner()
    {
        return $this->hasOne(User::className(), ['id' => 'owner_id']);
    }
    
    public static function listStatuses()
    {
        return [
            self::STATUS_NEW => Yii::t('app', 'nowy'),
            self::STATUS_IN_PROGRESS => Yii::t('app', 'w realizacji'),
            self::STATUS_CLOSED => Yii::t('app', 'zamknięty'),
            self::STATUS_DELETED => Yii::t('app', 'usunięty'),
        ];
    }
    
    public static function getAllProjectsNames($client_id = null, $owner_id = null)
    {
        $query = (new \yii\db\Query)->select(['name'])
                    ->from(self::tableName())
                    ->where(['!=', 'status', self::STATUS_DELETED])
                    ->indexBy('name')
                    ->orderBy('name');
        if ($client_id){
            $query->andWhere(['client_id' => $owner_id]);
        }
        if ($owner_id){
            $query->andWhere(['owner_id' => $client_id]);
        }
        return $query->column();
    }
    
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['project_id' => 'id']);
    }
    
    public static function getAllProjects()
    {
        $query = (new \yii\db\Query)->select(['name'])
            ->from(self::tableName())
            ->where(['not in', 'status', [self::STATUS_DELETED, self::STATUS_CLOSED]])
            ->indexBy('id')
            ->orderBy('name')
            ->column();
        return $query;
    }
    
    public function getShortName()
    {
       if (strlen($this->name) > 25){
           return substr($this->name, 0, 22).'...';
       }
       return $this->name;
    }
}
