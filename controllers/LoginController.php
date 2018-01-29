<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;

 use app\models\LoginForm;
//use app\models\ContactForm;


use app\models\User;        //встроенная авторизация;




# Login/Logout
class LoginController extends Controller
{
  

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $this->layout = 'simple'; //меняем шаблон на простой
        
        //user - компонент содержит постоянную информацию о текущем пользователе
        //получить к ней доступ из любого места приложения: Yii::app()->user
        //isGuest - проверить, является ли пользователь гостем
        //Для проверки прав на определённые действия удобно воспользоваться CWebUser::checkAccess. Также есть возможность получить уникальный идентификатор и другие постоянные данные пользователя.
        if (!Yii::$app->user->isGuest) {
            return $this->goHome(); //Если юзер не гость, то Redirects the browser to the home page.
        }
        
        //Если же он пока не авторизован, то:
        $model = new LoginForm(); //создаём объект модели LoginForm
        //$model->load(Yii::$app->request->post()) -загрузка атрибутов в модель (предполагаю, что от пользователя из формы ввода логина/пароля)
        //login() - public method, sets the specified identity and remembers the authentication status in session and cookie
        //$model->login() -применяем метод login() к модели
        //Предполагаю: "Если данные пришедшие из формы ввода загружены в модель И login() прошёл удачно, то редирект к последней посещённой странице
        /* if ($model->load(Yii::$app->request->post()) && $model->login()) {
            //return $this->goBack(); // goBack()-метод Redirects the browser to the last visited page.
            
            #Вытаскиваем имя залогиненного юзера и редиректим на нужный интерфейс
            $nameUser = \Yii::$app->user->identity->username;
            if ($nameUser == 'manager') {
                return $this->redirect(['manager']);
            } else {
                return $this->redirect(['guard']);
            }
        } */


        #Проверка прав пользователя
        if ($model->load(Yii::$app->request->post()) && $model->loginAdmin() ) {
            //return $this->goBack();
            return $this->redirect(['sentry/sentry']);
        } 
        if ($model->load(Yii::$app->request->post()) && $model->loginOperator() ) {
            //return $this->goBack();
            return $this->redirect(['sentry/sentry']);
        } 
        if ($model->load(Yii::$app->request->post()) && $model->loginUser() ) {
            //return $this->goBack();
            return $this->redirect(['guard/guard']);
        } 
        //Иначе снова отрендерить страницу login, передав в неё $model 
        return $this->render('login', compact('model'));
        
        /* return $this->render('login/login', [
            'model' => $model,
        ]); */

        //Иначе снова отрендерить страницу login, передав в неё $model 
        //return $this->render('login/login', compact('model'));
    }

    


    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
    
    
  
    
    
}




