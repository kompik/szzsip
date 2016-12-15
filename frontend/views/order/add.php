<?php
use frontend\models\OrderForm;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/* @var $orderForm OrderForm */
/* @var $this View*/

$this->title = 'Dodaj projekt';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'orderForm' => $orderForm,
    'userList'  => $userList,
    'clientsList'    => $clientsList,
    'orderStatus' => $orderStatus,
    'projects' => $projects
        ])?>

