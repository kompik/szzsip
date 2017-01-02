<?php
use common\models\Project;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use common\models\Order;
use common\models\OrderSearch;
use common\models\User;
use yii\data\ActiveDataProvider;
use kartik\grid\GridView;
use kartik\mpdf\Pdf;

/* @var $project Project */
/* @var $this View*/
/* @var $orders Order[] */
/* @var $dataProvider ActiveDataProvider*/

$this->title = 'Projekt '.$project->name;
$this->params['breadcrumbs'][] = $this->title;
$user = Yii::$app->user->isGuest ? : Yii::$app->user->identity;
$deleteButton = $user->isAdmin() || $user->isSupervisor() ? Html::a(Yii::t('app', '<i class="glyphicon glyphicon-minus"></i> Usuń zaznaczone zlecenia'), Url::to(['add']), ['class' => 'btn btn-danger']) : '';
?>
<div class="col-sm-12">
    <div class="row">
        <?=    Html::a(Yii::t('app', '<i class="glyphicon glyphicon-plus"></i> Dodaj zlecenie do projektu'), ['/order/add', 'project_id' => $project->id], ['class' => 'btn btn-success'])?>
       <hr> 
    </div>
    
    <div class="row">
        <div class="col-sm-12">
            <label class=""><?= $project->getAttributeLabel('name') ?></label>
            <div><?= $project->name ?></div>
            <hr>
        </div>
        
        <div class="col-sm-12">
            <label><?= $project->getAttributeLabel('description') ?></label>
            <div><?= $project->description ?></div>
            <hr>
        </div>
    </div>
    

    <div class="row">
        <div class="col-sm-3">
            <label><?= $project->getAttributeLabel('client_id') ?></label>
            <div><?= Html::a($project->client->acronym, Url::to(['/client/view', 'id' => $project->client_id])) ?></div><hr>
        </div>
        <div class="col-sm-3">
            <label><?= $project->getAttributeLabel('owner_id') ?></label>
            <div><?= Html::a($project->owner->username, Url::to(['/user/view', 'id' => $project->owner_id])) ?></div><hr>
        </div>

        <div class="col-sm-3">
            <label><?= $project->getAttributeLabel('created_at') ?></label>
            <div><?= date('d-m-Y H:i', $project->created_at) ?></div><hr>
        </div>

        <div class="col-sm-3">
            <label><?= $project->getAttributeLabel('status') ?></label>
            <div><?= Project::listStatuses()[$project->status] ?></div><hr>
        </div>
    </div>
    
    <div class="row">
        <?= GridView::widget([
            'panel'=>[
                'type'=>GridView::TYPE_PRIMARY,
                'heading'=>'<i class="glyphicon glyphicon-list"></i> Zlecenia projektu',
            ],'toolbar' => [
                [
                    'content'=>
                        Html::a('<i class="glyphicon glyphicon-plus"></i> Dodaj zlecenie', Url::to(['/order/add', 'id' => $project->id]), ['class' => 'btn btn-success']). ' ' .
                        $deleteButton. ' '.
                        Html::a('<i class="glyphicon glyphicon-repeat"></i> Resetuj widok', ['index'], [
                            'class' => 'btn btn-default', 
                            'title' => Yii::t('app', 'Resetuj widok')
                        ]) 
                ],
                '{export}',
                '{toggleData}'
            ],
//            'pjax'=>true,
            'pjaxSettings'=>[
                'neverTimeout'=>true,
            ],
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns' => [
                [
                    'class' => '\kartik\grid\CheckboxColumn'
                ],
                [
                    'class' => '\kartik\grid\SerialColumn'
                ],
                [
                    'class' => '\kartik\grid\DataColumn',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'data' => $orderNames,
                        'options' => [
                            'placeholder' => 'filtruj po nazwie ...',
                            'initValueText' => ''
                            ],
                        'pluginOptions' => [
                            'allowClear' => true,
//                            'minimumInputLength' => 3,
                        ]
                    ],
                    'attribute' => 'name',
//                    'value' => 'orders.id'
                    'format' => 'raw',
                    'value' => function($model){
                        return Html::a(Yii::t('app', $model->name), Url::to(['/order/view', 'id' => $model->id]), ['data-pjax' => 0, 'title' => $model->name]);
                    }
                ],
                [
                    'class' => '\kartik\grid\DataColumn',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'data' => $userList,
                        'options' => [
                            'placeholder' => 'filtruj po właścicielu ...',
                            'initValueText' => ''
                            ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ]
                    ],
                    'attribute' => 'owner',
                    'format' => 'raw',
                    'value' => function($model){
                        return Html::a($model->owner->username, Url::to(['/user/view', 'id' => $model->owner_id]), ['data-pjax' => 0]);
                    },
                ],
                [
                    'class' => '\kartik\grid\DataColumn',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'data' => $clientsList,
                        'options' => [
                            'placeholder' => 'filtruj po kliencie ...',
                            'initValueText' => ''
                            ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ]
                    ],
                    'attribute' => 'client',
                    'format' => 'raw',
                    'value' => function($model){
                        if ($model->client){
                            return Html::a($model->client->acronym, Url::to(['/client/view', 'id' => $model->client_id]), ['data-pjax' => 0]);
                        }
                        return 'brak';
                    },
                ],
                [
                    'class' => '\kartik\grid\DataColumn',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'data' => Order::listStatuses(),
                        'options' => [
                            'placeholder' => 'filtruj po statusie ...',
                            'initValueText' => ''
                            ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ]
                    ],
                    'attribute' => 'status',
                    'value' => function($model) {
                                    return Order::listStatuses()[$model->status];
                                },
                ],
                [
                    'class' => '\kartik\grid\DataColumn',
                    'filterType' => GridView::FILTER_DATE_RANGE,
                    'attribute' => 'created_at',
                    'filterWidgetOptions' => [
                        'presetDropdown' => true,
                        'pluginOptions' => [
                            'format' => 'YYYY-MM-DD',
                            'separator' => ' TO ',
                        'opens'=>'left',
                        ],
                        'pluginEvents' => [
                            "apply.daterangepicker" => "function() { apply_filter('date') }",
                        ]
                    ],
                    'value' => function ($model) {
                                    return date('d-m-Y H:i', $model->created_at);
                                },
                ],
                [
                    'class' => '\kartik\grid\ActionColumn',
                    'deleteOptions' => ['label' => '<i class="glyphicon glyphicon-remove"></i>'],
                    'viewOptions' => [
                        'title' => 'Pokaż szczegóły'
                    ],
                    'updateOptions' => [
                        'title' => 'Edytuj'
                    ],
                    'deleteOptions' => [
                        'title' => 'Usuń'
                    ],
                ],
            ],
            'showPageSummary' => true,

        ]) ?>
    </div>
</div>
