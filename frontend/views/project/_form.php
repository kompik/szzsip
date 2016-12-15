<?php
use frontend\models\ProjectForm;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $projectForm ProjectForm */

?>
    <?php $form = ActiveForm::begin([
        'options' => ['id' => 'project-form'],
    ]); ?>
    
    <?= $form->field($projectForm, 'name')->textInput() ?>
    <?= $form->field($projectForm, 'owner_id')->dropDownList($userList, ['prompt'=>'Wybierz właściciela projektu (domyślnie: '.Yii::$app->user->identity->username.')']) ?>
    <?= $form->field($projectForm, 'client_id')->dropDownList($clientsList, ['prompt'=>'Wybierz klienta']) ?>
    <?= $form->field($projectForm, 'status')->dropDownList($projectStatus, ['prompt'=>'Wybierz status (domyślnie nowy)']) ?>
    <?= $form->field($projectForm, 'description')->textArea() ?>

        
    <div class="modal-footer">
        <?= Html::submitButton(Yii::t('app', 'Zapisz'), [
            'class' => 'btn btn-primary col-sm-12'
        ]) ?>
    </div>

        
    <?php $form->end(); ?>

