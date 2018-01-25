<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;

use app\models\Client;      //список фирм клиентов;

class ClientController extends Controller
{
    
    #+Отрисовка страницы client и добавление клиента, если нажата копка добавления
    public function actionClient()
    {      
        $model = new Client();  //создаём объект модели 
        
        #Если нажали "Добавить клиента", то проверяем введённые данные и добавляем
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $clientName = Yii::$app->request->post('Client'); //request - объект, который по умолчанию является экземпляром yii\web\Request.
                                                              //у него есть методы get() и post()
            $model->name = $clientName['name']; 
            $model->save(); //сохраняем объект модели
        }

        $listClients = Client::find()->orderBy(['name' => 'SORT_ASC'])->all();    //забираем из базы
        return $this->render('client', compact('model', 'listClients')); //передаём в вид результат   
    }
    

}




