<?php
use frontend\models\OrderForm;
use frontend\models\Order;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $orderForm OrderForm */

?>
    <?php $form = ActiveForm::begin([
        'options' => ['id' => 'order-form'],
    ]); ?>
    
    <?= $form->field($orderForm, 'name')->textInput() ?>
    <?= $form->field($orderForm, 'owner_id')->dropDownList($userList, ['prompt'=>'Wybierz właściciela zlecenia (domyślnie: '.Yii::$app->user->identity->username.')']) ?>
    <?= $form->field($orderForm, 'executive_id')->dropDownList($userList, ['prompt'=>'Wybierz wykonawcę zlecenia (domyślnie: '.Yii::$app->user->identity->username.')']) ?>
    <?= $form->field($orderForm, 'client_id')->dropDownList($clientsList, ['prompt'=>'Wybierz klienta']) ?>
    <?= $form->field($orderForm, 'project_id')->dropDownList($projects, ['prompt'=>'Wybierz projekt']) ?>
    <?= $form->field($orderForm, 'status')->dropDownList($orderStatus, ['prompt'=>'Wybierz status (domyślnie nowy)']) ?>
    <?= $form->field($orderForm, 'description')->textArea() ?>

        
    <div class="modal-footer">
        <?= Html::submitButton(Yii::t('app', 'Zapisz'), [
            'class' => 'btn btn-primary col-sm-12'
        ]) ?>
    </div>

        
    <?php $form->end(); ?>

