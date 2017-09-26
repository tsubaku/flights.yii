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
use app\models\Users; //Подключаем модель для обработки списка охранников;
use app\models\User; //Подключаем модель для обработки списка охранников;
use app\models\Flights; //Подключаем модель для обработки таблицы рейсов;

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
            $tableName = Yii::$app->request->post('table');
            //print_r($idRow);
            if ($tableName == '11') {
                $model = new Clients(); //говорят, лишняя
                $model = Clients::find()->where(['id' => $idRow])->one()->delete(); //выбираем строку с нужным id и удаляем её
            } elseif ($tableName == '10') {
                $model = new Users(); //говорят, лишняя
                $model = Users::find()->where(['user_id' => $idRow])->one()->delete(); //выбираем строку с нужным id и удаляем её
            }
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
    
    public function actionRegisteruser()
    {      
        $this->view->title = 'One Article';
        #Добавление строки в таблицу clients
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
        //return $this->render('clients');
        //$listClients = Clients::find()->all();    //забираем из базы
        //return $this->render('clients', compact('listClients')); //compact('listClients') - передаём в вид результат   
    }
    
    
    
    public function actionUsers()
    {      
         $this->view->title = 'One Article';
        //$model = new Guards();                        //создаём объект модели
        //return $this->render('client', compact('model')); //Передаём объект модели в вид
        
        #Добавление строки в таблицу guards, с текстом "1111" в поле client
        //$model = new Guards();
        //$model->client = '11121';
        //$model->save();
        
        //return $this->render('guards');

        $listUsers = Users::find()->all();    //забираем из базы
        return $this->render('users', compact('listUsers')); //compact('listUsers') - передаём в вид результат 
        //$listClients = Clients::find()->all();    //забираем из базы
        //return $this->render('clients', compact('listClients')); //compact('listClients') - передаём в вид результат        
    }
    
    public function actionShowflightstable()
    {      
        //$this->view->title = 'One Article';
        if(Yii::$app->request->isAjax){
            $year = Yii::$app->request->post('year');
            $month = Yii::$app->request->post('month');
            

            $table = '20'; //рейсы             !!! Костыль !!!
            
            #Перевод названия месяца в его номер по порядку
            $mons       = array(
                "Январь" => 01,
                "Февраль" => 02,
                "Март" => 03,
                "Апрель" => 04,
                "Май" => 05,
                "Июнь" => 06,
                "Июль" => 07,
                "Август" => 08,
                "Сентябрь" => 09,
                "Октябрь" => 10,
                "Ноябрь" => 11,
                "Январь" => 12
            );
            $month_name = $mons[$month];
            
            $ru_rows_array = array(
                    "№",
                    "Номер рейса",
                    "Дата выезда",
                    "Время",
                    "Клиент",
                    "Подклиент",
                    "Номер машины",
                    "Принятие под охрану",
                    "Сдача с охраны",
                    "Состав ОХР",
                    "ФИО",
                    "Выдано",
                    "Машина",
                    "Срок доставки",
                    "Принятие",
                    "Сдача",
                    "Фактич. срок доставки",
                    "Простой часы",
                    "Простой, ставка за охранника",
                    "Простой сумма",
                    "Ставка без НДС",
                    "Ставка с НДС",
                    "Счёт",
                    "ЗП",
                    "Простой",
                    "Аренда машины",
                    "Оплата машины",
                    "ИТОГО",
                    "ЗП+Простой",
                    "Статус"
                );
            
            
                $model = new Flights();

                
                $listFlights = Flights::find()->all();    //забираем из базы всех юзеров
            
            
            
            
            
                echo "<table>";
                echo "<caption><strong>Рейсы за $month $year</strong></caption>"; //Название таблицы
                echo "</table>";
        
        $json_data = array(0 => $listFlights);
        echo json_encode($json_data);
        }

    
    }
    
    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        //user - компонент содержит постоянную информацию о текущем пользователе
        //получить к ней доступ из любого места приложения: Yii::app()->user
        //isGuest - проверить, является ли пользователь гостем
        //Для проверки прав на определённые действия удобно воспользоваться CWebUser::checkAccess. Также есть возможность получить уникальный идентификатор и другие постоянные данные пользователя.
        if (!Yii::$app->user->isGuest) {
            return $this->goHome(); //Если юзер не гость, то Redirects the browser to the home page.
        }
        //Если же он пока не авторизован, то:
        $model = new LoginForm(); //создаём объект модели LoginForm
        //$model->load(Yii::$app->request->post()) -загрузка атрибутов в модель (предполагаю, что от пользователя из формы ввода логина/пароля)
        //login() - public method, sets the specified identity and remembers the authentication status in session and cookie
        //$model->login() -применяем метод login() к модели
        //Предполагаю: "Если пришли данные из формы ввода И login() прошёл удачно, то редирект к последней посещённой странице
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack(); // goBack()	-метод Redirects the browser to the last visited page.
        }
        //Иначе снова отрендерить страниу login, передав в неё $model 
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
    
    /* public function actionSignup(){
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new Users();

        return $this->render('users', compact('model'));
    } */
    
    
    
}




