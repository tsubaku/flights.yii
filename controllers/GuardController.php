<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;

use app\models\Flight;      //таблица рейсов;
use app\models\Photo;       //таблица фотографий, присылаемых охранниками;
use app\models\User;        //встроенная авторизация;

# Класс страницы Охранник (интерфейс охранника)
class GuardController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        //Разрешить доступ любому аутентифицированному юзеру
        return [
            'access' => [
                'class' => AccessControl::className(),  
                'only' => ['guard'],
                'rules' => [    //страницы, доступные менеджеру:
                    [
                       'actions' => ['guard'],
                       'allow' => true,
                       'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [        //Доступные запросы
                'class' => VerbFilter::className(),
                'actions' => [
                    'guard' => ['get', 'post'],
                ],
            ],
        ];
    }
    
    
    #+Показ календаря на странице интерфейса охранника
    public function actionShowflight()
    {      
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
    
    
    #+Загрузка фото для рейса из интерфейса охранника
    # Принимает из core.js FormData() (т.е. файл с доп.данными), возвращает обратно ответ сервера
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
    
}




