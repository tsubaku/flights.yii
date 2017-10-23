<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;


$this->title = 'Охранники';
//$this->params['breadcrumbs'][] = $this->title;

$table_users = '10'; //охранники             !!! Костыль !!!
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
 


  
<!-- Регистрация юзеров --->
<?php $form = ActiveForm::begin([
        'id' => 'add',
        'layout' => 'inline',
        'fieldConfig' => [
            'labelOptions' => ['class' => ''],
            'enableError' => true,
        ]
    ]) ?>

<!-- [
        'layout' => 'inline',
        'fieldConfig' => [
            'labelOptions' => ['class' => ''],
            'enableError' => true,
        ]
    ] -->

<?= $form->field($model, 'username', [
        'template' => '{label} <div class="row"><div class="col-sm-2">{input}{error}{hint}</div></div>'
    ]) 
    //username -атрибуты модели UsersFofm
    //метод field принимает 2 параметра: модель и арибут данной модели (имя поля, которое мы создаём)
?>
<?= $form->field($model, 'full_name', [
        'template' => '{label} <div class="row"><div class="col-sm-2">{input}{error}{hint}</div></div>'
    ]) 
?>
<?= $form->field($model, 'password', [
        'template' => '{label} <div class="row"><div class="col-sm-2">{input}{error}{hint}</div></div>'
    ])->passwordInput() ?>
    <div class="form-group">
        <div>
            <?= Html::submitButton('Регистрация', ['class' => 'btn btn-success', 'id' => 'add1', 'name' => 'add2']) ?>
        </div>
    </div>
<?php ActiveForm::end() ?>   


 
<div class="row">    
    <div class="table table-striped">
     <table style="width: 600px;" class="table table-striped">
     <caption><strong>Список охранников</strong></caption>
        <thead>
            <tr>  
                <td><b>№</b></td> 
                <td><b>Логин</b></td> 
                <td><b>Полное имя</b></td> 
                <td><b>Удалить</b></td>
            </tr>
        </thead>
        <tbody id="usersTable">
            <?php
            #Выводим последовательно строки с именами клиентов, начиная с порядкового номера 
            $i = 1;   
            foreach ($listUsers as $CurrentUser){                 
                $user_id     = $CurrentUser['id'];          //id охранника
                $user_login  = $CurrentUser['username'];    //login охранника
                $full_name   = $CurrentUser['full_name'];   //Фамилия охранника       
            ?>
            <tr id='userName-<?=$user_id?>'>
                <td style="width: 50px;"><?=$i?></td> 
                <td><?=$user_login?></td> 
                <td><?=$full_name?></td> 
                <td style="width: 70px;"><button type='button' class='btn btn-sm btn-danger' onclick='delete_line(<?=$user_id?>, <?=$table_users?>);'>Удалить</button></td>
                        
                    
            </tr>
        <?php $i = $i + 1; } ?>  
        </tbody>
      </table>
    </div>
</div>
<br />



<div id="status">					
</div>
<!--
<button class="btn btn-success" id="btn">Click</button>
-->
<?php
/* $js = <<< JS
    $('#btn').on('click', function(e) {
        $.ajax({
            url: 'index.php?r=site/clients',
            data: {test: '123'},
            type: 'POST',
            success: function(res){
                console.log(res);
            },
            error: function(){
                alert('error ajax');
            }
        });
    });
JS;
$this->registerJs($js); //регистрируем скрипт */



?>        


<div>
   <!-- <form class="form-horizontal"> -->
    <!--
        <div class="form-group row">
            <div class="col-sm-2 ">
                <label for="login" class="control-label">Логин:</label>
                <input type="text" class="form-control" id="login" name="login">
            </div>  
          
            <div class="col-sm-2">
                <label for="password" class="control-label">Пароль:</label>
                <input type="text" class="form-control" id="password" name="password">
            </div>  
          
            <div class="col-sm-2">
                <label for="fullName" class="control-label">Полное имя:</label>
                <input type="text" class="form-control" id="fullName" name="fullName">
            </div>
            <button class="col-sm-2 btn btn-success" id="a_register_user" onclick="register_user();">Зарегистрировать</button>
        </div>
    -->     
  <!--    </form>     -->
  
</div>

<?php 
    /* echo '<pre>'; 
    echo 'r1= '; 
    print_r($r1); 
    echo 'r2= '; 
    print_r($r2);  */
?> 


<!-- ---> 
    
    
</div>
