<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;

use app\models\Gun;         //список оружия;
use app\models\User;        //встроенная авторизация;

# Класс страницы Оружие
class GunController extends Controller
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
    
    #+Отрисовка страницы gun и добавление оружия, если нажата копка добавления
    public function actionGun()
    {      
        $model = new Gun();  //создаём объект модели 
        
        #Если нажали "Добавить", то проверяем введённые данные и добавляем
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $gunName = Yii::$app->request->post('Gun'); //request - объект, который по умолчанию является экземпляром yii\web\Request.
                                                      //у него есть методы get() и post()
            $model->name = $gunName['name']; 
            $model->save(); //сохраняем объект модели
        }

        $listGun = Gun::find()->orderBy(['name' => 'SORT_ASC'])->all();    //забираем из базы
        return $this->render('gun', compact('model', 'listGun')); //передаём в вид результат   
    }
    
    
    
    
}




