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
use app\models\Guards; //Подключаем модель для обработки списка охранников;

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
        //$model = new Clients();                        //создаём объект модели
        //return $this->render('client', compact('model')); //Передаём объект модели в вид
        
        #Добавление строки в таблицу clients, с текстом "1111" в поле client
        //$model = new Clients();
        //$model->client = '11121';
        //$model->save();
        
        //return $this->render('clients');

        $listClients = Clients::find()->all();    //забираем из базы
        return $this->render('clients', compact('listClients')); //compact('listClients') - передаём в вид результат   
    }
    
    public function actionDelete(){
         #Проверяем, пришли ли данные методом аякс (метод request проверяет, откуда пришли данные - пост, гет, аякс)
         if(Yii::$app->request->isAjax){
            $idRow = Yii::$app->request->post('id_line');
            //print_r($idRow);
            $model = new Clients(); //говорят, лишняя
            $model = Clients::find()->where(['id' => $idRow])->one()->delete(); //выбираем строку с нужным id и удаляем её

            //$listClients = Clients::find()->all();    //забираем из базы
            //return $this->render('clients', compact('listClients')); //compact('listClients') - передаём в вид результат 
        }

    }
    
    
    public function actionRegister()
    {      
        $this->view->title = 'One Article';
        
        #Добавление строки в таблицу clients
        if(Yii::$app->request->isAjax){
            $model = new Clients();
            $nameNewClient = Yii::$app->request->post('client');
            $model->client = $nameNewClient;
            $model->save();
            
            $rows = (new \yii\db\Query())
                ->select(['id'])
                ->from('clients')
                ->where(['client'=>$nameNewClient])
                ->one();
            
            foreach ($rows as $key => $value) {
                $rows = $value;
            }
            
            $json_data = array(0 => $rows);

            echo json_encode($json_data);

        }

        
        //return $this->render('clients');
        //$listClients = Clients::find()->all();    //забираем из базы
        //return $this->render('clients', compact('listClients')); //compact('listClients') - передаём в вид результат   
    }
    
    public function actionGuards()
    {      
         $this->view->title = 'One Article';
        //$model = new Guards();                        //создаём объект модели
        //return $this->render('client', compact('model')); //Передаём объект модели в вид
        
        #Добавление строки в таблицу guards, с текстом "1111" в поле client
        //$model = new Guards();
        //$model->client = '11121';
        //$model->save();
        
        //return $this->render('guards');

        $listGuards = Guards::find()->all();    //забираем из базы
        return $this->render('guards', compact('listGuards')); //compact('listClients') - передаём в вид результат   
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
