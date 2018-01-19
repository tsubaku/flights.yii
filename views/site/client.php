<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;


$this->title = 'Клиенты';
//$this->params['breadcrumbs'][] = $this->title;

$table_users = '11'; //клиенты             !!! Костыль !!!
?>
<div class="container-fluid">
    <h1><?= Html::encode($this->title) ?></h1>
 
    <div class="row">
        <!-- Регистрация клиентов --->
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
    </div>



        
        
    <div class="row">    
        <table id="tableListClient" class="table table-striped table-bordered table-hover table-success">
         <caption><strong>Список наших клиентов</strong>
         </caption>
            <thead>
                <tr class='bg-primary'>  
                    <th scope='col'>№</th> 
                    <th scope='col'>Название</th> 
                    <th scope='col'>Удалить</th>
                </tr>
            </thead>
            <tbody id="clientsTable">
                <?php
                #Выводим последовательно строки с именами клиентов, начиная с порядкового номера
                $i = 1;   
                foreach ($listClients as $client){ 
                    $id         = $client['id'];      //id клиента
                    $clientName = $client['name'];  //Название клиента  
                    ?>
                    <tr id='clientName-<?=$id?>'>
                        <th scope='row'><?=$i?></td> 
                        <td><?=$client->name?></td> 
                        <td class="buttonDelGun">
                            <button type='button' class='btn btn-sm btn-danger' onclick='delete_line(<?=$id?>, <?=$table_users?>);'>Удалить</button>
                        </td>  
                    </tr>
                <?php $i = $i + 1; } ?>  
            </tbody>
        </table>
    </div>

    
    <div id="status">					
    </div>


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
    //echo '<pre>'; 
    //print_r ($client->name); //фактически - последний клиент из списка
    //print_r ($client);
    //print_r ($rows);
    //echo '</pre>'; 

    ?>        

 
    
    
</div>
