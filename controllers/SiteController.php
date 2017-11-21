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
use app\models\User;    //Подключаем модель для авторизации;
use app\models\Flight;  //Подключаем модель для обработки таблицы рейсов;
use app\models\Photo;   //Подключаем модель для обработки таблицы фотографий;
use app\models\SignupForm; //Подключаем модель для обработки списка охранников

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        //only -фильтр ACF нужно применять только к действиям logout
        //rules -задаёт правила доступа    
            //Разрешить всем гостям (ещё не прошедшим авторизацию) доступ к действиям login и signup. Опция roles содержит знак вопроса ?, это специальный токен обозначающий "гостя".
            //Разрешить аутентифицированным пользователям доступ к действию logout. Символ @ — это другой специальный токен, обозначающий аутентифицированного пользователя.
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
                    [
                        'allow' => true,
                        'actions' => ['login', 'signup'],
                        'roles' => ['?'],
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

        $full_name = \Yii::$app->user->identity->username;
        $idUser = \Yii::$app->user->identity->id;
        
        #Вытаскиваем из базы даты выездов нужного охранника     
        $query = 'SELECT `data_vyezda` FROM `flight` WHERE `fio` = :full_name';
        $table_array = flight::findBySql($query, [':full_name' => $full_name])->asArray()->all(); //$table_array - массив всех дат выезда указанного охранника
            
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
            $text = '5';
            $model->podklient = $text;
            $model->save();
        } 

        #Если период введён, подставляем его. Если нет, то подставляем текущий год и месяц
        if( Yii::$app->request->post('refresh-button') ){
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
            }
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

            $user->save(); //сохраняем объект модели User
        }

        $listUsers = user::find()->all();    //забираем из базы
        return $this->render('signup', compact('model', 'listUsers', 'r1', 'r2')); //compact('listUsers') - передаём в вид результат 
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
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            //return $this->goBack(); // goBack()	-метод Redirects the browser to the last visited page.
            
            #Вытаскиваем имя залогиненного юзера и редиректим на нужный интерфейс
            $nameUser = \Yii::$app->user->identity->username;
            if ($nameUser == 'manager') {
                return $this->redirect(['manager']);
            } else {
                return $this->redirect(['guard']);
            }
        }

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




