<?php

use common\models\User;
use common\models\Client;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/* @var $client Client*/
?> 
    <?php $form = ActiveForm::begin([
        'options' => ['id' => 'client-form'],
    ]); ?>
<div class="modal-header">
    <h3>Dodaj opiekuna dla klienta <?= $client->name?></h3>
</div>
<div class="col-md-12 ">
    <?= $form->field($client, 'attendant')->dropDownList($usersList) ?>
</div>
 
    <div class="modal-footer">
        <?= Html::submitButton(Yii::t('app', 'Zapisz'), [
            'class' => 'btn btn-primary col-sm-12'
        ]) ?>
    </div>

        
    <?php $form->end(); ?>