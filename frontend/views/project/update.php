<?php
use frontend\models\Project;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/* @var $project Project */
/* @var $this View*/

$this->title = 'Edytuj projekt';
$this->params['breadcrumbs'][] = $this->title;
?>

    <?php $form = ActiveForm::begin([
        'options' => ['id' => 'project-form'],
    ]); ?>
    
    <?= $form->field($project, 'name')->textInput() ?>
    <?= $form->field($project, 'owner_id')->dropDownList($userList, ['prompt'=>'Wybierz właściciela projektu (domyślnie: '.Yii::$app->user->identity->username.')']) ?>
    <?= $form->field($project, 'client_id')->dropDownList($clientsList, ['prompt'=>'Wybierz klienta']) ?>
    <?= $form->field($project, 'status')->dropDownList($projectStatus, ['prompt'=>'Wybierz status (domyślnie nowy)']) ?>
    <?= $form->field($project, 'description')->textArea() ?>

        
    <div class="modal-footer">
        <?= Html::submitButton(Yii::t('app', 'Zapisz'), [
            'class' => 'btn btn-primary col-sm-12'
        ]) ?>
    </div>

        
    <?php $form->end(); ?>


