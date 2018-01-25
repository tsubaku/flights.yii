<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;

use app\models\Gun;         //список оружия;


class GunController extends Controller
{

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

        $listGun = Gun::find()->orderBy(['name' => 'SORT_ASC'])->all();    //забираем из базы
        return $this->render('gun', compact('model', 'listGun')); //передаём в вид результат   
    }
    
    
    
    
}




