<?php
use common\models\Task;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/* @var $task Task */
/* @var $this View*/

$this->title = 'Edytuj zadanie';
$this->params['breadcrumbs'][] = $this->title;
?>

    <?php $form = ActiveForm::begin([
        'options' => ['id' => 'task-form'],
    ]); ?>
    
    <?= $form->field($task, 'name')->textInput() ?>
    <?= $form->field($task, 'status')->hiddenInput(['value' => $task->status])->label(false) ?>

        
    <div class="modal-footer">
        <?= Html::submitButton(Yii::t('app', 'Zapisz'), [
            'class' => 'btn btn-primary col-sm-12'
        ]) ?>
    </div>

        
    <?php $form->end(); ?>


