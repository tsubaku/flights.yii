<?php
    /* @var $this yii\web\View */
    use yii\helpers\Html;
    //use yii\widgets\ActiveForm;
    use yii\bootstrap\ActiveForm;
    use dosamigos\datepicker\DatePicker;

    $this->title = 'Интерфейс охранника';
    //$this->params['breadcrumbs'][] = $this->title;
    
    //$full_name = 'q';
?>


<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
</div> 


<?php $this->registerJsFile('@web/js/jquery/jquery.js',  //подключение скрипта с указанием зависимости (брать прямо из AppAssets.php)
    ['depends' => 'yii\web\YiiAsset']) ?> 
<?php $this->registerJsFile('@web/js/jquery/jquery-ui.js',  
    ['depends' => 'yii\web\YiiAsset']) ?> 
<?php $this->registerJsFile('@web/js/jquery/jquery-ui-datepicker-ru.js',  
    ['depends' => 'yii\web\YiiAsset']) ?>
<?php $this->registerJsFile('@web/js/jquery/jquery.main.js',     
    ['depends' => 'yii\web\YiiAsset']) ?>
 <?php $this->registerJsFile('@web/js/guard.js',     
    ['depends' => 'yii\web\YiiAsset']) ?>
  
  
<?php    
//    echo DatePicker::widget([
//   'name'  => 'from_date',
//  'value'  => $value,
   //'language' => 'ru',
   //'dateFormat' => 'yyyy-MM-dd',
// ]); 

?> 
<div id="calendar">
</div>

<p>Охранник: <?php print($full_name);?></p>

<!--  Блок модального окна -->			
<div id="modal_form"><!-- Сaмo oкнo --> 
    <span id="modal_close">X</span> <!-- Кнoпкa зaкрыть --> 
    <!-- Инфо о рейсе -->
    <div class="div_table_info_flight">
        <div class="div_line_info">
            <div class="left_div">№ рейса</div>
            <div class="right_div" id="div_right_string0"></div>
        </div><br />
        <div class="div_line_info">
            <div class="left_div">Дата выезда</div>
            <div class="right_div" id="div_right_string1"></div>
        </div><br />
        <div class="div_line_info">	
            <div class="left_div">Время</div>
            <div class="right_div" id="div_right_string2"></div>
        </div><br />
        <div class="div_line_info">	
            <div class="left_div">Клиент</div>
            <div class="right_div" id="div_right_string3"></div>		
        </div><br />
        <div class="div_line_info">	
            <div id="div_right_string5"></div> 
            <div id="div_right_string6"></div>
        </div><br />
    </div>
    <div class="div_table_info_flight">
        <div class="left_div">№ машины</div>
        <div class="right_div" id="div_right_string4"></div> 
    </div>
    
    <div class="div_line_info">Принятие</div>
    <div class="div_line_info" id="div_right_string7"></div> 
    <div class="div_line_info">Сдача</div>
    <div class="div_line_info" id="div_right_string8"></div>
    
    <!-- Загрузка фотографий на сервер-->
    <div class="wrapper">
        <form class="fileform" name="upload">
            <div class="selectbutton">Сделать фото</div>
            <input id="upload" type="file" name="myfile">
        </form>
        <div id="status">					
        </div>

        <div class="ajax-respond" id="ajax_respond"><!-- Ответ сервера на загрузку фото -->
            <img src='./img/success.png' alt='OK' />
        </div>
    </div>

</div>  <!--  Блок модального окна -->	
<div id="overlay"><!-- Пoдлoжкa -->
</div>

<div id="status">					
</div>




<!--  Загрузка файлов -->	

<!--   -->	




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
    //echo 'listUsers= '; 
   // print_r($flightDate); 

?>        

 
    
    
</div>
