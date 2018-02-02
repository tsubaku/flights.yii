<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;

use app\models\Settings;    //таблица настроек;
use app\models\User;        //встроенная авторизация;
use app\models\Gun;        //УБРАТЬ;

# Класс страницы настроек
class SettingsController extends Controller
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
                'only' => ['gun'],
                'rules' => [    //страницы, доступные менеджеру:
                    [
                       'actions' => ['gun'],
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
                    'gun' => ['get', 'post'],
                ],
            ],
        ];
    }
    
    
    #+Отрисовка страницы settings и добавление оружия, если нажата копка добавления
    public function actionSettings()
    {      
        $model = new Settings();  //создаём объект модели 
        
        #Если нажали "Изменить шапку постовой ведомости", то проверяем введённые данные и обновляем
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $sentryHeaderText = Yii::$app->request->post('Settings'); //request - объект, который по умолчанию является экземпляром yii\web\Request.
                                                              //у него есть методы get() и post()
            //$model->content = $sentryHeaderText['content']; 
            //$model->save(); //сохраняем объект модели
            
            #Обновить ячейку в таблице 
            //$model = Settings::findOne(['name' => 'sentryHeaderText']); //Выбрать из таблицы первую запись с id=$cellId
            //$model = Settings::findOne(['name' => 'sentryHeaderText']); //Выбрать из таблицы первую запись с id=$cellId
            //$model->content = $sentryHeaderText;   //Выбрать из этой записи ячейку в столбце $cellColumn и записать туда $cellValue
            //$model->save();                     //сохранить
            
            
            /* $cellValue = $sentryHeaderText;   
            $cellId = 1;
            $cellColumn = 'content';

            #Обновить ячейку в таблице 
            $model = Settings::findOne()->where(['name' => 'sentryHeaderText']) ; //Выбрать из таблицы первую запись с id=$cellId
            $model->$cellColumn = $cellValue;   //Выбрать из этой записи ячейку в столбце $cellColumn и записать туда $cellValue
            $model->save();                     //сохранить
             */
            
            
        }
        return $this->render('settings', compact('model', 'sentryHeaderText')); //передаём в вид результат  
    }
  
    
}




