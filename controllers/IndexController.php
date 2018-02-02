<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;

use app\models\User;        //встроенная авторизация;

# Класс страницы index (она же Справка)
class IndexController extends Controller
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
                'only' => ['index'],
                'rules' => [    //страницы, доступные админу и оператору:
                    [
                       'actions' => ['index'],
                       'allow' => true,
                       'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [        //Доступные запросы
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get', 'post'],
                ],
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


}




