<?php //

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use common\models\Order;
use common\models\Project;
use common\models\User;
use common\models\Client;
use yii\data\ActiveDataProvider;
use kartik\grid\GridView;
use kartik\mpdf\Pdf;

/* @var $this View */
/* @var $dataProvider ActiveDataProvider*/

$this->title = 'Użytkownicy';
$this->params['breadcrumbs'][] = $this->title;
$user = Yii::$app->user->isGuest ? : Yii::$app->user->identity;
$deleteButton = $user->isAdmin() || $user->isSupervisor() ? Html::a(Yii::t('app', '<i class="glyphicon glyphicon-minus"></i> Usuń zaznaczonych użytkowników'), Url::to(['add']), ['class' => 'btn btn-danger']) : '';

?>

<div class="site-index">
        
        <?= GridView::widget([
            'panel'=>[
                'type'=>GridView::TYPE_PRIMARY,
                'heading'=>'<i class="glyphicon glyphicon-user"></i> Użytkownicy',
            ],'toolbar' => [
                [
                    'content'=>
                        Html::a('<i class="glyphicon glyphicon-plus"></i> Dodaj użytkownika', Url::to(['add']), ['class' => 'btn btn-success']). ' ' .
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
//                    'filterWidgetOptions' => [
//                        'data' => $orderNames,
//                        'options' => [
//                            'placeholder' => 'filtruj po nazwie ...',
//                            'initValueText' => ''
//                            ],
//                        'pluginOptions' => [
//                            'allowClear' => true,
////                            'minimumInputLength' => 3,
//                        ]
//                    ],
                    'attribute' => 'username',
                    'format' => 'raw',
                    'value' => function($model){
                        return Html::a($model->username, Url::to(['view', 'id' => $model->id]), ['data-pjax' => 0, 'title' => $model->username]);
                    }
                ],
                [
                    'class' => '\kartik\grid\DataColumn',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'data' => User::findAllUsers(),
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
                        return Html::a($model->name, Url::to(['view', 'id' => $model->id]), ['data-pjax' => 0, 'title' => $model->name]);
                    }
                ],
//                [
//                    'class' => '\kartik\grid\DataColumn',
//                    'filterType' => GridView::FILTER_SELECT2,
//                    'filterWidgetOptions' => [
//                        'data' => $userList,
//                        'options' => [
//                            'placeholder' => 'filtruj po właścicielu ...',
//                            'initValueText' => ''
//                            ],
//                        'pluginOptions' => [
//                            'allowClear' => true
//                        ]
//                    ],
//                    'attribute' => 'owner',
//                    'format' => 'raw',
//                    'value' => function($model){
//                        return Html::a($model->owner->username, Url::to(['/user/view', 'id' => $model->owner_id]), ['data-pjax' => 0]);
//                    },
//                ],
                [
                    'class' => '\kartik\grid\DataColumn',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'data' => User::listTypes(),
                        'options' => [
                            'placeholder' => 'filtruj po typie ...',
                            'initValueText' => ''
                            ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ]
                    ],
                    'attribute' => 'type',
                    'format' => 'raw',
                    'value' => function($model){
                        return User::listTypes()[$model->type];
                    },
                ],
                [
                    'class' => '\kartik\grid\DataColumn',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'data' => User::listStatuses(),
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
                                    return User::listStatuses()[$model->status];
                                },
                ],
                [
                    'class' => '\kartik\grid\DataColumn',
                    'filterType' => GridView::FILTER_DATE_RANGE,
                    'attribute' => 'created',
                    'filterWidgetOptions' => [
                        'presetDropdown' => true,
                        'pluginOptions' => [
                            'opens'=>'left',
                            'format' => 'DD-MM-YYYY'
                        ],
                        'pluginEvents' => [
                            "apply.daterangepicker" => "function() { apply_filter('created') }",
                        ]
                    ],
//                    'format' => 'date',
                    'value' => function ($model) {
                                    return gmdate('d-m-Y H:i', $model->created_at);
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