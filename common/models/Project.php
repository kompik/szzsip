<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * User model
 *
 * @property integer $id
 * @property string $name
 * @property integer $owner_id
 * @property integer $client_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $status
 * @property string $description
 */

class Project extends ActiveRecord
{
    const STATUS_NEW = 'new';
    const STATUS_IN_PROGRESS = 'in progress';
    const STATUS_CLOSED = 'closed';

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
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_NEW],
            ['name', 'string']
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Nazwa'),
            'owner_id' => Yii::t('app', 'Właściciel projektu'),
            'client_id' => Yii::t('app', 'Klient'),
            'status' => Yii::t('app', 'Status'),
            'description' => Yii::t('app', 'Opis'),
        ];
    }
    
    public function getClient()
    {
        return $this->hasOne(User::className(), ['id' => 'client_id']);
    }
    
    public function getOwner()
    {
        return $this->hasOne(User::className(), ['id' => 'owner_id']);
    }
    
    
}
