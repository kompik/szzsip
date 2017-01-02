<?php //

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use common\models\Order;
use common\models\Task;
use common\models\TaskSearch;
use yii\data\ActiveDataProvider;
use kartik\grid\GridView;

/* @var $this View */
/* @var $orders Order[] */
/* @var $dataProvider ActiveDataProvider*/

$this->title = 'Zadania';
$this->params['breadcrumbs'][] = $this->title;
$user = Yii::$app->user->isGuest ? : Yii::$app->user->identity;
$deleteButton = $user->isAdmin() || $user->isSupervisor() ? Html::a(Yii::t('app', '<i class="glyphicon glyphicon-minus"></i> Usuń zaznaczone zadania'), Url::to(['add']), ['class' => 'btn btn-danger']) : '';
$addToTaskButton = Html::a(Yii::t('app', '<i class="glyphicon glyphicon-plus"></i> Dodaj zaznaczone zadania do zlecenia'), Url::to(['add']), ['class' => 'btn btn-success']);
?>

<div class="site-index">
        
        <?= GridView::widget([
            'panel'=>[
                'type'=>GridView::TYPE_PRIMARY,
                'heading'=>'<i class="glyphicon glyphicon-th"></i> Zadania',
            ],'toolbar' => [
                [
                    'content'=>
                        Html::a('<i class="glyphicon glyphicon-plus"></i> Dodaj zadanie', Url::to(['add']), ['class' => 'btn btn-success']). ' ' .
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
                    'attribute' => 'creator',
                    'format' => 'raw',
                    'value' => function($model){
                        return Html::a($model->creator->username, Url::to(['/user/view', 'id' => $model->created_by]), ['data-pjax' => 0]);
                    },
                ],
                [
                    'class' => '\kartik\grid\DataColumn',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'data' => Task::listStatuses(),
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
                                    return Task::listStatuses()[$model->status];
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
                    'header' => 'Akcje',
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

<script type="text/javascript">
function apply_filter() {

$('.grid-view').yiiGridView('applyFilter');

}
</script>