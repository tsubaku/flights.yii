<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;

use app\models\Client;      //список фирм клиентов;
use app\models\User;        //встроенная авторизация;
use app\models\Flight;      //таблица рейсов;
use app\models\Photo;       //таблица фотографий, присылаемых охранниками;

# Класс страницы Рейсы 
class ManagerController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        //Разрешить доступ только админу
        return [
            'access' => [
                'class' => AccessControl::className(),  
                'only' => ['manager'],
                'rules' => [    //страницы, доступные менеджеру:
                    [
                       'actions' => ['manager'],
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                           return User::isUserAdmin(Yii::$app->user->identity->username);
                       }
                    ],
                ],
            ],
            'verbs' => [        //Доступные запросы
                'class' => VerbFilter::className(),
                'actions' => [
                    'manager' => ['get', 'post'],
                ],
            ],
        ];
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
        if( (Yii::$app->request->post('refreshButton')) or (Yii::$app->request->post('add-button')) ){
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
        
        #забираем из базы все рейсы  between 1 and 31
        $date1 = $year."-".$month."-01";
        $date2 = $year."-".$month."-31";
        $query = "SELECT * FROM flight WHERE data_vyezda between :date1 and :date2";
        $listFlight = flight::findBySql($query, [':date1' => $date1, ':date2' => $date2])->with('photo')->asArray()->all(); 
        
        #Ищем рейсы без даты и добавляем их в таблицу, а если таких нет, то передварительно создаём один такой
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

        #Вытаскиваем все пути к фотографиям рейса
        $listPhoto = Photo::find()->asArray()->all();    //забираем из базы

        return $this->render('manager', compact('model', 'listFlight', 'year', 'month', 'ru_rows_array', 'listUsers', 'listClients', 'listPhoto', 'text')); //передаём в вид результат 
    }
    

    #+Изменение данных в ячейке таблицы рейсов
    # Принимает данные из core.js, вставляет в таблицу Flight, если необходимо, то рассчитывает зависимые ячейки
    # и возвращает результаты обратно для отрисовки.
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
                    $model->save();           

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
                        $fakticheskij_srok_dostavki = "--:--";  
                    }
                    
                    if ($sdacha == NULL) {
                        $fakticheskij_srok_dostavki = "В рейсе"; 
                    }
                    if ($prinjatie == NULL) {
                        $fakticheskij_srok_dostavki = "--:--"; 
                    }
                    
                    $res_array["fakticheskij_srok_dostavki"] = $fakticheskij_srok_dostavki; 

                    $model->fakticheskij_srok_dostavki = $fakticheskij_srok_dostavki;   
                    $model->save();                    
  
                    break;                

                case 'prostoj_chasy':
                case 'prostoj_stavka_za_ohrannika':
                    $prostoj_chasy               = intval($line_array['prostoj_chasy']);
                    $prostoj_stavka_za_ohrannika = intval($line_array['prostoj_stavka_za_ohrannika']);
                    $prostoj_summa               = $prostoj_chasy * $prostoj_stavka_za_ohrannika * 2;

                    $model->prostoj_summa = $prostoj_summa;   
                    $model->save();                    

                    $res_array["prostoj_summa"] = $prostoj_summa; 
                    
                    //От prostoj_summa зависит schet, так что пересчитываем его
                    $stavka_bez_nds = intval($line_array['stavka_bez_nds']);
                    $stavka_s_nds   = intval($line_array['stavka_s_nds']);
                    $schet          = $prostoj_summa + $stavka_bez_nds + $stavka_s_nds;

                    $model->schet = $schet;   
                    $model->save();                     

                    $res_array["schet"] = $schet; 
                    break;
                    
               case 'arenda_mashin':
                    $arenda_mashin = intval($line_array['arenda_mashin']);
                    $oplata_mashin = $arenda_mashin * 1700;

                    $model->oplata_mashin = $schet;  
                    $model->save();                     
                    $res_array["oplata_mashin"] = $oplata_mashin; 
                    
                    //От arenda_mashin зависит itogo, так что пересчитываем его
                    $zp      = intval($line_array['zp']);
                    $prostoj = intval($line_array['prostoj']);
                    $itogo   = $zp + $prostoj + $oplata_mashin;

                    $model->itogo = $itogo;   
                    $model->save();                     
                    $res_array["itogo"] = $itogo; 
                    break;     
                    
               case 'zp':
               case 'prostoj':
               case 'oplata_mashin':
                    $zp            = intval($line_array['zp']);
                    $prostoj       = intval($line_array['prostoj']);
                    $oplata_mashin = intval($line_array['oplata_mashin']);
                    $itogo         = $zp + $prostoj + $oplata_mashin;

                    $model->itogo = $schet;   
                    $model->save();                     
                    $res_array["itogo"] = $itogo; 
                    
                    $zp_plus_prostoj = $zp + $prostoj;

                    $model->zp_plus_prostoj = $zp_plus_prostoj;   
                    $model->save();                     
                    $res_array["zp_plus_prostoj"] = $zp_plus_prostoj; 
                    break;
                
                default:
                    break;     
                                   
            }
            echo json_encode($res_array);
        }
    }
    

    #+Показ модального окна с фотографиями рейса
    # Принимает данные от manager.js, вытаскивает прикреплённые к рейсу фотографии и отдаёт их обратно
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
    

}




