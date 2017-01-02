<?php
use common\models\Order;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use common\models\Task;
use common\models\OrderTask;
use common\models\TaskSearch;
use yii\data\ActiveDataProvider;
use kartik\grid\GridView;
use common\models\User;
use common\helpers\TimeHelper;

/* @var $order Order */
/* @var $this View*/
/* @var $dataProvider ActiveDataProvider*/
/* @var $user User */

$this->title = 'Zlecenie '.$order->name;
$this->params['breadcrumbs'][] = $this->title;
$deleteButton = $user->isAdmin() || $user->isSupervisor() ? 
        Html::a(Yii::t('app', '<i class="glyphicon glyphicon-minus"></i> Usuń zaznaczone zadania'), 
                Url::to(['view?id='.$order->id.'#']), 
                ['class' => 'btn btn-danger']) : '';
$addToTaskButton = Html::a(Yii::t('app', '<i class="glyphicon glyphicon-plus"></i> Dodaj zadania do zlecenia'), 
                            Url::to(['add-task-to-order', 'order_id' => $order->id]), ['class' => 'btn btn-success']);
?>
<div class="col-sm-12">
    <div class="row">
       <hr> 
    </div>
    <div class="row">
        <div class="col-sm-12">
            <label class=""><?= $order->getAttributeLabel('name') ?></label>
            <div><?= $order->name ?></div>
            <hr>
        </div>
        
        <div class="col-sm-12">
            <label><?= $order->getAttributeLabel('description') ?></label>
            <div><?= $order->description ?></div>
            <hr>
        </div>
    </div>
    

    <div class="row specs-col-sm-2">
        <div class="col-sm-2">
            <label><?= $order->getAttributeLabel('client_id') ?></label>
            <div><?= $order->client ? Html::a($order->client->acronym, Url::to(['/client/view', 'id' => $order->client_id])) : 'brak' ?></div><hr>
        </div>
        <div class="col-sm-2">
            <label><?= $order->getAttributeLabel('project_id') ?></label>
            <div><?= Html::a($order->project->getShortName(), Url::to(['/project/view', 'id' => $order->project_id])) ?></div><hr>
        </div>
        <div class="col-sm-2">
            <label><?= $order->getAttributeLabel('owner_id') ?></label>
            <div><?= Html::a($order->owner->username, Url::to(['/user/view', 'id' => $order->owner_id])) ?></div><hr>
        </div>

        <div class="col-sm-2">
            <label><?= $order->getAttributeLabel('created_at') ?></label>
            <div><?= date('d-m-Y H:i', $order->created_at) ?></div><hr>
        </div>

        <div class="col-sm-2">
            <label><?= $order->getAttributeLabel('status') ?></label>
            <div><?= Order::listStatuses()[$order->status] ?></div><hr>
        </div>
    </div>
    
    <div class="row">
        
        <?= GridView::widget([
            'panel'=>[
                'type'=>GridView::TYPE_PRIMARY,
                'heading'=>'<i class="glyphicon glyphicon-th"></i> Zadania',
            ],'toolbar' => [
                [
                    'content'=>
                        $addToTaskButton. ' '.
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
                        'data' => $tasksNames,
                        'options' => [
                            'placeholder' => 'filtruj po nazwie ...',
                            'initValueText' => ''
                            ],
                        'pluginOptions' => [
                            'allowClear' => true,
//                            'minimumInputLength' => 3,
                        ]
                    ],
                    'attribute' => 'task',
                    'format' => 'raw',
                    'value' => function($model){
                        return Html::a($model->task->shortName, Url::to(['/task/view', 'id' => $model->task->id]), ['data-pjax' => 0, 'title' => $model->task->id]);
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
                    'attribute' => 'creator',
                    'format' => 'raw',
                    'value' => function($model){
                        return Html::a($model->creator->username, Url::to(['/user/view', 'id' => $model->created_by]), ['data-pjax' => 0]);
                    },
                ],
//                [
//                    'class' => '\kartik\grid\DataColumn',
//                    'filterType' => GridView::FILTER_SELECT2,
//                    'filterWidgetOptions' => [
//                        'data' => Task::listStatuses(),
//                        'options' => [
//                            'placeholder' => 'filtruj po statusie ...',
//                            'initValueText' => ''
//                            ],
//                        'pluginOptions' => [
//                            'allowClear' => true
//                        ]
//                    ],
//                    'attribute' => 'status',
//                    'value' => function($model) {
//                                    return Task::listStatuses()[$model->status];
//                                },
//                ],
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
                    'class' => '\kartik\grid\DataColumn',
//                    'filterType' => GridView::FILTER_SELECT2,
//                    'filterWidgetOptions' => [
//                        'data' => Task::listStatuses(),
//                        'options' => [
//                            'placeholder' => 'filtruj po statusie ...',
//                            'initValueText' => ''
//                            ],
//                        'pluginOptions' => [
//                            'allowClear' => true
//                        ]
//                    ],
                    'attribute' => 'time',
                    'value' => function ($model) {
                                    return TimeHelper::HourMinSec($model->time);
                                },
                ],
                [
                    'class' => '\kartik\grid\ActionColumn',
                    'header' => 'Akcje',
                    'template' => OrderTask::Template(),
                    'buttons' => [
                        'work' => function ($url, $model) {
                                    return OrderTask::WorkButton($url, $model);
                        },
                        'view' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span> ', $url, [
                                        'title' => Yii::t('app', 'Pokaż szczegóły'),
                            ]);
                        },
                        'update' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span> ', $url, [
                                        'title' => Yii::t('app', 'Edytuj'),
                            ]);
                        },
                        'delete' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                        'title' => Yii::t('app', 'Usuń'),
                            ]);
                        }
                    ],
                    'urlCreator' => function ($action, $model, $key, $index) {
                        if ($action === 'work') {
                            if ($model->locked == OrderTask::LOCKED){
                                if ($model->updated_by == Yii::$app->user->identity->id){
                                    $url = Url::to(['task/pause-work', 'id' => $model->id]);
                                }
                                else {
                                    $url = Url::to(['view?id='.$model->order_id.'#']);
                                }
                            }
                            else {
                                $url = Url::to(['task/start-work', 'id' => $model->id]);
                            }
                            return $url;
                        }
                        if ($action === 'view') {
                            $url = Url::to(['task/view', 'id' => $model->task->id]);
                            return $url;
                        }
                        if ($action === 'update') {
                            $url = Url::to(['task/update', 'id' => $model->task->id]);
                            return $url;
                        }
                        if ($action === 'delete') {
                            $url = Url::to(['order/remove-task', 'id' => $model->id]);
                            return $url;
                        }
                    }

                ],
            ],
            'showPageSummary' => true,

        ]) ?>
</div>
</div>
