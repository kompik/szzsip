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
use yii\bootstrap\Tabs;

/* @var $client Client */
/* @var $this View*/

$this->title = 'Klient '.$client->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<?= Html::a(Yii::t('app', 'Edytuj dane klienta'), 
        ['update', 'id' => $client->id ], 
        [
            'class' => 'btn btn-success',
            'id' => 'btn-edit-client'
            ])?>
<?= Tabs::widget([
    'items' => [
        [
            'label' => 'Informacje ogólne',
            'active' => 'true',
            'content' => $this->render('_info-tab', ['client' => $client])
        ],
        [
            'label' => 'Projekty',
            'content' => $this->render('_projects-tab', [
                'client' => $client,
                'projectDataProvider' => $projectDataProvider,
                'projectSearchModel' => $projectSearchModel,
                'clientAllProjectsNames' => $clientAllProjectsNames
                    ])
        ],
        [
            'label' => 'Zlecenia',
            'content' => $this->render('_orders-tab', [
                'client' => $client,
                'orderDataProvider' => $orderDataProvider,
                'orderSearchModel' => $orderSearchModel,
                'clientAllOrdersNames' => $clientAllOrdersNames
                    ])
        ],
        [
            'label' => 'Użytkownicy'
        ]
    ]
])?>

