<?php
use frontend\models\order;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/* @var $order Order */
/* @var $this View*/

$this->title = 'Edytuj zlecenie';
$this->params['breadcrumbs'][] = $this->title;
?>

    <?php $form = ActiveForm::begin([
        'options' => ['id' => 'order-form'],
    ]); ?>
    
    <?= $form->field($order, 'name')->textInput() ?>
    <?= $form->field($order, 'owner_id')->dropDownList($userList, ['prompt'=>'Wybierz właściciela zlecenia (domyślnie: '.Yii::$app->user->identity->username.')']) ?>
    <?= $form->field($order, 'executive_id')->dropDownList($userList, ['prompt'=>'Wybierz wykonawcę zlecenia (domyślnie: '.Yii::$app->user->identity->username.')']) ?>
    <?= $form->field($order, 'client_id')->dropDownList($clientsList, ['prompt'=>'Wybierz klienta']) ?>
    <?= $form->field($order, 'project_id')->dropDownList($projects, ['prompt'=>'Wybierz projekt']) ?>
    <?= $form->field($order, 'status')->dropDownList($orderStatus, ['prompt'=>'Wybierz status (domyślnie nowy)']) ?>
    <?= $form->field($order, 'description')->textArea() ?>

        
    <div class="modal-footer">
        <?= Html::submitButton(Yii::t('app', 'Zapisz'), [
            'class' => 'btn btn-primary col-sm-12'
        ]) ?>
    </div>

        
    <?php $form->end(); ?>


