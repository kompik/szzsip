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
use common\models\ProjectSearch;
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

class ProjectController extends Controller
{
    /**
     * @inheritdoc
     */

    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'add', 'edit', 'delete', 'view'],
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index', 'add', 'edit', 'delete', 'view'],
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
        $searchModel = new ProjectSearch();
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
        $project = Project::findOne([$id]);
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);
        $userList = User::findAllUsers();
        $clientsList = Client::findAllClients();
        $orderNames = Order::getAllOrdersNames($id, 'name');
        $orders = $project->orders;
        
        return $this->render('view', [
            'project' => $project,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'userList' => $userList,
            'clientsList' => $clientsList,
            'orderNames' => $orderNames,
            'orders' => $orders
                ]);
    }

    public function actionAdd()
    {
        $projectForm = new ProjectForm();
        if ($projectForm->load(Yii::$app->request->post()) && $projectForm->validate()){
            $transaction = Yii::$app->db->beginTransaction();
                try {
                    $projectForm->addProject();
                    $transaction->commit();
                    Yii::$app->session->addFlash('success', Yii::t('app', 'Dodano projekt'));
                    return $this->redirect('/project/index');
                }
                catch (ActionException $ex){
                    $transaction->rollBack();
                    $projectForm->addError('name', $ex->getMessage());
                }                   
                catch (Exception $ex) {
                    $transaction->rollBack();
                    Yii::$app->session->addFlash('error', $ex->getMessage());
                }
        }
        $userList = User::findAllUsers();
        $clientsList = Client::findAllClients();
        $projectStatus = Project::listStatuses();
        return $this->render('add', [
            'projectForm' => $projectForm,
            'userList' => $userList,
            'clientsList' => $clientsList,
            'projectStatus' => $projectStatus
                ]);
    }
    
    public function actionUpdate($id)
    {
        $project = Project::findOne($id);
        
        if ($project->load(Yii::$app->request->post()) && $project->save())
        {
            Yii::$app->session->addFlash('success', Yii::t('app', 'Zapisano zmiany.'));
            return $this->redirect(['index']);

        }
        $userList = User::findAllUsers();
        $clientsList = Client::findAllClients();
        $projectStatus = Project::listStatuses();
        return $this->render('update', [
            'project' => $project,
            'userList' => $userList,
            'clientsList' => $clientsList,
            'projectStatus' => $projectStatus
                ]);
    }
    
    public function actionDelete($id)
    {
        $project = Project::findOne($id);
        
        $project->status = Project::STATUS_DELETED;
        if ($project->save(false, ['status', 'updated_at']))
        {
            Yii::$app->session->addFlash('success', Yii::t('app', 'Usunięto projekt.'));
            return $this->redirect(['index']);

        }
    }
    
}
