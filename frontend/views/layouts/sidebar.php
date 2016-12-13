<?php
    
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\sidenav\SideNav;

?>

<div class="nav" id="side-nav">
    <?php
    if (Yii::$app->user->isGuest){
        echo SideNav::widget([
            'encodeLabels' => false,
            'heading' => '<i class="glyphicon glyphicon-tasks"></i> Menu',
            'items' => [
                ['label' => 'Strona główna', 'icon' => 'home', 'url' => Url::to(['site/index'])],
                ['label' => 'O aplikacji', 'icon' => 'list-alt', 'url' => ['/site/about']],
                ['label' => 'Kontakt', 'icon' => 'envelope', 'url' => ['/site/contact']],
                ['label' => 'Zaloguj się', 'icon' => 'log-in', 'url' => Url::to(['site/login'])],
               
            ]
        ]);
    }
    else {
        echo SideNav::widget([
            'encodeLabels' => false,
            'id' => 'user-navbar',
            'options' => [
                'id' => 'user-navbar',
            ],
            'heading' => '<i class="glyphicon glyphicon-tasks"></i> Menu (zalogowany: '.Yii::$app->user->identity->username.')',
            'items' => [
                ['label' => 'Strona główna', 'icon' => 'home', 'url' => Url::to(['site/index']), 'active' => Yii::$app->user->isGuest],
                ['label' => 'Projekty', 'icon' => 'briefcase', 'url' => Url::to(['projects/index'])],
                ['label' => 'Zlecenia', 'icon' => 'list', 'url' => Url::to(['projects/index'])],
                ['label' => 'Użytkownicy', 'icon' => 'user', 'url' => Url::to(['profiles/index'])],
                ['label' => 'Mój profil', 'icon' => 'edit', 'url' => Url::to(['profiles/edit'])],
                ['label' => 'Wyloguj się', 'icon' => 'log-out', 'url' => Url::to(['/site/logout']), 'template' => '<a href="{url}" data-method="post">{icon}{label}</a>',],
               
            ]
        ]);
    }
        
    ?>
</div>

