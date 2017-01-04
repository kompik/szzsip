<?php

use common\models\User;
use common\models\Client;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/* @var $client Client*/
/* @var $this View */
/* @var $property string*/
$form = new ActiveForm();

switch ($property){
    case 'attendant':
        $header = 'Dodaj opiekuna dla klienta '.$client->name;
        $field = $form->field($client, 'attendant')->dropDownList($usersList);
        break;
    case 'email':
        $header = 'Dodaj e-mail dla klienta '.$client->name;
        $field = $form->field($client, 'email')->textInput();
        break;
    case 'status':
        $header = 'Zmień status klienta '.$client->name;
        $field = $form->field($client, 'status')->dropDownList(Client::listStatuses());
        break;        
}
?> 
    <?php $form->begin([
        'options' => ['id' => 'client-form'],
    ]); ?>
<div class="modal-header">
    <h3><?= $header ?></h3>
</div>
<div class="col-md-12 ">
    <?= $field ?>
</div>
 
    <div class="modal-footer">
        <?= Html::submitButton(Yii::t('app', 'Zapisz'), [
            'class' => 'btn btn-primary col-sm-12'
        ]) ?>
    </div>

        
    <?php $form->end(); ?>