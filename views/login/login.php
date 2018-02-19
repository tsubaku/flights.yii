<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Авторизация';
//$this->params['breadcrumbs'][] = $this->title;
?>
<!-- <div class="site-login"> -->

<div class="row">
    <h1><?= Html::encode($this->title) ?></h1>
</div>

<?php $form = ActiveForm::begin([
    'id' => 'login-form',
    'layout' => 'horizontal',
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
        'labelOptions' => ['class' => 'col-lg-4 col-md-3 col-sm-6 col-xs-12 control-label text-centre'],
    ],
]); ?>

<div class="row">     
    <?= $form->field($model, 'username')->textInput([
            'autofocus' => true,
            'placeholder' => 'Логин', 
        ]) 
            //метод field принимает 2 параметра: модель и арибут данной модели (имя поля, которое мы создаём)
            //метод textInput() -Renders a text input.
    ?>
</div>
        
<div class="row">
    <?= $form->field($model, 'password')->passwordInput([
        'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
        'placeholder' => 'Пароль',
    ]) ?>
</div>
        
<div class="row">   
    <?= $form->field($model, 'rememberMe', [
        'template' => "{label}<div class=\"col-lg-offset-1 col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
    ])->checkbox([],false)  ?>
</div>

<div class="row">  
    <div class="col-xs-1">
        <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>


<?php //echo "sig= ".$sig; ?>

