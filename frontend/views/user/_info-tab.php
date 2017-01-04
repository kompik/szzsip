<?php

use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $user User */

?>
<div class="col-sm-12">
    <div class="row">
       <hr> 
    </div>
    <div class="row">
        <div class="col-sm-12">
            <label class=""><?= $user->getAttributeLabel('name') ?></label>
            <div><?= $user->name ?></div>
            <hr>
        </div>
        
   </div>
    <div class="row">
        <div class="col-sm-6">
            <label><?= $user->getAttributeLabel('phone') ?></label>
            <div><?= $user->phone ? $user->phone : Html::a(Yii::t('app', 'dodaj telefon'), ['add-property', 'id'=> $user->id, 'property' => 'phone'])?></div>
        </div>
        <div class="col-sm-6">
            <label><?= $user->getAttributeLabel('email') ?></label>
            <div><?= $user->email ? 
                    Html::mailto(Yii::t('app', $user->email), $user->email, []) : 
                    Html::a(Yii::t('app', 'Dodaj e-mail'), 
                            Url::to(['add-property', 'id' => $user->id, 'property' => 'email']), 
                            ['id' => 'add-email'])    ?>
            </div>
        </div>
    </div>
    <hr>
    <div class="row specs-col-sm-2">
        <div class="col-sm-2">
            <label><?= $user->getAttributeLabel('username') ?></label>
            <div><?= $user->username ?></div><hr>
        </div>
        <div class="col-sm-2">
            <label><?= $user->getAttributeLabel('type') ?></label>
            <div><?= $user::listTypes()[$user->type] ?></div><hr>
        </div>
        <div class="col-sm-2">
            <label><?= $user->getAttributeLabel('created_at') ?></label>
            <div><?= date('d-m-Y H:i', $user->created_at) ?></div><hr>
        </div>

        <div class="col-sm-2">
            <label><?= $user->getAttributeLabel('status') ?></label>
            <div><?= User::listStatuses()[$user->status].' '. 
                    Html::a(Yii::t('app', '<i class="glyphicon glyphicon-refresh"></i>'),
                            Url::to(['add-property', 'id' => $user->id, 'property' => 'status']),
                            [
                                'id' => 'change-status',
                                'title' => 'Zmień status'
                                ]) ?>
            </div>
            <hr>
        </div>
    </div>
</div>

