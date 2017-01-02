<?php

namespace frontend\models;

use common\models\OrderTask;
use yii\base\Model;
use Yii;

class Task2OrderForm extends Model {
    
    public $order_id;
    public $task_ids;
    
    public function rules() {
        return [
            [['order_id'], 'integer'],
            ['task_ids', 'each', 'rule' => ['integer'], 'when' => function ($model) {
                return is_array($model->task_ids);
            }
            ]
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'order_id' => Yii::t('app', 'Zlecenie'),
            'task_ids' => Yii::t('app', 'Zadania'),
            'task_id' => Yii::t('app', 'Zadanie'),
        ];
    }
    
    public function addTask2Order()
    {
        $orderTask = new OrderTask();
        
        $orderTask->order_id = $this->order_id;
        if (is_array($this->task_ids)){
            foreach ($this->task_ids as $task_id){
                $orderTask->id = NULL;
                $orderTask->isNewRecord = TRUE;
                $orderTask->task_id = $task_id;
                $orderTask->save();
            }
            return true;
        }
        $orderTask->task_id = $this->task_ids;
        $orderTask->save();
        return true;
    }
    
}

