<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;

use app\models\User;        //встроенная авторизация;
use app\models\User_gun;    //таблица связей юзер-оружие (многие ко многим);
use app\models\Sentry;      //таблица постовой ведомости
use app\models\Settings;    //таблица постовой ведомости

# Класс страницы Постовая ведомость
class SentryController extends Controller
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
                'only' => ['sentry'],
                'rules' => [    //страницы, доступные админу и оператору:
                    [
                       'actions' => ['sentry'],
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                           return User::isUserAdmin(Yii::$app->user->identity->username);
                       }
                    ],
                    [
                       'actions' => ['sentry'],
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                           return User::isUserOperator(Yii::$app->user->identity->username);
                       }
                    ],
                ],
            ],
            'verbs' => [        //Доступные запросы
                'class' => VerbFilter::className(),
                'actions' => [
                    'sentry' => ['get', 'post'],
                ],
            ],
        ];
    }
    
    
    #+Изменение данных в ячейке таблицы Постовая ведомость. 
    # Получить от core.js id строки, название столбца и данные. Внести изменения в таблицу базы. 
    # Вернуть те же данные, плюс список оружия на охраннике, если изменено его имя
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
    
    
    //+Получить от manager.js событие "установить текущее время", сделать изменения в базе Sentry, вернуть в js текущие дату и время
    public function actionSettime(){
        if(Yii::$app->request->isAjax){
            $columnInDb  = Yii::$app->request->post('columnInDb');
            $idInDb      = Yii::$app->request->post('idInDb');

            $currentDate = date('Y-m-d');
            $currentTime = date('H:i:s');
            
            $model = Sentry::findOne($idInDb);  //Выбрать из таблицы первую запись с id=$idInDb
            $model->$columnInDb = $currentTime; //Выбрать из этой записи ячейку в столбце $columnInDb и записать туда $currentTime
     
            if ($columnInDb =='time_on') {
                $model->date = $currentDate;   
            }
            if ($columnInDb =='time_off') {
                $model->date_off = $currentDate;   
            }
            $model->save();                     //сохранить
            
            $currentDateTime = array(
                currentDate => $currentDate,
                currentTime => $currentTime
            ); 
            echo json_encode($currentDateTime);
        }
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
            //$text           = '';
            $year           = Yii::$app->request->post('year');
            $month          = Yii::$app->request->post('month'); 
            $day            = Yii::$app->request->post('day'); 
            $dateFlight     = $year."-".$month."-".$day;
            //$model->note = '';
            $model->date    = $dateFlight;
            $model->date_off= $dateFlight;
            $model->save();
        } 

        #Если период введён, подставляем его. Если нет, то подставляем текущий год и месяц
        if( (Yii::$app->request->post('refreshButton')) or (Yii::$app->request->post('add-button')) ){
            //$text = 'post';
            $year = Yii::$app->request->post('year');
            $month = Yii::$app->request->post('month'); 
            $day = Yii::$app->request->post('day'); 
        } else {
            //$text = 'no_post';
            $year = date("Y");
            //$month = date("m"); 
            $month = date("n"); //№ месяца без ведущего нуля
            //$month = "1"; 
            $day = date("d"); 
        }
        
        
        #забираем из базы все маршруты на выбранную дату
        $dateFlight = $year."-".$month."-".$day;
        $listSentry = Sentry::find()->asArray()->where(['date' => $dateFlight])->orderBy(['time_on' => 'SORT_ASC'])->all();//забираем из базы
        $countListSentry = count($listSentry); //кол-во записей за этот день
        
        #Проверяем количество маршрутов. Если меньшще 11, то создаём их до 11 на выбранную дату.
        if ( $countListSentry < 11 ) {
            $n = 11 - $countListSentry;
            for ($j = 1; $j <= $n; $j++) {
                $model = new Sentry();
                $model->date = $dateFlight;
                $model->save(); 
                unset($model);
            }
            $listSentry = Sentry::find()->asArray()->where(['date' => $dateFlight])->all();    //обновляем список рейсов на выбранную дату
        }

        #Добавляем охранников, которым было выдано оружее ранее и которые его ещё не сдали
        //$listSentryNotReturned = Sentry::find()->asArray()->where(['AND', ['<', 'date', $dateFlight], ['OR', ['>=', 'date_off', $dateFlight], ['date_off' => 0000-00-00]]])->orderBy(['date' => 'SORT_ASC'])->all();    //(выдано раньше выбранной даты) и ([сдано позже выбранной даты] или [не сдано])       
        $listSentryNotReturned = Sentry::find()->asArray()->where(['AND', ['AND', ['<', 'date', $dateFlight], ['OR', ['>=', 'date_off', $dateFlight], ['date_off' => 0000-00-00]]], ['AND', ['NOT IN', 'full_name', ''], ['NOT IN', 'full_name', 'Не выбран']]])->orderBy(['date' => 'SORT_ASC'])->all();    //(выдано раньше выбранной даты) И ([сдано позже выбранной даты] ИЛИ [не сдано]) И full_name не пусто И full_name не 'Не выбран'
        
        $listSentry = array_merge($listSentry, $listSentryNotReturned);

        #Вытаскиваем все фамилии охранников
        $listUsers = User::find()->select('full_name')->where(['department' => 'Сопровождение'])->asArray()->orderBy(['full_name' => 'SORT_ASC'])->column();    //забираем из базы
        $k = count($listUsers);
        $listUsers[$k+1] = 'Не выбран'; //Добавляем в массив охранников невыбранного 
        
        #Вытаскиваем всё оружие
        //$listGuns = Gun::find()->select('name')->asArray()->column();    //забираем из базы
        //$k = count($listGuns);
        //$listGuns[$k] = 'Оружие не выбрано'; //Добавляем в массив невыбранное оружие
    
        #Вытаскиваем все связи
        $usersGuns = User_gun::find()->with(['user','gun'])->asArray()->all();    //забираем из базы
        
        #
        //$userGun    = User_gun::find()->select(['user_id', 'gun_id'])->asArray()->all();    //забираем из базы
        //$arrayUsers = User    ::find()->select(['id', 'full_name'])  ->asArray()->all();    //забираем из базы
        //$arrayGun   = Gun     ::find()->select(['id', 'name'])       ->asArray()->all();    //забираем из базы
        //$gu = array();
        //$p = 0;
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
        
        $sentryHeaderText = Settings::find()->select('content')->where(['name' => 'sentryHeaderText'])->asArray()->column();    //забираем из базы шапку
    
        return $this->render('sentry', compact('model', 'listSentry', 'listUsers', 'usersGuns', 'year', 'month', 'day', 'sentryHeaderText')); //передаём в вид результат 
    }
  
    
}




