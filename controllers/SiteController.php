<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Manager;

use app\models\Clients; //Подключаем модель для обработки списка клиентов;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Displays manager homepage.
     *
     * @return string
     */
    public function actionManager()
    {
           
        $model = new Manager(); // И создаём объект модели
            
        $this->view->title = 'Все статьи';     //Передаём объект модели в вид         -это надо вообще?
        //return $this->render('test', compact('model'));
        
        
        return $this->render('manager', compact('model'));
    }
    
    public function actionClients()
    {      
         $this->view->title = 'One Article';
        //$comments = Comments::find()->offset()->limit()->orderBy()->all(); 
        /* $clients = Clients::find()->all(); //вывести все записи таблицы clients
        return $this->render('clients', [
            'clients' => $clients
        ]); */
        //$this->view->RegisterMetaTag(['name' => 'keywords', 'content' => 'ключевики...']);
        //$this->view->RegisterMetaTag(['name' => 'description', 'content' => 'описание страницы...']);
        
        #Добавление строки в таблицу clients, с текстом "1111" в поле client
        //$model = new Clients();
        //$model->client = '1111';
        //$model->save();
        //return $this->render('clients');
        
         #Проверяем, пришли ли данные методом аякс (метод request проверяет, откуда пришли данные - пост, гет, аякс)
        if(Yii::$app->request->isAjax){
            
            
            //debug($_GET);
            //print_r($POST);
            //print_r('rr='.Yii::$app->request->post('id_line'));
            $idRow = Yii::$app->request->post('id_line');
            
            print_r($idRow);
            $model = new Clients();
            //$model = Clients::find()->where(['id' => 'idRow'])->one();// выбираем из таблицы строку с нужным id
            //print_r($model);
            //$model->delete();// удаляем строку
            
            $model = Article::findOne($idRow);
            $model->delete();
            
            return 'test1';
        }
        
        $listClients = Clients::find()->all();    //забираем из базы
        return $this->render('clients', compact('listClients')); //compact('listClients') - передаём в вид результат 
    }
    
    public function actionDeleteRow($id, $nameTable){
        
        
    }
    
    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
