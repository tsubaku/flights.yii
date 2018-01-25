<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
//use app\models\ContactForm;

use app\models\Client;      //список фирм клиентов;
use app\models\Gun;         //список оружия;
use app\models\User;        //встроенная авторизация;
use app\models\User_gun;    //таблица связей юзер-оружие (многие ко многим);
use app\models\Flight;      //таблица рейсов;
use app\models\Photo;       //таблица фотографий, присылаемых охранниками;
use app\models\SignupForm;  //таблица охранников и прочих юзеров
use app\models\Sentry;      //таблица постовой ведомости


#Основной контроллер для функций, котрые используются более чем на одной странице сайта
#
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        //only -фильтр ACF нужно применять только к перечисленным действиям
        //rules -задаёт правила доступа    
            //Разрешить всем гостям (ещё не прошедшим авторизацию) доступ к действиям login и signup. 
                //roles 
                    //? - специальный токен, обозначающий "гостя".
                    //@ — специальный токен, обозначающий аутентифицированного пользователя.
            //Разрешить аутентифицированным пользователям доступ к действию logout. 
        return [
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['logout'],   
                'only' => ['logout', 'manager', 'guard', 'signup', 'client', 'gun'],
                'rules' => [
                    //страницы, доступные всем гостям
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    //страницы, доступные всем авторизованным
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    //страницы, доступные менеджеру:
                    [
                       'actions' => ['manager', 'guard', 'signup', 'client', 'gun', 'sentry'],
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                           return User::isUserAdmin(Yii::$app->user->identity->username);
                       }
                    ],
                    //страницы, доступные охраннику:
                    [ 
                        'actions' => ['guard'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                           return User::isUserUser(Yii::$app->user->identity->username);
                       }
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


    
  
    
    #+Удаление рейса, клиента, оружия или юзера
    public function actionDelete(){
         #Проверяем, пришли ли данные методом аякс (метод request проверяет, откуда пришли данные - пост, гет, аякс)
         if(Yii::$app->request->isAjax){
            $idRow = Yii::$app->request->post('id_line');
            $tableName = Yii::$app->request->post('table');

            if ($tableName == '11') {
                $model = new Client(); //говорят, лишняя
                $model = Client::find()->where(['id' => $idRow])->one()->delete(); //выбираем строку с нужным id и удаляем её
            } elseif ($tableName == '10') {
                //$model = new User(); //говорят, лишняя
                $model = new SignupForm(); //говорят, лишняя
                $model = User::find()->where(['id' => $idRow])->one()->delete(); //выбираем строку с нужным id и удаляем её
            } elseif ($tableName == '20') {
                $model = Flight::find()->where(['id' => $idRow])->one()->delete(); //выбираем строку с нужным id и удаляем её
            } elseif ($tableName == '31') {
                $model = new Gun(); //говорят, лишняя
                $model = Gun::find()->where(['id' => $idRow])->one()->delete(); //выбираем строку с нужным id и удаляем её
            }
        }
    }
    
 
   
    
 
    
   

    

    
    
    
    
#####  Ниже только неиспользуемые куски кода  ##########################################################  
    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    /* public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');
            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    } */

    /**
     * Displays about page.
     *
     * @return string
     */
    /* public function actionAbout()
    {
        return $this->render('about');
    } */
    
    
    
    //+Акшен срабатывает при изменении охранника в любой строке постовой ведомости, принимает ФИО, возвращает список приписанного к нему оружия
   /*  public function actionSelecteduser(){
        if(Yii::$app->request->isAjax){
            $fullName = Yii::$app->request->post('selectedUser');

            #Вытаскиваем все связи
            $usersGuns = User_gun::find()->with(['user','gun'])->asArray()->all();    //забираем из базы
            
            
            #Вытаскиваем все пути к фотографиям рейса
            //$listPhoto = Photo::find()->asArray()->all();    //забираем из базы
            
            //$photo_name_array = null; //собираем в один массив все фото с данного рейса
            $p = 0;
            $listGun = array(
                0 => "Оружие отсутствует"
            );
            foreach ($usersGuns as $key => $val) {
                if ($val['user'][0]['full_name'] == $fullName) { 
                    //$photo_name_array[$p] = $val['path'];
                    //$patchCurrent       = $val['path'];
                    $listGun[$p]    = $val['gun'][0]['name'];
                    $p                  = $p + 1;
                } 
            }
 
            echo json_encode($fullName);
        }
    } */
    
    
    
  
       #-Добавление клиента (через написанный вручную аякс-скрипт)
    public function actionRegisterclient4()
    {      
        #Добавление строки в таблицу client
        if(Yii::$app->request->isAjax){
            $model = new Client();
            $nameNewClient = Yii::$app->request->post('client');
            $model->name = $nameNewClient;
            $model->save();
            
            $rows = (new \yii\db\Query())
                ->select(['id'])
                ->from('client')
                ->where(['name'=>$nameNewClient])
                ->one();
            
            foreach ($rows as $key => $value) {
                $rows = $value;
            }
            $json_data = array(0 => $rows);
            echo json_encode($json_data);
        }  
    }
    
    
    
    #-Добавление нового юзера (охранника/менеджера) (через написанный вручную аякс-скрипт)
    public function actionRegisteruser4()
    {      
        #Добавление строки в таблицу user
        if(Yii::$app->request->isAjax){
            $model = new Users();
            $loginNewUser = Yii::$app->request->post('g_login');
            $passwordNewUser = Yii::$app->request->post('g_password');   
            $fullNameNewUser = Yii::$app->request->post('fullName');
            
            $passwordNewUserHash = password_hash(trim($passwordNewUser), PASSWORD_DEFAULT);
            
            $model->user_login = $loginNewUser;
            $model->user_password = $passwordNewUserHash;
            $model->full_name = $fullNameNewUser;
            
            $model->save();
            
            $rows = (new \yii\db\Query())
                ->select(['user_id'])
                ->from('users')
                ->where(['user_login'=>$loginNewUser])
                ->one();
            
            foreach ($rows as $key => $value) {
                $rows = $value;
            }
            $json_data = array(0 => $rows);
            echo json_encode($json_data);
        }  
    }
    
    
    #Добавление строки в таблицу рейсов (через аякс)
    public function actionAddline()
    {      
        #Добавление строки в таблицу flight
        if(Yii::$app->request->isAjax){
            $model = new Flight();
            //$nameNewFlight = Yii::$app->request->post('flight');
            //$model->flight = $nameNewFlight;
            //$model->save();
            
            $text = '5';
            $model->podklient = $text;
            $model->save();
            
            
            /* $rows = (new \yii\db\Query())
                ->all()
                ->from('flight')
                ->where(['client'=>$nameNewClient])
                ->one();
            
            foreach ($rows as $key => $value) {
                $rows = $value;
            }
            $json_data = array(0 => $rows);
            echo json_encode($json_data); */
        }  
    }
##########################################################    
    
    
    
}




