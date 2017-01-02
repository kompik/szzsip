<?php

namespace common\models;

use Yii;
use common\models\OrderTask;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "start_stop".
 *
 * @property integer $id
 * @property integer $order_task_id
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 * @property integer $type
 */
class StartStop extends \yii\db\ActiveRecord
{
    const TYPE_STOP = 0;
    const TYPE_START = 1;
    const TYPE_PAUSE = 2;
    const TYPE_RESUME = 3;
    const TYPE_CLOSE = 4;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'start_stop';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['id'], 'required'],
            [['id', 'order_task_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'type'], 'integer'],
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
            'order_task_id' => 'Order Task ID',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'type' => 'Type',
        ];
    }
    
    public function getOrderTasks()
    {
        return $this->hasMany(OrderTask::className(), ['id' => 'order_task_id']);
    }
    
}
