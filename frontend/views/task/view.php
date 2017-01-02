<?php
use common\models\Task;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $task Task */
/* @var $this View*/

$this->title = 'Zadanie '.$task->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-sm-12">
    <div class="row">
        <?=    Html::a(Yii::t('app', '<i class="glyphicon glyphicon-plus"></i> Dodaj zadanie do zlecenia'),
                ['/order/add-task-to-order', 'task_id' => $task->id], ['class' => 'btn btn-success'])?>
       <hr> 
    </div>
    
    <div class="row">
        <div class="col-sm-12">
            <label class=""><?= $task->getAttributeLabel('name') ?></label>
            <div><?= $task->name ?></div>
            <hr>
        </div>    

    <div class="row specs-col-sm-2">
        <div class="col-sm-2">
            <label><?= $task->getAttributeLabel('created_by') ?></label>
            <div><?= Html::a($task->creator->username, Url::to(['/user/view', 'id' => $task->created_by])) ?></div><hr>
        </div>

        <div class="col-sm-2">
            <label><?= $task->getAttributeLabel('created_at') ?></label>
            <div><?= date('d-m-Y H:i', $task->created_at) ?></div><hr>
        </div>

        <div class="col-sm-2">
            <label><?= $task->getAttributeLabel('status') ?></label>
            <div><?= Task::listStatuses()[$task->status] ?></div><hr>
        </div>
    </div>
</div>
