<?php
use frontend\models\ProjectForm;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $projectForm ProjectForm */

$this->title = 'Dodaj projekt';
$this->params['breadcrumbs'][] = $this->title;
?>
    <?php $form = ActiveForm::begin([
        'options' => ['id' => 'add-project-form'],
    ]); ?>
    
    <?= $form->field($projectForm, 'name')->textInput() ?>
    <?= $form->field($projectForm, 'owner_id')->textInput() ?>
    <?= $form->field($projectForm, 'client_id')->dropDownList($userList, ['prompt'=>'Wybierz klienta']) ?>
    <?= $form->field($projectForm, 'description')->textInput() ?>

        
    <div class="modal-footer">
        <?= Html::submitButton(Yii::t('app', 'Zapisz'), [
            'class' => 'btn btn-primary col-sm-12'
        ]) ?>
    </div>

        
    <?php $form->end(); ?>

