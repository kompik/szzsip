<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use common\models\Project;
use common\models\User;
use yii\data\ActiveDataProvider;
use kartik\grid\GridView;
use kartik\mpdf\Pdf;

/* @var $this View */
/* @var $projects Project[] */
/* @var $dataProvider ActiveDataProvider*/

$this->title = 'Projekty';
$this->params['breadcrumbs'][] = $this->title;
$user = Yii::$app->user->isGuest ? : Yii::$app->user->identity;
$deleteButton = $user->isAdmin() || $user->isSupervisor() ? Html::a(Yii::t('app', '<i class="glyphicon glyphicon-minus"></i> Usuń zaznaczone projekty'), Url::to(['add']), ['class' => 'btn btn-danger']) : '';

?>

<div class="site-index">
        
        <?= GridView::widget([
            'panel'=>[
                'type'=>GridView::TYPE_PRIMARY,
                'heading'=>'<i class="glyphicon glyphicon-briefcase"></i> Projekty',
            ],
            'showPageSummary' => true,
            'toolbar' => [
                [
                    'content'=>
                        Html::a('<i class="glyphicon glyphicon-plus"></i> Dodaj projekt', Url::to(['add']), ['class' => 'btn btn-success']) . ' ' .
                        $deleteButton . ' '.
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
                        'data' => $projectsNames,
                        'options' => [
                            'placeholder' => 'filtruj po nazwie ...',
                            'initValueText' => ''
                            ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumInputLength' => 3,
                        ]
                    ],
                    'attribute' => 'name',
                    'format' => 'raw',
                    'value' => function($model){
                        return Html::a($model->getShortName(), Url::to(['view', 'id' => $model->id]), ['data-pjax' => 0, 'title' => $model->name]);
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
                        return $model->client ? Html::a($model->client->acronym, Url::to(['/client/view', 'id' => $model->client_id]), ['data-pjax' => 0]) : 'brak';
                    },
                ],
                [
                    'class' => '\kartik\grid\DataColumn',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'data' => Project::listStatuses(),
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
                                    return Project::listStatuses()[$model->status];
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
                    'label' => 'Utworzony'
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

        ]) ?>
</div>

<script type="text/javascript">
function apply_filter() {

$('.grid-view').yiiGridView('applyFilter');

}
</script>