<?php
use common\models\Order;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use common\models\User;
use yii\data\ActiveDataProvider;
use kartik\grid\GridView;
use common\helpers\TimeHelper;
use yii\bootstrap\Tabs;

/* @var $user User */
/* @var $this View*/

$this->title = 'Użytkownik '.$user->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<?= Html::a(Yii::t('app', 'Edytuj dane użytkownika'), 
        ['update', 'id' => $user->id ], 
        [
            'class' => 'btn btn-success',
            'id' => 'btn-edit'
            ])?>
<?= Tabs::widget([
    'items' => [
        [
            'label' => 'Informacje ogólne',
            'active' => 'true',
            'content' => $this->render('_info-tab', ['user' => $user])
        ],
//        [
//            'label' => 'Projekty',
//            'content' => $this->render('_projects-tab', [
//                'client' => $client,
//                'projectDataProvider' => $projectDataProvider,
//                'projectSearchModel' => $projectSearchModel,
//                'clientAllProjectsNames' => $clientAllProjectsNames
//                    ])
//        ],
//        [
//            'label' => 'Zlecenia',
//            'content' => $this->render('_orders-tab', [
//                'client' => $client,
//                'orderDataProvider' => $orderDataProvider,
//                'orderSearchModel' => $orderSearchModel,
//                'clientAllOrdersNames' => $clientAllOrdersNames
//                    ])
//        ],
    ]
])?>

