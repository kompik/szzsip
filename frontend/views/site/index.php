<?php

/* @var $this yii\web\View */

$this->title = 'SZZSIP Marcin Pikul';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>SZZSIP</h1>

        <p class="lead">System Zarządzania Zleceniami Serwisowymi i Programistycznymi</p>

    
    </div>
    <?php if (Yii::$app->user->isGuest) : ?>
    <div class="body-content">

        <div class="info">
            <i class="glyphicon glyphicon-arrow-left"></i>
            <i class="glyphicon glyphicon-minus"></i>
            <i class="glyphicon glyphicon-minus"></i>
            <i class="glyphicon glyphicon-minus"></i>
            <i class="glyphicon glyphicon-minus"></i>
            <i class="glyphicon glyphicon-log-in"></i> 
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Zaloguj się, aby skorzystać z systemu!
        </div>

    </div>
    <?php    endif;?>
</div>
