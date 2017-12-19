<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

use app\models\Client;  //Подключаем модель для обработки списка клиентов;
use app\models\Gun;     //Подключаем модель для обработки списка клиентов;
use app\models\User;    //Подключаем модель для авторизации;
use app\models\User_gun;    //Подключаем модель для авторизации;
use app\models\Flight;  //Подключаем модель для обработки таблицы рейсов;
use app\models\Photo;   //Подключаем модель для обработки таблицы фотографий;
use app\models\SignupForm; //Подключаем модель для обработки списка охранников
use app\models\Sentry; //Подключаем модель для обработки таблицы постовой ведомости

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
                       'actions' => ['manager', 'guard', 'signup', 'client', 'gun'],
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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

       
    #+Показ календаря на странице интерфейса охранника
    public function actionShowflight()
    {      
        //$this->view->title = 'One Article';
        //$model = new Flight();             //создаём объект модели

        #Принимаем из формы дату и фамилию охранника
        if( Yii::$app->request->isAjax ){
            $data = Yii::$app->request->post('dat');
            $userId = Yii::$app->request->post('user_id_current'); 
            
            $date_flights_mysql = date('Y-m-d', strtotime($data)); //php date dd.mm.yyyy to mysql format 'YYYY-MM-DD'
            $query = 'SELECT `id`,`data_vyezda`, `vremja`, `klient`, `nomer_mashiny`, `prinjatie_pod_ohranu`, `sdacha_s_ohrany`, `prinjatie`, `sdacha`, `status` FROM `flight` WHERE (`data_vyezda` = :date_flights_mysql) AND `fio` = (SELECT `full_name` FROM `user` WHERE `id` = :userId) GROUP BY `id`';
            $flightDate = flight::findBySql($query, [':date_flights_mysql' => $date_flights_mysql, ':userId' => $userId])->asArray()->one(); //получим все записи, сотв. условию  
        } else {
            $flightDate = '0000';
        }
        echo json_encode($flightDate);  
    }
    
    
    #+Загрузка фото
    public function actionUploadfiles()
    {      
        // Здесь нужно сделать все проверки передавемых файлов и вывести ошибки если нужно

        $data = array(); // Переменная ответа
        $error = false;
        $files = array();
        $uploaddir = 'img/photo/'; //каталог для сохраняемых файлов
        
        # Создадим папку если её нет
        //if( ! is_dir( $uploaddir ) ) mkdir( $uploaddir, 0777 );
        
        # переместим файлы из временной директории в указанную
        foreach ($_FILES as $file) {
            #Проверка типа файла
            if ($file['type'] == "image/jpeg") {
                $file['name'] = date('Y-m-d_H-i-s') . ".jpg";
            } elseif ($file['type'] == "image/png") {
                $file['name'] = date('Y-m-d_H-i-s') . ".png";
            } elseif ($file['type'] == "image/gif") {
                $file['name'] = date('Y-m-d_H-i-s') . ".gif";
            } else {
                // $error .= 'Изображения могут быть в формате JPG, PNG или GIF';
                $error = true;
                break;
            }
     
            if (move_uploaded_file($file['tmp_name'], $uploaddir . basename($file['name']))) {
                $files[] = realpath($uploaddir . $file['name']);
                //chmod($uploaddir . basename($file['name']), 777);

                //Создание миниатюры
                //header('Content-Type: image/png'); //или /png /gif, т.е то что нам надо
                //createThumbnail($files[], 'false', 100, 100);    
                
            } else {
                $error = true;
            }
            #Записываем в таблицу photo 
            $model = new Photo();             //создаём объект модели
            $model->path = $file['name'];
            $model->n_flight = $_POST['number_flight'];
            $model->save(); 
        }
        
        $data = $error ? array(
            'error' => 'Ошибка загрузки файлов.'
        ) : array(
            'files' => $files
        );
        
        echo json_encode($data);
    }
    
    

    #+Показ одного рейса
    public function actionGuard()
    {      
        $this->layout = 'simple'; //меняем шаблон на простой

        $idUser       = \Yii::$app->user->identity->id;
        $username     = \Yii::$app->user->identity->username;
        $full_name    = \Yii::$app->user->identity->full_name;
           
        #Вытаскиваем из базы даты выездов нужного охранника     
        $query        = 'SELECT `data_vyezda` FROM `flight` WHERE `fio` = :full_name';
        $table_array  = flight::findBySql($query, [':full_name' => $full_name])->asArray()->all(); //$table_array - массив всех дат выезда указанного охранника
            
        $array_date_of_departure = Array();
        $i                       = 0;
        foreach ($table_array as $date_of_departure) {
            $array_date_of_departure[$i] = $date_of_departure['data_vyezda'];
            $i                           = $i + 1;
        }

        $js_array = json_encode($array_date_of_departure); //масив дат выездов

        return $this->render( 'guard', compact('idUser', 'full_name', 'js_array') ); //передаём в вид результат   
    }
    
    
    
    
    /**
     * Displays manager homepage.
     *
     * @return string
     */
    #+Акшен страницы рейсов, показывающий рейсы за период
    public function actionManager()
    {    
        $model = new Flight();             //создаём объект модели
        
       
        
        #Кнопка добавления строки в таблицу 
        if ( Yii::$app->request->post('add-button') ) {
            $text = '';
            $model->podklient = $text;
            $model->save();
        } 

        #Если период введён, подставляем его. Если нет, то подставляем текущий год и месяц
        if( (Yii::$app->request->post('refresh-button')) or (Yii::$app->request->post('add-button')) ){
            $text = 'post';
            $year = Yii::$app->request->post('year');
            $month = Yii::$app->request->post('month'); 
        } else {
            $text = 'no_post';
            $year = date("Y");
            $month = date("m"); //SELECT * FROM flight WHERE data_vyezda between '2017-10-01' and '2017-10-31'
        }
        
        $table = '20'; //рейсы             !!! Костыль !!!

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
        
        #забираем из базы все рейсы  between 1 and 31'
        $date1 = $year."-".$month."-01";
        $date2 = $year."-".$month."-31";
        $query = "SELECT * FROM flight WHERE data_vyezda between :date1 and :date2";
        $listFlight = flight::findBySql($query, [':date1' => $date1, ':date2' => $date2])->with('photo')->asArray()->all(); 
        //print_r($listFlight);
        
        #Ищем рейсы без даты и добавляем их в таблицу, а если таких нет, то передварительно создаём их
        $query = "SELECT * FROM flight WHERE data_vyezda IS NULL";
        $listFlightNoDate = flight::findBySql($query)->asArray()->all(); //получим все записи, сотв. условию
        if ( empty($listFlightNoDate) ) {
            $text = '';
            $model->podklient = $text;
            $model->save(); 
            
            #И заново вытаскиваем эту пустую строку из базы
            $query = "SELECT * FROM flight WHERE data_vyezda IS NULL";
            $listFlightNoDate = flight::findBySql($query)->with('photo')->asArray()->all(); //получим все записи, сотв. условию            
        }

        #добавляем пустые строки в общий результат
        $p = count($listFlight);
        foreach ($listFlightNoDate as $key => $val) {   
            //$flightPhoto[] = $val['path']; 
            $p = $p + 1;
            $listFlight[$p] = $val; 
        }  
                                    
                                    
        #Вытаскиваем все фамилии охранников
        $listUsers = User::find()->select('full_name')->asArray()->column();    //забираем из базы
        $k = count($listUsers);
        $listUsers[$k+1] = 'Не выбран'; //Добавляем в массив охранников невыбранного 
        
        #Вытаскиваем всех клиентов
        $listClients = Client::find()->select('name')->asArray()->column();    //забираем из базы
        $k = count($listClients);
        $listClients[$k] = 'Не выбран'; //Добавляем в массив невыбранного клиента
    
    
        //$cats = Flight::find()->where('id=568')->all(); 
        //$cats = Flight::findOne(568); 
        $cats = Flight::find()->with('photo')->asArray()->all(); 
        foreach ($cats as $customer) {
            $cats2 = $customer->photo;
        }
    
        #Вытаскиваем все пути к фотографиям рейса
        $listPhoto = Photo::find()->asArray()->all();    //забираем из базы

        return $this->render('manager', compact('model', 'listFlight', 'year', 'month', 'ru_rows_array', 'listUsers', 'listClients', 'listPhoto', 'text', 'cats', 'cats2')); //передаём в вид результат 
    }
    
    
    
    
    #+Изменение данных в ячейке таблицы
    public function actionChange(){
         #Проверяем, пришли ли данные методом аякс (метод request проверяет, откуда пришли данные - пост, гет, аякс)
         if(Yii::$app->request->isAjax){
            $cellValue = Yii::$app->request->post('cell_value');   
            $cellId = Yii::$app->request->post('id_in_db');
            $cellColumn = Yii::$app->request->post('column_in_db');

            #Обновить ячейку в таблице 
            $model = Flight::findOne($cellId); //Выбрать из таблицы Flight первую запись с id=$cellId
            $model->$cellColumn = $cellValue;   //Выбрать из этой записи ячейку в столбце $cellColumn и записать туда $cellValue
            $model->save();                     //сохранить

            #Просчёт связанных ячеек
            $line_array = $model;
            $res_array = array();
            switch ($cellColumn) {
                case 'prostoj_summa':
                case 'stavka_bez_nds':
                case 'stavka_s_nds':
                    $prostoj_summa  = intval($line_array['prostoj_summa']);
                    $stavka_bez_nds = intval($line_array['stavka_bez_nds']);
                    $stavka_s_nds   = intval($line_array['stavka_s_nds']);
                    $schet          = $prostoj_summa + $stavka_bez_nds + $stavka_s_nds;
                    
                    $model->schet = $schet;   //Выбрать из этой записи ячейку в столбце schet и записать туда $schet
                    $model->save();           //сохранить

                    $res_array["schet"] = $schet; // Это добавляет к массиву новый элемент с ключом "schet"
                    break;
                    
                case 'prinjatie': //datetime(6)
                case 'sdacha': //datetime(6)
                    $prinjatie = strtotime($line_array['prinjatie']); //1488883260 - секунд Unix
                    $sdacha    = strtotime($line_array['sdacha']);
                    
                    if ($prinjatie <= $sdacha) {
                        $fakticheskij_srok_dostavki = $sdacha - $prinjatie; // 60 - разница в секундах
                        $hh                         = intval($fakticheskij_srok_dostavki / 3600);
                        $mm                         = intval($fakticheskij_srok_dostavki / 60) - $hh * 60;
                        if ($mm < 10) {
                            $mm = "0" . "$mm";
                        }
                        if ($hh < 10) {
                            $hh = "0" . "$hh";
                        }
                        $fakticheskij_srok_dostavki = $hh . ":" . $mm; //string    
                        
                    } else {
                        $fakticheskij_srok_dostavki = "--:--"; // 
                    }
                    
                    if ($sdacha == NULL) {
                        $fakticheskij_srok_dostavki = "В рейсе"; // 
                    }
                    if ($prinjatie == NULL) {
                        $fakticheskij_srok_dostavki = "--:--"; // 
                    }
                    
                    $res_array["fakticheskij_srok_dostavki"] = $fakticheskij_srok_dostavki; //Доб.к массиву новый эл.с ключом fakticheskij_srok_dostavki

                    $model->fakticheskij_srok_dostavki = $fakticheskij_srok_dostavki;   
                    $model->save();                     //сохранить
  
                    break;                

                case 'prostoj_chasy':
                case 'prostoj_stavka_za_ohrannika':
                    $prostoj_chasy               = intval($line_array['prostoj_chasy']);
                    $prostoj_stavka_za_ohrannika = intval($line_array['prostoj_stavka_za_ohrannika']);
                    $prostoj_summa               = $prostoj_chasy * $prostoj_stavka_za_ohrannika * 2;

                    $model->prostoj_summa = $prostoj_summa;   
                    $model->save();                     //сохранить

                    $res_array["prostoj_summa"] = $prostoj_summa; // Это добавляет к массиву новый элемент с ключом "prostoj_summa"
                    
                    //От prostoj_summa зависит schet, так что пересчитываем его
                    $stavka_bez_nds = intval($line_array['stavka_bez_nds']);
                    $stavka_s_nds   = intval($line_array['stavka_s_nds']);
                    $schet          = $prostoj_summa + $stavka_bez_nds + $stavka_s_nds;

                    $model->schet = $schet;   
                    $model->save();                     //сохранить

                    $res_array["schet"] = $schet; // Это добавляет к массиву новый элемент с ключом "schet"
                    break;
                    
               case 'arenda_mashin':
                    $arenda_mashin = intval($line_array['arenda_mashin']);
                    $oplata_mashin = $arenda_mashin * 1700;

                    $model->oplata_mashin = $schet;   //Выбрать из этой записи ячейку в столбце $cellColumn и записать туда $schet
                    $model->save();                     //сохранить
                    $res_array["oplata_mashin"] = $oplata_mashin; // Это добавляет к массиву новый элемент с ключом "schet"
                    
                    //От arenda_mashin зависит itogo, так что пересчитываем его
                    $zp      = intval($line_array['zp']);
                    $prostoj = intval($line_array['prostoj']);
                    $itogo   = $zp + $prostoj + $oplata_mashin;

                    $model->itogo = $itogo;   
                    $model->save();                     //сохранить
                    $res_array["itogo"] = $itogo; // Это добавляет к массиву новый элемент с ключом "schet"
                    break;     
                    
               case 'zp':
               case 'prostoj':
               case 'oplata_mashin':
                    $zp            = intval($line_array['zp']);
                    $prostoj       = intval($line_array['prostoj']);
                    $oplata_mashin = intval($line_array['oplata_mashin']);
                    $itogo         = $zp + $prostoj + $oplata_mashin;

                    $model->itogo = $schet;   //Выбрать из этой записи ячейку в столбце $cellColumn и записать туда $schet
                    $model->save();                     //сохранить
                    $res_array["itogo"] = $itogo; // Это добавляет к массиву новый элемент с ключом "schet"
                    
                    $zp_plus_prostoj = $zp + $prostoj;

                    $model->zp_plus_prostoj = $zp_plus_prostoj;   //Выбрать из этой записи ячейку в столбце $cellColumn и записать туда $schet
                    $model->save();                     //сохранить
                    $res_array["zp_plus_prostoj"] = $zp_plus_prostoj; // Это добавляет к массиву новый элемент с ключом "schet"            
                    break;
                
                default:
                    break;     
                                   
            }
            echo json_encode($res_array);
        }
    }
    
    
    #+Изменение данных в ячейке таблицы Постовая ведомость
    public function actionChangesentry(){
         #Проверяем, пришли ли данные методом аякс (метод request проверяет, откуда пришли данные - пост, гет, аякс)
         if(Yii::$app->request->isAjax){
            $cellValue = Yii::$app->request->post('cell_value');   
            $cellId = Yii::$app->request->post('id_in_db');
            $cellColumn = Yii::$app->request->post('column_in_db');

            #Обновить ячейку в таблице 
            $model = Sentry::findOne($cellId); //Выбрать из таблицы первую запись с id=$cellId
            $model->$cellColumn = $cellValue;   //Выбрать из этой записи ячейку в столбце $cellColumn и записать туда $cellValue
            $model->save();                     //сохранить
 
            $listGun = array();
            if ($cellColumn == 'full_name') {   //Если изменена фамилия охранника, то вытаскиваем приписанное к нему оружие
                #Вытаскиваем все связи
                $usersGuns = User_gun::find()->with(['user','gun'])->asArray()->all();    //забираем из базы
                $p = 0;
                foreach ($usersGuns as $key => $val) {
                    if ($val['user'][0]['full_name'] == $cellValue) { 
                        $listGun[$p]    = $val['gun'][0]['name'];
                        $p                  = $p + 1;
                    } 
                }  
            }
            $res_array = [$cellValue, $cellId, $cellColumn, $listGun];
            echo json_encode($res_array);
        }
    }
    
    
    
    #+Добавление строки в таблицу рейсов
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
    
    
    
    #+Отрисовка страницы client и добавление клиента, если нажата копка добавления
    public function actionClient()
    {      
        $model = new Client();  //создаём объект модели 
        
        #Если нажали "Добавить клиента", то проверяем введённые данные и добавляем
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $r1 = Yii::$app->request->post('Client'); //request - объект, который по умолчанию является экземпляром yii\web\Request.
                                                      //у него есть методы get() и post()
            $model->name = $r1['name']; 
            $model->save(); //сохраняем объект модели
        }

        $listClients = Client::find()->all();    //забираем из базы
        return $this->render('client', compact('model', 'listClients')); //передаём в вид результат   
    }
    
    
 
    #+Отрисовка страницы gun и добавление оружия, если нажата копка добавления
    public function actionGun()
    {      
        $model = new Gun();  //создаём объект модели 
        
        #Если нажали "Добавить", то проверяем введённые данные и добавляем
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $r1 = Yii::$app->request->post('Gun'); //request - объект, который по умолчанию является экземпляром yii\web\Request.
                                                      //у него есть методы get() и post()
            $model->name = $r1['name']; 
            $model->save(); //сохраняем объект модели
        }

        $listGun = Gun::find()->all();    //забираем из базы
        return $this->render('gun', compact('model', 'listGun')); //передаём в вид результат   
    }
    
    
    
    #+Удаление рейса, клиента или юзера
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
    
    #+Принять от js-скрипта id юзера, вытащить связь, вытащить прикреплённое оружие, вернуть js-скрипту
    public function actionGunshow(){
        if(Yii::$app->request->isAjax){
            $userId = Yii::$app->request->post('userId');
            $model = new User_gun(); //говорят, лишняя
            $listGun = User_gun::find()->where(['user_id' => $userId])->asArray()->all(); //выбираем строку с нужным id
            
            //$model = new Gun(); //говорят, лишняя
            //$model = Gun::find()->where(['id' => $idRow])->one()->delete(); //выбираем строку с нужным id и удаляем её

            $p = 0;
            foreach ($listGun as $key => $val) {        
                //$gun_id[$p]    = $val['gun_id'];
                //$gun = Gun::find()->where(['id' => $userId])->asArray()->one(); //выбираем строку с нужным id
                
                $gun_id = $val['gun_id'];
                $gun = Gun::find()->where(['id' => $gun_id])->asArray()->one(); //выбираем строку с нужным id
                //$listGunName[$p]    = $gun['name'];
                $listGunId[$p]    = $gun['id'];
                $p                  = $p + 1;
            }
            
            echo json_encode($listGunId);
        }
    }
    
    //+Получить от js-скрипта событие чекбокса, сделать изменения в базе user_gun
    public function actionCheckboxchange(){
        if(Yii::$app->request->isAjax){
            $userName = Yii::$app->request->post('userName');
            $gunId = Yii::$app->request->post('gunId');
            $checkboxPosition = Yii::$app->request->post('checkboxPosition');
            
            $userId = User::find()->where(['full_name' => $userName])->asArray()->one(); //выбираем строку с нужным id
            //$model = new Gun(); //говорят, лишняя
            $model = new User_gun(); //говорят, лишняя
            if ($checkboxPosition == 'true') {    //записать в user_gun связь
                $model->user_id = $userId['id']; 
                $model->gun_id = $gunId; 
                $model->save(); //сохраняем объект модели
            } else {                            //найти и удалить из user_gun связь
                //$userId = $userId['id'];
                $model = User_gun::find()->where(['user_id' => $userId['id']]) ->andWhere(['gun_id' => $gunId])->one()->delete(); 

            } 
            echo json_encode("$gunId");
        }
        
    }
    
    //+Акшен срабатывает при посещении страницы signup, либо при вводе данных с неё
    public function actionSignup(){
        //Проверка, является ли пользователь гостем
    ///    if (!Yii::$app->user->isGuest) {
    ///        return $this->goHome(); //Если НЕ гость, то редирект на домашнюю страницу
    ///    }
        $model = new SignupForm();  //создаём объект модели UsersForm
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $r1 = Yii::$app->request->post(); //request - это объект, который по умолчанию является экземпляром yii\web\Request.
                                              //у него есть методы get() и post()
            $user = new User(); //создаём объект модели User (эта модель указана в качестве компонента идентификации в файле config\web.php)
            $user->username = $model->username; //передаём атрибут модели UsersForm в атрибут модели User 
            $user->full_name = $model->full_name; //(заполним его полученными из формы данными)
            $user->password = \Yii::$app->security->generatePasswordHash($model->password); //Аналогично, только ещё и шифруем
            $user->role = '10'; //Присваиваем новому пользователю права охранника (а права менеджера 20 - только через ручную правку бд)
            $user->save(); //сохраняем объект модели User
        }

        $listUsers = user::find()->all();    //забираем из базы
        $listGun = gun::find()->all();    //забираем из базы
        return $this->render('signup', compact('model', 'listUsers', 'listGun')); //compact('listUsers') - передаём в вид результат 
    }

    
    
    //+Акшен срабатывает при посещении страницы sentry, либо при вводе данных с неё
    public function actionSentry(){
        
        $model = new Sentry();             //создаём объект модели

        /* #Кнопка печати таблицы постовой ведомости. Принимает из js аяксом дату, отдаёт печатаемую таблицу 
        if ( Yii::$app->request->post('add-button') ) {
            $year = Yii::$app->request->post('year');
            $month = Yii::$app->request->post('month'); 
            $day = Yii::$app->request->post('day'); 
            $dateFlight = $year."-".$month."-".$day;
            $listSentry = Sentry::find()->where(['date' => $dateFlight])->asArray()->all();    //забираем из базы
            
            #Рисуем таблицу маршрутов
            if ($listSentry != NULL) { //иначе варнинги идут, если пусто 
                $tyu = "<table>";
                $tyu .= "<h1>Постовая ведомость за $day $month $year</h1>"; //Название таблицы
                #Рисуем шапку таблицы
                $tyu .= "<tr>
                            <td><b>№</b></td> 
                            <td><b>№ поста/<br />маршрута</b></td> 
                            <td><b>Ф.И.О. охранника</b></td> 
                            <td><b>Время заступления на службу</b></td>
                            <td><b>Наличие оружия и спецсредств на посту</b></td>
                            <td><b>Время окончания службы</b></td>
                            <td><b>Воемя доклада об обстановке на посту</b></td>
                            <td><b>Примечания</b></td> 
                        </tr>";
                
                #Рисуем строки таблицы
                $i = 1;
                foreach ($listSentry as $key_id => $row_content) { //$key_id - номер строки в таблице, $row_content - массив ячеек в ряду
                    
                    $id_line    = $row_content['id'];           //$id_line - id строки в БД Sentry
                    $fullName   = $row_content['full_name'];    //$fullName - full_name юзера из строки в БД Sentry
                    $gunName    = $row_content['gun'];          //$gun - название оружия
                    
                    $tyu .= "<tr id='sentry-$id_line'>";
                    $tyu .= "<td><input type='text' id='number_line-$i' class='number_line' value='$i' disabled='disabled'> </input></td>"; //Вывод № строки
                    $i = $i + 1;
                                $tyu .= "<td ><div class='$container'><input type='$type' id='$column_name-$id_line' name='$column_name-$id_line' class='$column_name' value='$data'></input></div></td>";
                                break;
                        } 
                    }
                    $tyu .= "</tr>";
                }
                $tyu .= "</table>";
            } else { //Если в таблице нет ни одного рейса за этот месяц и нет рейсов без даты, то:
                $tyu .= "таблица пуста";
            }
               
            
           echo json_encode($photo_array); 
        }  */
        
        #Кнопка добавления строки в таблицу 
        if ( Yii::$app->request->post('add-button') ) {
            $text = '';
            $year = Yii::$app->request->post('year');
            $month = Yii::$app->request->post('month'); 
            $day = Yii::$app->request->post('day'); 
            $dateFlight = $year."-".$month."-".$day;
            //$model->note = '';
            $model->date = $dateFlight;
            $model->save();
        } 

        #Если период введён, подставляем его. Если нет, то подставляем текущий год и месяц
        if( (Yii::$app->request->post('refresh-button')) or (Yii::$app->request->post('add-button')) ){
            $text = 'post';
            $year = Yii::$app->request->post('year');
            $month = Yii::$app->request->post('month'); 
            $day = Yii::$app->request->post('day'); 
        } else {
            $text = 'no_post';
            $year = date("Y");
            $month = date("m"); //SELECT * FROM flight WHERE data_vyezda between '2017-10-01' and '2017-10-31'
            $day = date("d"); 
        }
        
        $table = '40'; //путевая ведомость             !!! Костыль !!!

        
        #забираем из базы все рейсы на дату
        $dateFlight = $year."-".$month."-".$day;
        //$query = "SELECT * FROM sentry WHERE :date";
        //$listSentry = sentry::findBySql($query, [':date' => $dateFlight])->asArray()->all(); 
        //print_r($listSentry);
        $listSentry = Sentry::find()->asArray()->where(['date' => $dateFlight])->all();    //забираем из базы
        $countListSentry = count($listSentry); //кол-во записей за этот день
        
        #Ищем рейсы без даты и добавляем их в таблицу, а если таких нет, то передварительно создаём их
        //$query = "SELECT * FROM sentry WHERE date IS NULL";
        //$listSentryNoDate = sentry::findBySql($query)->asArray()->all(); //получим все записи, сотв. условию
        if ( $countListSentry < 11 ) {
            $n = 11 - $countListSentry;
            //$n = 3;
            for ($j = 1; $j <= $n; $j++) {
                //$text = '';
                $model = new Sentry();
                $model->date = $dateFlight;
                $model->save(); 
                unset($model);
            }
            #И заново вытаскиваем эту пустую строку из базы
            //$query = "SELECT * FROM sentry WHERE date IS NULL";
            //$listSentryNoDate = sentry::findBySql($query)->asArray()->all(); //получим все записи, сотв. условию     
            $listSentry = Sentry::find()->asArray()->where(['date' => $dateFlight])->all();    //забираем из базы            
        }

        #добавляем пустые строки в общий результат
        /* $p = count($listSentry);
        foreach ($listSentryNoDate as $key => $val) {   
            //$flightPhoto[] = $val['path']; 
            $p = $p + 1;
            $listSentry[$p] = $val; 
        }   */
                                    
                                    
        #Вытаскиваем все фамилии охранников
        $listUsers = User::find()->select('full_name')->asArray()->column();    //забираем из базы
        $k = count($listUsers);
        $listUsers[$k+1] = 'Не выбран'; //Добавляем в массив охранников невыбранного 
        
        #Вытаскиваем всё оружие
        $listGuns = Gun::find()->select('name')->asArray()->column();    //забираем из базы
        $k = count($listGuns);
        $listGuns[$k] = 'Оружие не выбрано'; //Добавляем в массив невыбранное оружие
    
        #Вытаскиваем все связи
        $usersGuns = User_gun::find()->with(['user','gun'])->asArray()->all();    //забираем из базы
        //$k = count($listGuns);
        //$listGuns[$k] = 'Не выбран'; //Добавляем в массив невыбранное оружие
        
        #
        $gu = array();
        //$p = 0;
        $userGun    = User_gun::find()->select(['user_id', 'gun_id'])->asArray()->all();    //забираем из базы
        $arrayUsers = User    ::find()->select(['id', 'full_name'])  ->asArray()->all();    //забираем из базы
        $arrayGun   = Gun     ::find()->select(['id', 'name'])       ->asArray()->all();    //забираем из базы
        //$k = count($user_gun);
        /* foreach ($arrayUsers as $key1 => $val1) {           //key1 - номера, val1 - массив [id, full_name] фамилий  $vol1['id'] - id юзера
            foreach ($userGun as $key2 => $val2) {          //key2 - номера, val2 - массив [user_id, gun_id] связей $val2['gun_id'] - id пистолета
                if ($val1['id'] == $val2['user_id']){
                    foreach ($arrayGun as $key3 => $val3) { //key3 - номера, val3 - массив [id, name] оружия
                        $val3['name'] = array();
                        if ($val3['id'] == $val2['gun_id']){
                            $gu[$val1['full_name']] .= $val3['name'];
                        }
                    }
                }
            }
          //  $p                  = $p + 1;
        }  */
    
        
        return $this->render('sentry', compact('model', 'listSentry', 'year', 'month', 'day', 'listUsers', 'listGuns', 'dateFlight', 'userGun', 'usersGuns', 'countListSentry')); //передаём в вид результат 
    }


    


    #+Показ модального окна с фотографиями рейса
    //Посмотреть гайды:
    //http://yiico.ru/blog/493-zagruzka-izobrazhenii-i-failov-v-yii2-ih-sohranenie-v-papku-na-servere-i-v-bd
    //http://web-sprints.ru/yii2-zagruzka-faylov-i-izobrazheniy/
    public function actionGetphoto(){
         #Проверяем, пришли ли данные методом аякс (метод request проверяет, откуда пришли данные - пост, гет, аякс)
         if(Yii::$app->request->isAjax){
            $id_line = Yii::$app->request->post('id_line');

            #Вытаскиваем все пути к фотографиям рейса
            $listPhoto = Photo::find()->asArray()->all();    //забираем из базы
            
            $photo_name_array = null; //собираем в один массив все фото с данного рейса
            $p = 0;
            $photo_array = array(
                0 => "Фотографии отсутствуют"
            );
            foreach ($listPhoto as $key => $val) {
                if ($val['n_flight'] == $id_line) { 
                    //$photo_name_array[$p] = $val['path'];
                    $patchCurrent       = $val['path'];
                    $photo_array[$p]    = "<li><a href='img/photo/$patchCurrent' onclick='selectPhoto();'><figure class='photo_prev'><img id='photo$p' src='img/photo/$val[path]' height='100' alt='$patchCurrent' title='$patchCurrent'> <figcaption>$patchCurrent</figcaption> </figure></a></li>";
                    $p                  = $p + 1;
                } 
            }

            echo json_encode($photo_array);
        }
    }
    

    
    
    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $this->layout = 'simple'; //меняем шаблон на простой
        
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
        //Предполагаю: "Если данные пришедшие из формы ввода загружены в модель И login() прошёл удачно, то редирект к последней посещённой странице
        /* if ($model->load(Yii::$app->request->post()) && $model->login()) {
            //return $this->goBack(); // goBack()-метод Redirects the browser to the last visited page.
            
            #Вытаскиваем имя залогиненного юзера и редиректим на нужный интерфейс
            $nameUser = \Yii::$app->user->identity->username;
            if ($nameUser == 'manager') {
                return $this->redirect(['manager']);
            } else {
                return $this->redirect(['guard']);
            }
        } */


        #Проверка прав пользователя
        if ($model->load(Yii::$app->request->post()) && $model->loginAdmin() ) {
            //return $this->goBack();
            return $this->redirect(['manager']);
        } 
        if ($model->load(Yii::$app->request->post()) && $model->loginUser() ) {
            //return $this->goBack();
            return $this->redirect(['guard']);
        } 
        return $this->render('login', [
            'model' => $model,
        ]);

        
        
        //Иначе снова отрендерить страницу login, передав в неё $model 
        return $this->render('login', compact('model'));
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
        //создать роль и сохранить ее в RBAC
       /*  $role = Yii::$app->authManager->createRole('manager');
        $role->description = 'Менеджер';
        Yii::$app->authManager->add($role);
         
        $role = Yii::$app->authManager->createRole('user');
        $role->description = 'Охранник';
        Yii::$app->authManager->add($role); */
        
        
        
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
    
    
    
#####  Ниже только неиспользуемые куски кода  ##########################################################    
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
##########################################################    
    
    
    
}




