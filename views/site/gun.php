<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;


$this->title = 'Оружие';
//$this->params['breadcrumbs'][] = $this->title;

$table_users = '31'; //клиенты             !!! Костыль !!!
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
<div class="container-fluid"> 
    <div class="row">
        <!-- form Регистрация клиентов --->
        <?php $form = ActiveForm::begin([
                'id' => 'add',
                'layout' => 'inline',
                'fieldConfig' => [
                    'labelOptions' => ['class' => ''],
                    'enableError' => true,
                ]
            ]) ?>


        <?= $form->field($model, 'name', [
                'template' => '{label} <div class="row"><div class="col-sm-2">{input}{error}{hint}</div></div>'
            ]) 
            
        ?>
        
            <div class="form-group">
                <div>
                    <?= Html::submitButton('Регистрация', ['class' => 'btn btn-success', 'id' => 'add1', 'name' => 'add2']) ?>
                </div>
            </div>
        <?php ActiveForm::end() ?> 
        <!--    </form>     --> 
    </div><!-- row -->

        
    <div class="row">    
        <div class="table table-striped">
             <table style="width: 600px;" class="table table-striped">
             <caption><strong>Список наших клиентов</strong></caption>
                <thead>
                    <tr>  
                        <td><b>№</b></td> 
                        <td><b>Название</b></td> 
                        <td><b>Удалить</b></td>
                    </tr>
                </thead>
                <tbody id="gunTable">
                    <?php
                    #Выводим последовательно строки с именами клиентов, начиная с порядкового номера
                    $i = 1;   
                    foreach ($listGun as $gun){ 
                        $id     = $gun['id'];      //id клиента
                        $unName = $gun['name'];  //Название клиента  
                        ?>
                        <tr id='gunName-<?=$id?>'>
                            <td style="width: 50px;"><?=$i?></td> 
                            <td><?=$gun->name?></td> 
                            <td style="width: 70px;"><button type='button' class='btn btn-sm btn-danger' onclick='delete_line(<?=$id?>, <?=$table_users?>);'>Удалить</button></td>  
                        </tr>
                    <?php $i = $i + 1; } ?>  
                </tbody>
              </table>
        </div>
    </div> <!-- row -->

    <div class="row">
        
    </div> <!-- row -->
</div> <!-- container-fluid -->
    
    
    
<br />


	

<div id="status">					
</div>


<?php


//echo '<pre>'; 
//print_r ($gun->name); //фактически - последний клиент из списка
//print_r ($gun);
//print_r ($rows);
//echo '</pre>'; 

?>        

 
    
    
</div>
