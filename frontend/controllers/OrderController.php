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
use common\models\TaskSearch;
use common\models\Task;
use common\models\OrderTask;
use common\models\OrderTaskSearch;
use frontend\models\Task2OrderForm;
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
                'only' => ['index', 'add', 'edit', 'delete', 'add-task-to-order'],
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index', 'add', 'edit', 'delete', 'add-task-to-order'],
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
        $orderNames = Order::getAllOrdersNames(false, 'name');
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
    
    public function actionView($id)
    {
        $order = Order::findOne([$id]);
        $searchModel = new OrderTaskSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);
        $userList = User::findAllUsers();
        $tasksNames = Task::getAllTasksNames($id);
        $user = \Yii::$app->user->identity;
        return $this->render('view', [
            'order' => $order,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'userList' => $userList,
            'tasksNames' => $tasksNames,
            'user' => $user
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
        $order = Order::findOne([$id]);
        
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
        $order = Order::findOne([$id]);
        
        $order->status = Order::STATUS_DELETED;
        if ($order->save(false, ['status', 'updated_at', 'updated_by']))
        {
            Yii::$app->session->addFlash('success', Yii::t('app', 'Usunięto zlecenie.'));
            return $this->redirect(['index']);
        }
            Yii::$app->session->addFlash('error', Yii::t('app', 'Nie udało się usunąć zlecenia!'));
            return $this->redirect(['index']);
    }
    
    public function actionAddTaskToOrder($order_id = null, $task_id = null)
    {
        $order = $order_id ? Order::findOne([$order_id]) : new Order();
        $task = $task_id ? Task::findOne($task_id) : new Task();
        $task2order = new Task2OrderForm();
        
        if ($task2order->load(Yii::$app->request->post()) && $task2order->validate()){
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $task2order->addTask2Order();
                $transaction->commit();
                Yii::$app->session->addFlash('success', Yii::t('app', 'Dodano zadania do zlecenia'));
                return $this->redirect(['view', 'id' => $task2order->order_id]);

            }
            catch (ActionException $ex){
                $transaction->rollBack();
                $task2order->addError('name', $ex->getMessage());
            }                   
            catch (Exception $ex) {
                $transaction->rollBack();
                Yii::$app->session->addFlash('error', $ex->getMessage());
            }
        }

        $tasksList = Task::getAllTasksNames();
        $ordersList = Order::getAllOrdersNames(false, 'id');
        return $this->render('add-task-to-order', [
            'order' => $order,
            'task'  => $task,
            'task2order' => $task2order,
            'tasksList' => $tasksList,
            'ordersList' => $ordersList
        ]);
    }
    
    public function actionRemoveTask($id)
    {
        $orderTask = OrderTask::findOne($id);
        if ($orderTask->delete()){
            Yii::$app->session->addFlash('success', Yii::t('app', 'Usunięto zadanie ze zlecenia'));
            return $this->redirect(['view', 'id' => $orderTask->order_id]);
        }
    }
}
