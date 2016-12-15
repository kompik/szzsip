<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\models\User;
use common\models\Client;
use common\models\Project;
use yii\db\Expression;

/**
 * Order model
 *
 * @property integer $id
 * @property string $name
 * @property integer $owner_id
 * @property integer $executive_id
 * @property integer $client_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 * @property integer $project_id
 * @property string $description
 */

class Order extends Project
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order}}';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            [['executive_id', 'project_id'], 'integer']
        ];
        return array_merge($rules, parent::rules());
    }
    
    public function attributeLabels()
    {
        return [
            'owner_id' => Yii::t('app', 'Właściciel zlecenia'),
            'executive_id' => Yii::t('app', 'Wykonawca'),
            'name' => Yii::t('app', 'Nazwa'),
            'client_id' => Yii::t('app', 'Klient'),
            'status' => Yii::t('app', 'Status'),
            'description' => Yii::t('app', 'Opis'),
            'created_at' => Yii::t('app', 'Utworzony'),
            'project_id' => Yii::t('app', 'Projekt')
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
    
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }
    
    public static function getAllOrdersNames()
    {
        $query = (new \yii\db\Query)->select(['name'])
                    ->from(self::tableName())
                    ->where(['!=', 'status', self::STATUS_DELETED])
                    ->indexBy('name')
                    ->orderBy('name')
                    ->column();
        return $query;
    }
    
}
