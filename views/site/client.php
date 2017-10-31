<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;


$this->title = 'Клиенты';
//$this->params['breadcrumbs'][] = $this->title;

$table_users = '11'; //клиенты             !!! Костыль !!!
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
 
<div>
   <!-- <form class="form-horizontal"> -->
        <div class="form-group">
            <label for="client" class="col-sm-2 control-label">Новый клиент:</label>
            <div class="col-sm-3">
          
                <input type="text" class="form-control" id="client" name="client"> 
            </div>  
          
            <button class="col-sm-2 btn btn-success" id="a_register_client" onclick="register_client();">Зарегистрировать</button>
        </div>
  <!--    </form>     -->
</div>

  






	
    
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
        <tbody id="clientsTable">
            <?php
            #Выводим последовательно строки с именами клиентов, начиная с порядкового номера
            $i = 1;   
            foreach ($listClients as $client){ 
                $id         = $client['id'];      //id клиента
                $clientName = $client['name'];  //Название клиента  
                ?>
                <tr id='clientName-<?=$id?>'>
                    <td style="width: 50px;"><?=$i?></td> 
                    <td><?=$client->name?></td> 
                    <td style="width: 70px;"><button type='button' class='btn btn-sm btn-danger' onclick='delete_line(<?=$id?>, <?=$table_users?>);'>Удалить</button></td>  
                </tr>
            <?php $i = $i + 1; } ?>  
        </tbody>
      </table>
</div>
</div>
<br />


	

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

print_r ($client->name);

?>        

 
    
    
</div>
