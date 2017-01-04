<?php

use common\models\Client;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="col-sm-12">
    <div class="row">
       <hr> 
    </div>
    <div class="row">
        <div class="col-sm-12">
            <label class=""><?= $client->getAttributeLabel('name') ?></label>
            <div><?= $client->name ?></div>
            <hr>
        </div>
        
        <div class="col-sm-12">
            <label><?= $client->getAttributeLabel('info') ?></label>
            <div><?= $client->info ?></div>
            <hr>
        </div>
        

    </div>
    <div class="row">
        <div class="col-sm-6">
            <label><?= $client->getAttributeLabel('phone') ?></label>
            <div><?= $client->phone ?></div>
        </div>
        <div class="col-sm-6">
            <label><?= $client->getAttributeLabel('email') ?></label>
            <div><?= $client->email ? 
                    Html::mailto(Yii::t('app', $client->email), $client->email, []) : 
                    Html::a(Yii::t('app', 'Dodaj e-mail'), 
                            Url::to(['add-property', 'id' => $client->id, 'property' => 'email']), 
                            ['id' => 'add-email'])    ?>
            </div>
        </div>
    </div>
    <hr>
    <div class="row specs-col-sm-2">
        <div class="col-sm-2">
            <label><?= $client->getAttributeLabel('acronym') ?></label>
            <div><?= $client->acronym ?></div><hr>
        </div>
        <div class="col-sm-2">
            <label><?= $client->getAttributeLabel('type') ?></label>
            <div><?= Client::listTypes()[$client->type] ?></div><hr>
        </div>
        <div class="col-sm-2">
            <label><?= $client->getAttributeLabel('attendant') ?></label>
            <div><?= $client->attendant ? 
                    $client->clientAttendant->username : 
                    Html::a('dodaj opiekuna', 
                            ['add-property', 'id' => $client->id, 'property' => 'attendant'], 
                            [
                                'id' => 'add-attendant',
                                'title' => 'Dodaj opiekuna dla klienta '.$client->acronym
                            ])?>
            </div>
            <hr>
        </div>

        <div class="col-sm-2">
            <label><?= $client->getAttributeLabel('created_at') ?></label>
            <div><?= date('d-m-Y H:i', $client->created_at) ?></div><hr>
        </div>

        <div class="col-sm-2">
            <label><?= $client->getAttributeLabel('status') ?></label>
            <div><?= Client::listStatuses()[$client->status].' '. 
                    Html::a(Yii::t('app', '<i class="glyphicon glyphicon-refresh"></i>'),
                            Url::to(['add-property', 'id' => $client->id, 'property' => 'status']),
                            [
                                'id' => 'change-status',
                                'title' => 'Zmień status'
                                ]) ?>
            </div><hr>
        </div>
    </div>
</div>

