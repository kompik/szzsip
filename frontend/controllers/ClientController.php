<?php

namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Project;
use common\models\ProjectSearch;
use common\models\User;
use common\models\ClientSearch;
use common\models\Client;
use frontend\models\ProjectForm;
use common\models\OrderSearch;
use common\models\Order;
use Exception;
use yii\db\Query;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\data\ActiveDataProvider;

class ClientController extends Controller
{
    /**
     * @inheritdoc
     */

    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'add', 'edit', 'delete', 'view', 'add-attendant'],
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index', 'add', 'edit', 'delete', 'view', 'add-attendant'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    
//    public function beforeSave($insert)
//    {
//        if ($this->isNewRecord && $this->status == self::STATUS_NOT_ACCEPTED) {
//            if (!$this->checkAvailability()) {
//                return false;
//            }
//        }
//        return parent::beforeSave($insert);
//    }
//
//    public function afterSave($insert, $changedAttributes)
//    {
//        if ($insert && $this->status == self::STATUS_NOT_ACCEPTED) {
//            $this->sendOfficeNotification(
//                    'office-notification-html',
//                    'office-notification-text',
//                    'Nowa rejestracja pacjenta w portalu ipobolu.pl'
//                    );
//            $message = Yii::t('app', 'Pojawiła się nowa rejestracja na wizytę dnia {date}, w placówce {place}. Potwierdź ją jak najszybciej w swoim Kalendarzu na iPOBÓLU.pl', [
//                'date'  => Yii::$app->formatter->asDatetime($this->date),
//                'place' => $this->location->fullAddress,
//            ]);
//            $this->sendOfficeNotificationSMS($message);
//            $this->sendPatientNotification(
//                    'patient-visit-notification-html',
//                    'patient-visit-notification-text',
//                    'Potwierdzenie umówienia wizyty'
//                    );
//        }
//
//        return parent::afterSave($insert, $changedAttributes);
//    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ClientSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $userList = User::findAllUsers();
        $clientsList = Client::findAllClients();
        $projectsNames = Project::getAllProjectsNames();
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'userList' => $userList,
            'clientsList' => $clientsList,
            'projectsNames' => $projectsNames
                ]);
    }
    
    public function actionView($id)
    {
        $client = Client::findOne([$id]);
        $projectSearchModel = new ProjectSearch();
        $projectDataProvider = $projectSearchModel->search(Yii::$app->request->queryParams, $client_id = $id);
        $clientAllProjectsNames = Project::getAllProjectsNames($client_id = $id);
        $orderSearchModel = new OrderSearch();
        $orderDataProvider = $orderSearchModel->search(Yii::$app->request->queryParams, null, $client_id = $id);
        $clientAllOrdersNames = Order::getAllOrdersNames($client_id = $id, false, 'name');
        return $this->render('view', [
            'client' => $client,
            'projectDataProvider' => $projectDataProvider,
            'projectSearchModel' => $projectSearchModel,
            'clientAllProjectsNames' => $clientAllProjectsNames,
            'orderDataProvider' => $orderDataProvider,
            'orderSearchModel' => $orderSearchModel,
            'clientAllOrdersNames' => $clientAllOrdersNames
                ]);
    }

    public function actionAdd()
    {
        $client = new Client();
        if ($client->load(Yii::$app->request->post()) && $client->validate()){
            $transaction = Yii::$app->db->beginTransaction();
                try {
                    $client->save();
                    $transaction->commit();
                    Yii::$app->session->addFlash('success', Yii::t('app', 'Dodano klienta'));
                    return $this->redirect('/client/index');
                }
                catch (ActionException $ex){
                    $transaction->rollBack();
                    $client->addError('firstname', $ex->getMessage());
                }                   
                catch (Exception $ex) {
                    $transaction->rollBack();
                    Yii::$app->session->addFlash('error', $ex->getMessage());
                }
        }
        return $this->render('add', [
            'client' => $client,
                ]);
    }
    
    public function actionUpdate($id)
    {
        $client = Client::findOne($id);
        
        if ($client->load(Yii::$app->request->post()) && $client->save())
        {
            Yii::$app->session->addFlash('success', Yii::t('app', 'Zapisano zmiany.'));
            return $this->redirect(['index']);

        }
        $usersList = User::find(['!=', 'type', User::TYPE_CLIENT])
                ->select('username')->indexBy('id')->column();
        return $this->render('update', [
            'client' => $client,
            'usersList' => $usersList
                ]);
    }
    
    public function actionDelete($id)
    {
        $client = Client::findOne($id);
        
        $client->status = Client::STATUS_DELETED;
        if ($client->save())
        {
            Yii::$app->session->addFlash('success', Yii::t('app', 'Usunięto klienta '. $client->acronym .'.'));
            return $this->redirect(['index']);

        }
    }
    
    public function actionAddProperty($id, $property = null){
        $client = Client::findOne($id);
        
        if ($client->load(Yii::$app->request->post()) && $client->save()){
            Yii::$app->session->addFlash('success', Yii::t('app', 'Zapisano zmiany.'));
            return $this->redirect(['view', 'id' => $id]);
        }
        $usersList = User::find(['!=', 'type', User::TYPE_CLIENT])->select('username')->indexBy('id')->column();
        return $this->renderPartial('_modal-add-property', [
            'client' => $client,
            'usersList' => $usersList,
            'property' => $property
        ]);
        
    }
    
}
