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
$deleteButton = $user->isAdmin() || $user->isSupervisor() ? Html::a(Yii::t('app', 'Usuń zaznaczone projekty'), Url::to(['add']), ['class' => 'btn btn-danger']) : '';

?>


<div class="site-index">
        
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns' => [
                'id',
                'name',
                'description',
                [
                    'attribute' => 'owner',
                    'value' => 'owner.username',
                    'label' => Yii::t('app', 'Właściciel projektu'),
                ],
                'client_id',
                'status'
            ],
            'showPageSummary' => true,
            'toolbar' => [
                [
                    'content'=>
                        Html::a('<i class="glyphicon glyphicon-plus"></i> Dodaj projekt', Url::to(['add']), ['class' => 'btn btn-success']). ' '.
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
                'heading'=>'Projekty',
            ],
        ]) ?>
    </div>
</div>
