<?php
use common\models\Client;
use yii\web\View;


/* @var $this View*/
/* @var $client Client*/

$this->title = 'Dodaj klienta';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
        'client' => $client
        ])?>

