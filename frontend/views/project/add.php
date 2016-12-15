<?php
use frontend\models\ProjectForm;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/* @var $projectForm ProjectForm */
/* @var $this View*/

$this->title = 'Dodaj projekt';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'projectForm' => $projectForm,
    'userList'  => $userList,
    'clientsList'    => $clientsList,
    'projectStatus' => $projectStatus
        ])?>

