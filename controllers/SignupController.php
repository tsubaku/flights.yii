<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;

use app\models\Gun;         //список оружия;
use app\models\User;        //встроенная авторизация;
use app\models\User_gun;    //таблица связей юзер-оружие (многие ко многим);
use app\models\SignupForm;  //таблица охранников и прочих юзеров


class SignupController extends Controller
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
                'only' => ['signup'],
                'rules' => [    //страницы, доступные менеджеру:
                    [
                       'actions' => ['signup'],
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
                    'signup' => ['get', 'post'],
                ],
            ],
        ];
    }

    #+Изменение данных в ячейке таблицы User. Пока что используется только для смены отдела охранника.
    #+Принять данные от core.js, внести в таблицу User
    public function actionChangeuser(){
         #Проверяем, пришли ли данные методом аякс (метод request проверяет, откуда пришли данные - пост, гет, аякс)
         if(Yii::$app->request->isAjax){
            $cellValue = Yii::$app->request->post('cell_value');   
            $cellId = Yii::$app->request->post('id_in_db');
            $cellColumn = Yii::$app->request->post('column_in_db');

            #Обновить ячейку в таблице 
            $model = User::findOne($cellId); //Выбрать из таблицы первую запись с id=$cellId
            $model->$cellColumn = $cellValue;   //Выбрать из этой записи ячейку в столбце $cellColumn и записать туда $cellValue
            $model->save();                     //сохранить
 
            //$res_array = [$cellValue, $cellId, $cellColumn, $listGun];
            //echo json_encode($res_array);
        }
    }
    

 
    
    #+Принять от manager.js id юзера, вытащить связь, вытащить прикреплённое оружие, вернуть js-скрипту
    public function actionGunshow(){
        if(Yii::$app->request->isAjax){
            $userId  = Yii::$app->request->post('userId');
            $listGun = User_gun::find()->where(['user_id' => $userId])->asArray()->all(); //выбираем строку с нужным id

            $p = 0;
            foreach ($listGun as $key => $val) {        
                $gun_id         = $val['gun_id'];
                $gun = Gun::find()->where(['id' => $gun_id])->asArray()->one(); //выбираем строку с нужным id
                $listGunId[$p]  = $gun['id'];
                $p              = $p + 1;
            }
            
            echo json_encode($listGunId);
        }
    }
    
    
    //+Получить от manager.js событие чекбокса, сделать изменения в базе user_gun
    public function actionCheckboxchange(){
        if(Yii::$app->request->isAjax){
            $userName           = Yii::$app->request->post('userName');
            $gunId              = Yii::$app->request->post('gunId');
            $checkboxPosition   = Yii::$app->request->post('checkboxPosition');
            
            $userId = User::find()->where(['full_name' => $userName])->asArray()->one(); //выбираем строку с нужным id
            $model = new User_gun(); //говорят, лишняя
            if ($checkboxPosition == 'true') {    //записать в user_gun связь
                $model->user_id = $userId['id']; 
                $model->gun_id = $gunId; 
                $model->save(); //сохраняем объект модели
            } else {                            //найти и удалить из user_gun связь
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
            $user->department = $model->department; 
            $user->save(); //сохраняем объект модели User
        } else {
            //иначе то же самое, но пишем 
            //$listUsers = user::find()->all();    //забираем из базы
            //$listGun = gun::find()->all();    //забираем из базы
            $error = 'error validate actionSignup';
            //return $this->render('signup', compact('model', 'listUsers', 'listGun', 'error')); //compact('listUsers') - передаём в вид результат 
        }

        $listUsers = user::find()->orderBy(['full_name' => 'SORT_ASC'])->all();    //забираем из базы
        $listGun = gun::find()->all();    //забираем из базы
        return $this->render('signup', compact('model', 'listUsers', 'listGun', 'error')); //compact('listUsers') - передаём в вид результат 
    }


    
    
    
}




