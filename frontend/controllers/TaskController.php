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
use common\models\TaskSearch;
use common\models\Task;
use common\models\OrderTask;
use common\models\StartStop;
use common\models\Client;
use common\models\Order;
use frontend\models\OrderForm;
use Exception;
use yii\db\Query;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\data\ActiveDataProvider;

class TaskController extends Controller
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
        $searchModel = new TaskSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $userList = User::findAllUsers();
        $tasksNames = Task::getAllTasksNames();
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'userList' => $userList,
            'tasksNames' => $tasksNames
                ]);
    }
    
    public function actionView($id)
    {
        $task = Task::findOne([$id]);
        
        return $this->render('view', ['task' => $task]);
    }
    
    public function actionAdd()
    {
        $task = new Task();
        if ($task->load(Yii::$app->request->post()) && $task->validate()){
                $task->status = Task::STATUS_ACTIVE;
                $task->save();
                Yii::$app->session->addFlash('success', Yii::t('app', 'Dodano zadanie'));
                return $this->redirect('/task/index');
            }

        return $this->render('add', [
            'task' => $task,
                ]);
    }
    
    public function actionUpdate($id)
    {
        $task = Task::findOne($id);
        
        if ($task->load(Yii::$app->request->post()) && $task->save())
        {          
            Yii::$app->session->addFlash('success', Yii::t('app', 'Zapisano zmiany.'));
            return $this->redirect(['index']);

        }
        return $this->render('update', [
            'task' => $task
                ]);
    }
    
    public function actionDelete($id)
    {
        $task = Task::findOne($id);
        
        $task->status = Task::STATUS_DELETED;
        if ($task->save(false, ['status', 'updated_at', 'updated_by']))
        {
            Yii::$app->session->addFlash('success', Yii::t('app', 'Usunięto zadanie.'));
            return $this->redirect(['index']);

        }
    }
    
    public function actionStartWork($id, $type = 'start')
    {
        $orderTask = OrderTask::findOne($id);
        $startStop = new StartStop();
        
        $startStop->order_task_id = $orderTask->id;
        $startStop->type = $type == 'start' ? StartStop::TYPE_START : StartStop::TYPE_RESUME;
        
        if ($orderTask->Lock() && $startStop->save()){
            Yii::$app->session->addFlash('success', Yii::t('app', 'Pracujesz teraz nad zadaniem "'. $orderTask->task->name .'".'));
        }
        else {
            Yii::$app->session->addFlash('error', Yii::t('app', 'Nie możesz rozpocząć pracy nad zadaniem "'. $orderTask->task->name .'". Sprawdź czy już ktoś nad nim nie pracuje.'));
        }
        return $this->redirect(['/order/view', 'id' => $orderTask->order_id]);
    }
    
    public function actionStopWork($id)
    {
        $orderTask = OrderTask::findOne($id);
        $startStop = new StartStop();
        
        $startStop->order_task_id = $orderTask->id;
        $startStop->type = StartStop::TYPE_STOP;
        
        if ($orderTask->Unlock() && $startStop->save()){
            Yii::$app->session->addFlash('success', Yii::t('app', 'Zakończyłeś pracę z zadaniem "'. $orderTask->task->name .'".'));
        }
        else {
            Yii::$app->session->addFlash('error', Yii::t('app', 'Nie można zakończyć pracy nad zadaniem "'. $orderTask->task->name .'". Spróbuj jeszcze raz.'));
        }
        return $this->redirect(['/order/view', 'id' => $orderTask->order_id]);
    }
    
    public function actionPauseWork($id)
    {
        $orderTask = OrderTask::findOne($id);
        $startStop = new StartStop();
        
        $startStop->order_task_id = $orderTask->id;
        $startStop->type = StartStop::TYPE_PAUSE;
        
        if ($orderTask->Pause() && $startStop->save()){
            Yii::$app->session->addFlash('success', Yii::t('app', 'Zatrzymałeś czas pracy nad zadaniem "'. $orderTask->task->name .'".'));
        }
        else {
            Yii::$app->session->addFlash('error', Yii::t('app', 'Nie można zatrzymać czasu pracy nad zadaniem "'. $orderTask->task->name .'". Spróbuj jeszcze raz.'));
        }
        return $this->redirect(['/order/view', 'id' => $orderTask->order_id]);
    }
    
}
