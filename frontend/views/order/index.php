<?php //

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use common\models\Order;
use common\models\Project;
use common\models\User;
use yii\data\ActiveDataProvider;
use kartik\grid\GridView;
use kartik\mpdf\Pdf;

/* @var $this View */
/* @var $orders Order[] */
/* @var $dataProvider ActiveDataProvider*/

$this->title = 'Zlecenia';
$this->params['breadcrumbs'][] = $this->title;
$user = Yii::$app->user->isGuest ? : Yii::$app->user->identity;
$deleteButton = $user->isAdmin() || $user->isSupervisor() ? Html::a(Yii::t('app', 'Usuń zaznaczone zlecenia'), Url::to(['add']), ['class' => 'btn btn-danger']) : '';

?>

<div class="site-index">
        
        <?= GridView::widget([
            'pjax'=>true,
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
                            'minimumInputLength' => 3,
                        ]
                    ],
                    'attribute' => 'name',
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
                    'value' => 'owner.username',
                    'label' => Yii::t('app', 'Właściciel zlecenia'),
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
                    'value' => 'client.acronym',
                    'label' => Yii::t('app', 'Klient'),
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
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'data' => $projects,
                        'options' => [
                            'placeholder' => 'filtruj po projekcie ...',
                            'initValueText' => ''
                            ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ]
                    ],
                    'attribute' => 'project',
                    'value' => 'project.name'
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
            'toolbar' => [
                [
                    'content'=>
                        Html::a('<i class="glyphicon glyphicon-plus"></i> Dodaj zlecenie', Url::to(['add']), ['class' => 'btn btn-success']). ' '.
                        Html::a('<i class="glyphicon glyphicon-repeat"></i> Resetuj widok', ['index'], [
                            'class' => 'btn btn-default', 
                            'title' => Yii::t('app', 'Resetuj widok')
                        ]) . ' ' .
                        $deleteButton
                ],
                '{export}',
                '{toggleData}'
            ],
            'panel'=>[
                'type'=>GridView::TYPE_PRIMARY,
                'heading'=>'<i class="glyphicon glyphicon-list"></i> Zlecenia',
            ],
        ]) ?>
</div>

<script type="text/javascript">
function apply_filter() {

$('.grid-view').yiiGridView('applyFilter');

}
</script>