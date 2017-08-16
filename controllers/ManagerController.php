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

 
 
}
