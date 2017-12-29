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
    <?/* = $form->field($model, 'department', [
            'template' => '{label} <div class="row"><div class="col-sm-2">{input}{error}{hint}</div></div>'
        ])  */ 
    ?>
    
    <?php
        $listDepartment=[
           'Без отдела'=>'Без отдела',
           'Сопровождение'=>'Сопровождение',
        ];
        $param = ['options' =>[ $currentDepartment => ['Selected' => true]]];
        //echo Html::dropDownList('department', 'null', $listDepartment, $param); 
        echo $form->field($model, 'department', [
            'template' => '{label} <div class="row"><div class="col-sm-2">{input}{error}{hint}</div></div>'
        ])->dropDownList($listDepartment, $param);
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
                    <td><b>Отдел</b></td> 
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
                    $currentDepartment  = $CurrentUser['department'];   //Фамилия охранника       
                ?>
                <tr id='userName-<?=$user_id?>'>
                    <td><?=$i?></td> 
                    <td><?=$user_login?></td> 
                    <td><?=$full_name?></td> 
                    <td style='width: 300px;'><?
                        $listDepartment=[
                               'Без отдела'=>'Без отдела',
                               'Сопровождение'=>'Сопровождение',
                        ];
                        $param = [
                            'options' =>[ $currentDepartment => ['Selected' => true]],
                            'id' => 'department-'.$user_id,
                            //'class' => 'currentDepartment',
                            //'template' => '{select}',
                            'onchange' => 'changeUser(GetData(this.id), this.id)',
                        ];
                        echo $form->field($model, 'department', ['template' => '{input}'])->dropDownList($listDepartment, $param);


                    ?></td> 
                    <td><button type='button' class='btn btn-sm btn-danger' onclick='delete_line(<?=$user_id?>, <?=$table_users?>);'>Удалить</button></td>
                    
                    
                    <?php //if ($currentDepartment == 'Сопровождение'): ?>
                        <td><button type='button' class='btn btn-sm btn-danger' onclick='gunShow(<?=$user_id?>, "<?=$full_name?>", <?=$i?>);'>Оружие</button></td>
                    <? //else: ?>
                        <!-- <td></td> -->
                    <? //endif; ?>

                    
                    
                            
                        
                </tr>
            <?php $i = $i + 1; } ?>  
            </tbody>
          </table>
        </div>
        <div class="col-md-4" id="gun">	
            <div class="panel panel-default" id="panelGun">
                <div class="panel-body">
                    <table class="table table-striped" id="tableGun">
                        <caption><strong>Список оружия <input type="text" id="userName"></input></strong></caption>
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
                                 
                                <td><label class="checkbox" for="gunCheckbox-<?=$gun_id?>"><input id="gunCheckbox-<?=$gun_id?>" class="gunCheckbox" type="checkbox" value="" onclick='checkboxChange(<?=$gun_id?>);'><?=$j?></label></td> 
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
         echo '<pre>'; 
       // echo 'r1= '; 
        print_r($error); 
        //echo 'r2= '; 
        print_r($r2);  
    ?> 


<!--  

    <div class="row-fluid">    
      <div class="col-md-7">.col-md-7ffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff</div>
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
      <div class="col-md-6">.col-md-6
      </div>
    </div>
</div>


--->




