<?php

namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Project;
use common\models\User;
use common\models\OrderSearch;
use common\models\Client;
use common\models\Order;
use frontend\models\OrderForm;
use Exception;
use yii\db\Query;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\data\ActiveDataProvider;

class OrderController extends Controller
{
    /**
     * @inheritdoc
     */

    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'add', 'edit', 'delete'],
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index', 'add', 'edit', 'delete'],
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
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $userList = User::findAllUsers();
        $clientsList = Client::findAllClients();
        $orderNames = Order::getAllOrdersNames();
        $projects = Project::getAllProjects();
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'userList' => $userList,
            'clientsList' => $clientsList,
            'orderNames' => $orderNames,
            'projects' => $projects
                ]);
    }
    
    public function actionAdd()
    {
        $orderForm = new OrderForm();
        if ($orderForm->load(Yii::$app->request->post()) && $orderForm->validate()){
            $transaction = Yii::$app->db->beginTransaction();
                try {
                    $orderForm->addOrder();
                    $transaction->commit();
                    Yii::$app->session->addFlash('success', Yii::t('app', 'Dodano zlecenie'));
                    return $this->redirect('/order/index');
                }
                catch (ActionException $ex){
                    $transaction->rollBack();
                    $orderForm->addError('name', $ex->getMessage());
                }                   
                catch (Exception $ex) {
                    $transaction->rollBack();
                    Yii::$app->session->addFlash('error', $ex->getMessage());
                }
        }
        $userList = User::findAllUsers();
        $clientsList = Client::findAllClients();
        $orderStatus = Order::listStatuses();
        $projects = Project::getAllProjects();
        return $this->render('add', [
            'orderForm' => $orderForm,
            'userList' => $userList,
            'clientsList' => $clientsList,
            'orderStatus' => $orderStatus,
            'projects' => $projects
                ]);
    }
    
    public function actionUpdate($id)
    {
        $order = Order::findOne($id);
        
        if ($order->load(Yii::$app->request->post()) && $order->save())
        {
            Yii::$app->session->addFlash('success', Yii::t('app', 'Zapisano zmiany.'));
            return $this->redirect(['index']);

        }
        $userList = User::findAllUsers();
        $clientsList = Client::findAllClients();
        $orderStatus = Order::listStatuses();
        $projects = Project::getAllProjects();
        return $this->render('update', [
            'order' => $order,
            'userList' => $userList,
            'clientsList' => $clientsList,
            'orderStatus' => $orderStatus,
            'projects' => $projects
                ]);
    }
    
    public function actionDelete($id)
    {
        $order = Order::findOne($id);
        
        $order->status = $order::STATUS_DELETED;
        if ($order->save(false, ['status', 'updated_at']))
        {
            Yii::$app->session->addFlash('success', Yii::t('app', 'Usunięto zlecenie.'));
            return $this->redirect(['index']);

        }
    }
    
}
