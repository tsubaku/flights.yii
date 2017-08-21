<?php

namespace app\controllers;

////use Yii;
//use yii\filters\AccessControl;
use yii\web\Controller;
//use yii\web\Response;
//use yii\filters\VerbFilter;
//use app\models\LoginForm;
//use app\models\ContactForm;

class ManagerController extends Controller
{
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionManager()
    {
        $hi = 'manager-controller-';
        return $this->render('manager', compact('hi'));
    }
    
    
    public function actionDeleteRow($id, $nameTable){
        #Проверяем, пришли ли данные методом аякс (метод request проверяет, откуда пришли данные - пост, гет, аякс)
        /* if(Yii::$app->request->isAjax){
            $idRow = Yii::$app->request->post('id_line');
            print_r($idRow);
            $model = new Clients();
            $model = Clients::find()->where(['id' => $idRow])->one();// выбираем из таблицы строку с нужным id
            //$model = Article::findOne($idRow); //php падает с ошибкой от этого запроса
            $model->delete();// удаляем строку

            $listClients = Clients::find()->all();    //забираем из базы
            return $this->render('clients', compact('listClients')); //compact('listClients') - передаём в вид результат 
        }*/
        $listClients = Clients::find()->all();    //забираем из базы 
        return $this->render('clients', compact('listClients')); //compact('listClients') - передаём в вид результат   
    }
 
 
}
