<?php
    
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\sidenav\SideNav;

$user = Yii::$app->user->isGuest ? : Yii::$app->user->identity;
?>

<div class="nav" id="side-nav">
    <?php
    
    if (Yii::$app->user->isGuest){
        $items = [
            ['label' => 'Strona główna', 'icon' => 'home', 'url' => Url::to(['site/index']),
                'active' => $this->context->id == 'site' && $this->context->action && $this->context->action->id == 'index'],
            ['label' => 'O aplikacji', 'icon' => 'list-alt', 'url' => ['/site/about'],
                'active' => $this->context->id == 'site' && $this->context->action && $this->context->action->id == 'about'],
            ['label' => 'Kontakt', 'icon' => 'envelope', 'url' => ['/site/contact'],
                'active' => $this->context->id == 'site' && $this->context->action && $this->context->action->id == 'contact'],
            ['label' => 'Zaloguj się', 'icon' => 'log-in', 'url' => Url::to(['site/login']),
                'active' => $this->context->id == 'site' && $this->context->action && $this->context->action->id == 'login'],
        ];
        $heading = '<i class="glyphicon glyphicon-tasks"></i> Menu';
    }
    else {
            $items = [
                ['label' => 'Strona główna', 'icon' => 'home', 'url' => Url::to(['/site/index']), 
                    'active' => $this->context->id == 'site' && $this->context->action && $this->context->action->id == 'index'],
                ['label' => 'Projekty', 'icon' => 'briefcase', 'url' => Url::to(['/project/index']),
                    'active' => $this->context->id == 'project'],
                ['label' => 'Zlecenia', 'icon' => 'list', 'url' => Url::to(['/order/index']),
                    'active' => $this->context->id == 'order'],
                ['label' => 'Użytkownicy', 'icon' => 'user', 'url' => Url::to(['/profile/index']),
                    'active' => $this->context->id == 'profile',
                    'visible' => $user->isAdmin() || $user->isSupervisor()],
                ['label' => 'Mój profil', 'icon' => 'edit', 'url' => Url::to(['/profile/edit']),
                    'active' => $this->context->id == 'profile' && $this->context->action && $this->context->action->id == 'edit'],
                ['label' => 'Wyloguj się', 'icon' => 'log-out', 'url' => Url::to(['/site/logout']), 'template' => '<a href="{url}" data-method="post">{icon}{label}</a>',], 
            ];
            $heading = '<i class="glyphicon glyphicon-tasks"></i> Menu (zalogowany: '. Yii::$app->user->identity->username .')';
    }
        echo SideNav::widget([
            'encodeLabels' => false,
            'heading' => $heading,
            'items' => $items
        ]);

        
    ?>
</div>

