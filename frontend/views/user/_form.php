<?php
use common\models\Client;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $client Client */

?>
    <?php $form = ActiveForm::begin([
        'options' => ['id' => 'client-form'],
    ]); ?>
    
    <?= $form->field($client, 'firstname')->textInput() ?>
    <?= $form->field($client, 'lastname')->textInput() ?>
    <?= $form->field($client, 'acronym')->textInput() ?>
    <?= $form->field($client, 'nip')->textInput() ?>
    <?= $form->field($client, 'street')->textInput() ?>
    <?= $form->field($client, 'street_no')->textInput() ?>
    <?= $form->field($client, 'postcode')->textInput() ?>
    <?= $form->field($client, 'city')->textInput() ?>
    <?= $form->field($client, 'email')->textInput() ?>
    <?= $form->field($client, 'type')->dropDownList(Client::listTypes()) ?>
    <?= $form->field($client, 'info')->textarea() ?>

        
    <div class="modal-footer">
        <?= Html::submitButton(Yii::t('app', 'Zapisz'), [
            'class' => 'btn btn-primary col-sm-12'
        ]) ?>
    </div>

        
    <?php $form->end(); ?>

