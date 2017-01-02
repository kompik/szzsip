<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use common\models\User;
use common\models\OrderTask;

/**
 * This is the model class for table "task".
 *
 * @property integer $id
 * @property string $name
 * @property string $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * 
 */
class Task extends ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'string'],
            [['created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name'], 'string', 'max' => 100],
            ['name', 'unique', 'message' => 'Zadanie o takiej nazwie już istnieje']
        ];
    }
    
    public static function listStatuses()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('app', 'aktywne'),
            self::STATUS_DELETED => Yii::t('app', 'usunięte'),
        ];
    }
    
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
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Nazwa zadania',
            'task' => 'Nazwa zadania',
            'status' => 'Status',
            'created_at' => 'Utworzony',
            'created' => 'Utworzony',
            'updated_at' => 'Edytowany',
            'created_by' => 'Utworzył',
            'creator' => 'Utworzył',
            'updated_at' => 'Edytował',
            
        ];
    }
    
    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
    
    public static function getAllTasksNames($id = null)
    {
        $query = new \yii\db\Query();
        if ($id){
            $query->select(['t.name'])->from(OrderTask::tableName().' ot')->leftJoin(self::tableName().' t', 'ot.task_id = t.id')
                    ->where(['order_id' => $id])->indexBy('name'); 
        }
        else {
            $query->select(['name'])
            ->from(self::tableName())
//                    ->where(['!=', 'status', self::STATUS_DELETED])
            ->indexBy('id');
        
        }
        return $query->column();
    }
    
    public function getShortName()
    {
       if (strlen($this->name) > 25){
           return substr($this->name, 0, 22).'...';
       }
       return $this->name;
    }
    
    public function getOrderTasks()
    {
        return $this->hasMany(OrderTask::className(), ['task_id' => 'id']);
    }
    
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['id' => 'order_id'])
                ->via('orderTasks');
    }
    
    
}
