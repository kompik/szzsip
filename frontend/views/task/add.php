<?php
use frontend\models\Task;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/* @var $task Task */
/* @var $this View*/

$this->title = 'Dodaj zadanie';
$this->params['breadcrumbs'][] = $this->title;
?>

    <?php $form = ActiveForm::begin([
        'options' => ['id' => 'task-form'],
    ]); ?>
    
    <?= $form->field($task, 'name')->textInput() ?>
        
    <div class="modal-footer">
        <?= Html::submitButton(Yii::t('app', 'Zapisz'), [
            'class' => 'btn btn-primary col-sm-12'
        ]) ?>
    </div>

        
    <?php $form->end(); ?>

