<?php
use common\models\User;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/* @var $user User */
/* @var $this View*/

$this->title = 'Edytuj użytkownika';
$this->params['breadcrumbs'][] = $this->title;
?>

    <?php $form = ActiveForm::begin([
        'options' => ['id' => 'user-form'],
    ]); ?>
    
    <?= $form->field($user, 'firstname')->textInput() ?>
    <?= $form->field($user, 'lastname')->textInput() ?>
    <?= $form->field($user, 'username')->textInput(['disabled' => $user->username ? 'disabled' : '']) ?>
    <?= $form->field($user, 'email')->textInput() ?>
    <?= $form->field($user, 'type')
            ->dropDownList(User::listTypes(), [
                'value' => $user->type,
                'prompt' => 'Wybierz typ użytkownika'
                ]) ?>
    <?= $form->field($user, 'group_id')->textarea() ?>

        
    <div class="modal-footer">
        <?= Html::submitButton(Yii::t('app', 'Zapisz'), [
            'class' => 'btn btn-primary col-sm-12'
        ]) ?>
    </div>

        
    <?php $form->end(); ?>


