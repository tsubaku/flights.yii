<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Клиенты';
//$this->params['breadcrumbs'][] = $this->title;

$table_users = '11'; //клиенты             !!! Костыль !!!
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
 
        
<div class="table table-striped">
     <table style="width: 600px;" class="table table-striped">
     <caption><strong>Список наших клиентов</strong></caption>
        <thead>
            <tr>  
                <td><b>№</b></td> 
                <td><b>Название</b></td> 
                <td><b>del</b></td>
            </tr>
        </thead>
        <tbody>
        <?php
        #Выводим последовательно строки с именами клиентов, начиная с порядкового номера
        $i      = 1;
              
        foreach ($listClients as $client){ 
            $id         = $client['id'];      //id клиента
            $clientName = $client['client'];  //Название клиента  
        ?>
            <tr>
                <td style="width: 50px;"><?=$i?></td> 
                <td><?=$client->client?></td> 
                <td style="width: 30px;"><div><button type='button' class='a_button_delete' onclick='delete_line(<?=$id?>, <?=$table_users?>);'></button></div></td>  
            </tr>
        <?php $i = $i + 1; } ?>  
          


        </tbody>
      </table>
</div>



<div class="button_container">					
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
				<br />
			</div>
			
			<div class="button_container">
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
			</div>	

<div id="status">					
		</div>

<button class="btn btn-success" id="btn">Clicc</button>
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

 
    
    
</div>
