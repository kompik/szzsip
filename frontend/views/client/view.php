<?php
use common\models\Order;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use common\models\Client;
use yii\data\ActiveDataProvider;
use kartik\grid\GridView;
use common\models\User;
use common\helpers\TimeHelper;

/* @var $client Client */
/* @var $this View*/

$this->title = 'Klient '.$client->name;
$this->params['breadcrumbs'][] = $this->title;
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
                            ['add-attendant', 'id' => $client->id], 
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
            <div><?= Client::listStatuses()[$client->status] ?></div><hr>
        </div>
    </div>
</div>
