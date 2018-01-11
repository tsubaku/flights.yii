<?php
    /* @var $this yii\web\View */
    use yii\helpers\Html;
    //use yii\widgets\ActiveForm;
    use yii\bootstrap\ActiveForm;
    use dosamigos\datepicker\DatePicker;

    $this->title = 'Рейсы';

    use yii\bootstrap\Modal;
?>

<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
</div> 

<?php $this->registerJsFile('@web/js/jquery/jquery.js',  //подключение скриптов с указанием зависимости (брать прямо из AppAssets.php)
    ['depends' => 'yii\web\YiiAsset']) ?> 
<?php $this->registerJsFile('@web/js/jquery/jquery-ui.js',  
    ['depends' => 'yii\web\YiiAsset']) ?> 
<?php $this->registerJsFile('@web/js/jquery/jquery-ui-datepicker-ru.js',  
    ['depends' => 'yii\web\YiiAsset']) ?>
<?php $this->registerJsFile('@web/js/jquery/jquery.main.js',     
    ['depends' => 'yii\web\YiiAsset']) ?>
 <?php $this->registerJsFile('@web/js/guard.js',     
    ['depends' => 'yii\web\YiiAsset']) ?>
    
 <?php $this->registerCssFile('@web/css/jquery-ui.css',     
    ['depends' => 'yii\web\YiiAsset']) ?> 
  
<?php 
    echo '<script language="javascript">var array_date_of_departure = ' . $js_array . ';</script>'; //масив дат выездов
    echo '<script language="javascript">var user_id_current = ' . $idUser . ';</script>';           //id охранника
    
    Modal::begin([
        'header' => '<h2>Hello world</h2>',
        'toggleButton' => [
            'label' => 'click me',
            'tag' => 'button',
            'class' => 'btn btn-success',
        ],
        'footer' => 'Низ окна',
    ]);
    echo 'Say hello...';
    Modal::end();
?>

<div id="calendar">
</div>

<p>Охранник: <?php print($full_name); //echo "$js_array";?></p>


<!--  Блок модального окна -->			
<div id="modal_form"><!-- Сaмo oкнo --> 
</div>  <!--  Блок модального окна -->	
<div id="overlay"><!-- Пoдлoжкa -->
</div>
<!--  -->

<!-- HTML-код модального окна -->
<div id="myModalBox" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
        <!-- Заголовок модального окна -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
         <!--       <h4 class="modal-title">Заголовок модального окна</h4> -->
                <div class="col-xs-12 panel-heading">
                    <h3 class="panel-title col-xs-3">№ рейса:</h3>
                    <h3 class="panel-title col-xs-3" id="div_right_string0"></h3>
                </div>
            </div>
            <!-- Основное содержимое модального окна -->
            <div class="modal-body">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-3">Дата выезда</div>
                        <div class="col-xs-3" id="div_right_string1"></div>
                    </div><br />
                    <div class="row">	
                        <div class="col-xs-3">Время:</div>
                        <div class="col-xs-3" id="div_right_string2"></div>
                    </div><br />
                    <div class="row">	
                        <div class="col-xs-3">Клиент:</div>
                        <div class="col-xs-3" id="div_right_string3"></div>		
                    </div><br />
                    <div class="row">	
                        <div id="div_right_string5"></div> 
                        <div id="div_right_string6"></div>
                    </div><br />
                
                    <div class="row">
                        <div class="col-xs-3">№ машины:</div>
                    </div>
                    <div class="row">
                        <div class="col-xs-3" id="div_right_string4"></div> 
                    </div>
                    
                    <div class="row">
                        <div class="col-xs-4">Принятие:</div>
                    </div>
                    
                    <div class="row">
                        <div class="col-xs-4" id="div_right_string7"></div> 
                    </div>
                    <div class="row">
                        <div class="col-xs-4">Сдача:</div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4" id="div_right_string8"></div>
                    </div>

                    <!-- Загрузка фотографий на сервер-->
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
            </div>
            <!-- Футер модального окна -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
         <!--       <button type="button" class="btn btn-primary">Сохранить изменения</button> -->
            </div>
        </div>
    </div>
</div>
<!--   -->	

<div id="status">					
</div>



<?php

    //echo '<pre>'; 
    //echo 'listUsers= '; 
    //print_r($flightDate); 

?>        

 
    
    
</div>
