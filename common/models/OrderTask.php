<?php

namespace common\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use common\models\User;
use common\models\Task;
use common\models\Order;
use common\models\StartStop;

/**
 * This is the model class for table "task".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $task_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $locked
 * 
 */

class OrderTask extends ActiveRecord
{
    const LOCKED = 1;
    const UNLOCKED = 0;
    const PAUSED = 2;
    
    private $time;

    /**
    * @inheritdoc
    */
    public static function tableName()
    {
        return 'order_task';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['status'], 'string'],
            [['created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
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
//            'status' => 'Status',
            'created_at' => 'Utworzony',
            'created' => 'Utworzony',
            'updated_at' => 'Edytowany',
            'created_by' => 'Utworzył',
            'creator' => 'Utworzył',
            'updated_at' => 'Edytował'
        ];
    }
    
    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
    
    public function getWorker()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }
    
    public function getTask()
    {
        return $this->hasOne(Task::className(), ['id' => 'task_id']);
    }
    
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
    
    public function getStartStops()
    {
        return $this->hasMany(StartStop::className(), ['order_task_id' => 'id']);
    }
    
    public function setTime($time){
        $this->time = $time;
    }
    
    public function getTime(){
        if (!$this->startStops){
            return 0;
        }
        $this->setTime($this->CountTimeForOrderTask());
        return $this->time;
    }

        public function Lock()
    {
        if ($this->locked == self::UNLOCKED || $this->locked == self::PAUSED){
            $this->locked = self::LOCKED;
            return $this->save();
        }
        return FALSE;
    }
    
    public function Unlock()
    {
        if ($this->locked == self::LOCKED || $this->locked == self::PAUSED){
            $this->locked = self::UNLOCKED;
            return $this->save();
        }
        return FALSE;
    }
    
    public function Pause()
    {
        if ($this->locked == self::LOCKED){
            $this->locked = self::PAUSED;
            return $this->save();
        }
        return FALSE;
    }
    
    public static function Template()
    {
        return '{work}{view}{update}{delete}';
    }
    
    public static function WorkButton($url, $model)
    {
        $currentUser = Yii::$app->user->identity->id == $model->updated_by;
        if (($model->locked == OrderTask::LOCKED || $model->locked == OrderTask::PAUSED) && $currentUser){
            if ($model->locked == OrderTask::LOCKED){
                $button = '<span class="glyphicon glyphicon-pause"></span> ';
                $title = 'Pauza';
            } else {
                $button = '<span class="glyphicon glyphicon-play"></span> ';
                $title = 'Kontynuuj pracę z zadaniem';
            }
        }
        elseif ($model->locked == OrderTask::LOCKED || $model->locked == OrderTask::PAUSED) {
            $button = '<span class="glyphicon glyphicon-hourglass"></span> ';
            $title = 'Obecnie nad tym zadaniem pracuje '. $model->worker->username;
        }
        else {
            $button = '<span class="glyphicon glyphicon-play"></span> ';
            $title = 'Zacznij pracę z zadaniem';
        }
        return Html::a($button, $url, ['title' => Yii::t('app', $title)]).
                Html::a('<span class="glyphicon glyphicon-stop"></span> ',
                        ['task/stop-work', 'id' => $model->id],
                        [
                            'title' => Yii::t('app', 'Stop'),
                            'style' => $model->locked && $currentUser ? : 'display: none'
                        ]);
    }
    
    public function CountTimeForOrderTask()
    {
        $sS = $this->startStops;
        $count = count($sS);
        $time = 0;
        if ($count == 1){
            $time += time() - $sS[0]->created_at;
        }
        if ($count >= 2){
            for ($i=0; $i < $count-1; $i++){
                if (in_array($sS[$i]->type, [StartStop::TYPE_START, StartStop::TYPE_RESUME])){
                    $time += $sS[$i+1]->created_at - $sS[$i]->created_at;
                }
            }
            if (in_array($sS[$count-1]->type, [StartStop::TYPE_START, StartStop::TYPE_RESUME])){
                $time += time() - $sS[$count-1]->created_at;
            }
        }
        return $time;
    }
}

