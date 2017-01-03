<?php
use common\models\Order;
use common\models\Task;
use frontend\models\Task2OrderForm;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\web\View;
use kartik\select2\Select2;

/* @var $order Order */
/* @var $task2order Task2OrderForm */
/* @var $this View*/

$this->title = $order->id ? 'Dodaj zadania do zlecenia' : 'Dodaj zadanie do zlecenia';
$this->params['breadcrumbs'][] = $this->title;
?>

    <?php $form = ActiveForm::begin([
        'options' => ['id' => 'task-2-order-form'],
    ]); ?>
    <label class=""><?= $order->id ? $order->getAttributeLabel('name') : $task->getAttributeLabel('name')?></label>
    <div><?= $order->id ? $order->name : $task->name ?></div>
    <?= $form->field($task2order, $order->id ? 'order_id' : 'task_ids')->hiddenInput(['value' => $order->id ? $order->id : $task->id])->label(false) ?>
    <?= $form->field($task2order, $order->id ? 'task_ids' : 'order_id')->widget(Select2::className(), [
        'data' =>  $order->id ? $tasksList : $ordersList,
        'options' => [
            'placeholder' => $order->id ? 'Wybierz zadania' : 'Wybierz zlecenie',
            'multiple' => $order->id ? true : false,
            'initValueText' => ''
            ],
        'pluginOptions' => [
            'allowClear' => true,
//                            'minimumInputLength' => 3,
        ]
    ]) ?>

        
    <div class="modal-footer">
        <?= Html::submitButton(Yii::t('app', 'Zapisz'), [
            'class' => 'btn btn-primary col-sm-12'
        ]) ?>
    </div>

        
    <?php $form->end(); ?>
