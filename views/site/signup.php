<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;


$this->title = 'Охранники';
//$this->params['breadcrumbs'][] = $this->title;

$table_users = '10'; //охранники             !!! Костыль !!!
?>
<div class="container-fluid">
    
    <h1><?= Html::encode($this->title) ?></h1>
 


  
    <!-- Регистрация юзеров --->
    <?php $form = ActiveForm::begin([
            'id' => 'add',
            'layout' => 'inline',
            'fieldConfig' => [
                'labelOptions' => ['class' => ''],
                'enableError' => true,
            ]
        ]) 
    ?>

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


     
    <div class="row-fluid">    
        <div class="col-md-7">
         <table class="table table-striped">
         <caption><strong>Список охранников</strong></caption>
            <thead>
                <tr>  
                    <td><b>№</b></td> 
                    <td><b>Логин</b></td> 
                    <td><b>Полное имя</b></td> 
                    <td><b>Удалить</b></td>
                    <td><b>Оружие</b></td>
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
                    <td style="width: 70px;"><button type='button' class='btn btn-sm btn-danger' onclick='gun(<?=$user_id?>, <?=$table_users?>);'>Показать</button></td>
                            
                        
                </tr>
            <?php $i = $i + 1; } ?>  
            </tbody>
          </table>
        </div>
        <div class="col-md-4">	
            <div class="panel panel-default">
                <div class="panel-body">
                    <table class="table table-striped" id="tableGun">
                        <caption><strong>Список оружия</strong></caption>
                        <thead>
                            <tr>  
                                
                                <td><b>№</b></td> 
                                <td><b>Оружие</b></td>
                            </tr>
                        </thead>
                        <tbody id="gunTable">
                            <?php
                            #Выводим последовательно строки с именами клиентов, начиная с порядкового номера 
                            $j = 1;   
                            foreach ($listGun as $CurrentGun){                 
                                $gun_id      = $CurrentGun['id'];          //id охранника
                                $gun_name     = $CurrentGun['name'];          //название ствола
    
                            ?>
                            <tr id='gunName-<?=$gun_id?>'>
                                 
                                <td style="width: 50px;"><label class="checkbox"><input type="checkbox" value=""><?=$j?></label></td> 
                                <td><?=$gun_name?></td> 
                            </tr>
                        <?php $j = $j + 1; } ?>  
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div><!-- row --->
    <br />



    <div id="status">					
    </div>


    <?php 
        /* echo '<pre>'; 
        echo 'r1= '; 
        print_r($r1); 
        echo 'r2= '; 
        print_r($r2);  */
    ?> 


<!-- ---> 
    
 

    <div class="row-fluid">    
      <div class="col-md-7">.col-md-7ffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff</div>
      <div class="col-md-5">.col-md-5ff</div>

    </div>









 
</div>







<div class="container-fluid">
    <div class="row-fluid">    
      <div class="col-md-1">.col-md-1</div>
      <div class="col-md-1">.col-md-1</div>
      <div class="col-md-1">.col-md-1</div>
      <div class="col-md-1">.col-md-1</div>
      <div class="col-md-1">.col-md-1</div>
      <div class="col-md-1">.col-md-1</div>
      <div class="col-md-1">.col-md-1</div>
      <div class="col-md-1">.col-md-1</div>
      <div class="col-md-1">.col-md-1</div>
      <div class="col-md-1">.col-md-1</div>
      <div class="col-md-1">.col-md-1</div>
      <div class="col-md-1">.col-md-1</div>
    </div>
    <div class="row">
      <div class="col-md-8">.col-md-8</div>
      <div class="col-md-4">.col-md-4</div>
    </div>
    <div class="row">
      <div class="col-md-4">.col-md-4</div>
      <div class="col-md-4">.col-md-4</div>
      <div class="col-md-4">.col-md-4</div>
    </div>
    <div class="row">
      <div class="col-md-6">.col-md-6</div>
      <div class="col-md-6">.col-md-6</div>
    </div>
</div>







