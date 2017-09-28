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
 
<div>
   <!-- <form class="form-horizontal"> -->
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
  <!--    </form>     -->
</div>

  






	
    
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
            //foreach ($listUsers as $CurrentUser){                 
            //    $user_id     = $CurrentUser['user_id'];        //id охранника
            //    $user_login  = $CurrentUser['user_login'];     //login охранника
            //    $full_name   = $CurrentUser['full_name'];      //Фамилия охранника       
            ?>
            <tr id='userName-<?=$user_id?>'>
                <td style="width: 50px;"><?=$i?></td> 
                <td><?=$user_login?></td> 
                <td><?=$full_name?></td> 
                <td style="width: 70px;"><button type='button' class='btn btn-sm btn-danger' onclick='delete_line(<?=$user_id?>, <?=$table_users?>);'>Удалить</button></td>  
            </tr>
        <?php //$i = $i + 1; } ?>  
        </tbody>
      </table>
</div>
</div>
<br />


				
<select size="0" id="month" name="month">
    <?php
        $month=array('Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь');
        $current_month=date("n");
        for ($i=1;$i<13;$i++){
            $ii = $i-1;
            echo "<option value=$month[$ii]";
            if ($current_month==$i)echo " selected='selected'";
            echo ">".$month[$i-1];
        }
    ?>
</select>


<select size="0" id="year" name="year">
    <?php
        $year=array('2016','2017','2018','2019','2020','2021','2022','2023','2024', '2025', '2026', '2027');
        $current_year=date("Y");
        for ($y=0;$y<12;$y++){
            echo "<option value=$year[$y]";
            if ($year[$y] == $current_year)echo " selected='selected'";
            echo ">".$year[$y];
        }
    ?>
</select>
<br />	

<div id="status">					
</div>

<button class="btn btn-success" id="btn">Click</button>
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


<!-- Регистрация юзеров --->
<?php

//use yii\helpers\Html;
//use yii\bootstrap\ActiveForm;
?>

<?php $form = ActiveForm::begin() ?>
<?= $form->field($model, 'username') 
    //username -атрибуты модели UsersFofm
    //метод field принимает 2 параметра: модель и арибут данной модели (имя поля, которое мы создаём)
?>
<?= $form->field($model, 'password')->passwordInput() ?>
    <div class="form-group">
        <div>
            <?= Html::submitButton('Регистрация', ['class' => 'btn btn-success']) ?>
        </div>
    </div>
<?php ActiveForm::end() ?>




<!-- ---> 
    
    
</div>