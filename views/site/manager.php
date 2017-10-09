<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;

use app\models\Flights;


$this->title = 'Рейсы';
//$this->params['breadcrumbs'][] = $this->title;

$table = '20'; //Рейсы             !!! Костыль !!!
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
 
		<!-- содержимое первой вкладки   -->
		<div id="layerA">
			
			<div class="button_container">	
				<p>Даты выезда: </p>
                <?php echo "$text"; ?>
			</div>	
            <?php $form = ActiveForm::begin([
                'id' => 'manager-form',  
            ]); ?>
            
            

            
            <?php
               /*  echo $form->field($model, 'id')->dropdownList(['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
                    ['prompt'=>'Select Category'],
                    ['class'=>'col-sm-1']
                ); */
                
                $months=[
                   1=>'Январь',
                   2=>'Февраль',
                   3=>'Март',
                   4=>'Апрель',
                   5=>'Май',
                   6=>'Июнь',
                   7=>'Июль',
                   8=>'Август',
                   9=>'Сентябрь',
                   10=>'Октябрь',
                   11=>'Ноябрь',
                   12=>'Декабрь',
                ];
                $param = ['options' =>[ '6' => ['Selected' => true]]];
                echo Html::dropDownList('month', 'null', $months, $param);
                
                $years=[
                   2016=>'2016',
                   2017=>'2017',
                   2018=>'2018',

                ];
                $param = ['options' =>[ '6' => ['Selected' => true]]];
                echo Html::dropDownList('year', 'null', $years, $param);
                
            ?>
            
          <!--  
			<div class="button_container">					
				<select size="0" id="month" name="month">
					<?php
						/* $month=array('Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь');
						$current_month=date("n");
						for ($i=1;$i<13;$i++){
							$ii = $i-1;
							echo "<option value=$month[$ii]";
							if ($current_month==$i)echo " selected='selected'";
							echo ">".$month[$i-1];
						} */
					?>
				</select>
				<br />
			</div>
			
			<div class="button_container">
				<select size="0" id="year" name="year">
					<?php
						/* $year=array('2016','2017','2018','2019','2020','2021','2022','2023','2024', '2025', '2026', '2027');
						$current_year=date("Y");
						for ($y=0;$y<12;$y++){
							echo "<option value=$year[$y]";
							if ($year[$y] == $current_year)echo " selected='selected'";
							echo ">".$year[$y];
						} */
					?>
				</select>
			</div>	
			-->
			<div class="button_container">				
           <!--     <button type="button" id="a_show_flights_table" class="a_demo_three b_green">
                    Обновить таблицу рейсов
                </button> -->
                <div class="form-group">
                    <?= Html::submitButton('Refresh', ['class' => 'btn btn-primary', 'name' => 'refresh-button']) ?>
                </div>
			</div>
            
			<?php ActiveForm::end(); ?>
            
			<!-- Блок таблицы рейсов-->
			<div id="div_flights_table">
			<!--	<table><caption><strong>Рейсы</strong></caption><tbody><tr><td><strong>№</strong></td><td class=""><strong>Номер рейса</strong></td><td class=""><strong>Дата выезда</strong></td><td class=""><strong>Время</strong></td><td class=""><strong>Клиент</strong></td><td class=""><strong>Подклиент</strong></td><td class=""><strong>Номер машины</strong></td><td class=""><strong>Принятие под охрану</strong></td><td class=""><strong>Сдача с охраны</strong></td><td class=""><strong>Состав ОХР</strong></td><td class=""><strong>ФИО</strong></td><td class=""><strong>Выдано</strong></td><td class=""><strong>Машина</strong></td><td class=""><strong>Срок доставки</strong></td><td class=""><strong>Принятие</strong></td><td class=""><strong>Сдача</strong></td><td class=""><strong>Фактич. срок доставки</strong></td><td class=""><strong>Простой часы</strong></td><td class=""><strong>Простой, ставка за охранника</strong></td><td class=""><strong>Простой сумма</strong></td><td class=""><strong>Ставка без НДС</strong></td><td class=""><strong>Ставка с НДС</strong></td><td class=""><strong>Счёт</strong></td><td class=""><strong>ЗП</strong></td><td class=""><strong>Простой</strong></td><td class=""><strong>Аренда машины</strong></td><td class=""><strong>Оплата машины</strong></td><td class=""><strong>ИТОГО</strong></td><td class=""><strong>ЗП+Простой</strong></td><td class=""><strong>Статус</strong></td><td class=""><strong></strong></td></tr></tbody></table> -->
			
            
            
            
            
         <?php   
            #Рисуем таблицу рейсов
            if ($listFlights != NULL) { //иначе варнинги идут, если рейсов нет
                
                #Рисуем таблицу   
                echo "<table>";
                echo "<caption><strong>Рейсы за $month $year</strong></caption>"; //Название таблицы
                
                #Рисуем шапку таблицы
                echo "<tr>";
                foreach ($ru_rows_array as $key => $value) {
                    echo "<td><strong>" . $value . "</strong></td>"; 
                } 
                echo "</tr>";
                
                #Рисуем строки таблицы
                $js_change_cell = "change_cell(this.value, this.id)"; //Ф-ия записи данных в ячейке при их изменении
                $js_change_list = "change_cell(GetData(this.id), this.id)"; //Ф-ия записи данных в селекте при их изменении
                $i = 1;
                foreach ($listFlights as $key_id => $row_content) { //$key_id - номер строки в таблице, $row_content - массив ячеек в ряду
                    
                    $id_line   = $row_content['id']; //$id_line - id строки в БД рейсов
                    $id_status = $row_content['fakticheskij_srok_dostavki']; //$id_status - status строки в БД
                    echo "<tr id='flight-$id_line'>";
                    echo "<td><input type='text' id='number_line-$i' class='number_line' value='$i' disabled='disabled'> </input></td>"; //Вывод № строки
                    $i = $i + 1;
                    
                    foreach ($row_content as $column_name => $data) {
                        
                        #Определяем переменные для каждой ячейки строки
                        $button       = "";
                        $photo        = "";
                        $container    = "container_default";
                        $status_class = "";
                        $readonly     = "";
                        $type         = "text";
                        $fio          = "";
                        switch ($column_name) {
                            case 'id':
                                #Вытаскиваем все пути к фотографиям рейса
                                //$stmt = $pdo->prepare('SELECT `path` FROM `photo` WHERE `n_flight` = :id_line');
                                //$stmt->execute(array(
                                //    'id_line' => $id_line
                                //));
                                //$photo_name_array = $stmt->fetchAll(); //Обработать запрос, переведя ВСЕ данные в массив $photo_name_array
                                
                                //$listPhoto
                                //$flightPhoto = Array();
                                $photo_name_array = null;
                                $p = 0;
                                foreach ($listPhoto as $key => $val) {
                                    if ($val['n_flight'] == $id_line) { 
                                        //$flightPhoto[] = $val['path']; 
                                        $photo_name_array[$p] = $val['path'];
                                        $p = $p + 1;
                                    }
                                    
                                }
                                
                                
                                
                                if ($photo_name_array) { //Если имеется хотя бы одно фото
                                    $photo = "<button type='button' class='a_button_photo' onclick='get_photo($id_line)'></button>";
                                } else {
                                    $photo = "<button type='button' class='a_button_no_photo' onclick='get_photo($id_line)'></button>";
                                }
                                $readonly  = "readonly";
                                $container = "container_id";
                                $button    = "<button type='button' class='a_button_delete' onclick='delete_line($data, $table);'></button>";
                                break;
                            
                            case 'data_vyezda':
                                $type = "date"; //Ставим в ячейку дату;
                                break;
                            
                            case 'vremja':
                            case 'srok_dostavki':
                                $type = "text";
                                $data = substr($data, 0, 5); //убираем лишнее из формата ячейки TIME
                                break;
                            
                            case 'prinjatie':
                            case 'sdacha':
                                $type = "datetime-local"; //Ставим в ячейку тип "дата и время"
                                if (isset($data)) { //хз как, но оно работает, но время показывается и прописывается правильно
                                    $data = date("Y-m-d\TH:i:s", strtotime($data));
                                }
                                $input_class = '';
                                break;
                            
                            case 'klient':
                                 $klient = "<select size='0' id='klient-$id_line' name='klient-$id_line' class='list_users' onchange='$js_change_list'>";
                                foreach ($listClients as $value) {
                                    $user_n = str_replace(" ", "_", $value); //Заменяем пробелы на _, иначе браузер не понимает
                                    $klient .= "<option value=$user_n";
                                    if (($value == $data) or ($data == NULL)) {
                                        $klient .= " selected='selected'";
                                    }
                                    $klient .= '>' . $value;
                                }
                                $klient .= "</select>"; 
                                break;
                            
                            case 'fio':
                                 $fio = "<select size='0' id='fio-$id_line' name='fio-$id_line' class='list_users' onchange='$js_change_list'>";
                                foreach ($listUsers as $value) {
                                    $user_n = str_replace(" ", "_", $value); //Заменяем пробелы на _, иначе браузер не понимает
                                    $fio .= "<option value=$user_n";
                                    if (($value == $data) or ($data == NULL)) {
                                        $fio .= " selected='selected'";
                                    }
                                    $fio .= '>' . $value;
                                }
                                $fio .= "</select>"; 
                                break;
                            
                            case 'fakticheskij_srok_dostavki':
                            case 'prostoj_summa':
                            case 'schet':
                            case 'oplata_mashin':
                            case 'itogo':
                            case 'zp_plus_prostoj':
                                $readonly = "readonly";
                                break;
                            
                            default:
                                break;
                        }
                        if ($id_status == 'В рейсе') {
                            $status_class = 'completed';
                        }
                        
                        //Если столбец ФИО или Клиент, то рисуем тег select со списком
                        if ($column_name == 'fio') {
                            echo "<td ><div class='$container'>$fio</div></td>"; //
                        } else if ($column_name == 'klient') {
                            echo "<td ><div class='$container'>$klient</div></td>"; //
                        } else {    //иначе просто инпут
                            echo "<td ><div class='$container'>$photo<input $readonly type='$type' id='$column_name-$id_line' name='$column_name-$id_line' class='$column_name $status_class' value='$data' onchange='$js_change_cell'></input>$button</div></td>"; //
                        }
                    }
                    echo "</tr>";
                }
                echo "</table>";
            } else { //Если в таблице нет ни одного рейса за этот месяц и нет рейсов без даты, то:
                //$stmt = $pdo->query('INSERT INTO flights () VALUES()'); //Добавляем пустую строку
                //showTable($year, $month_name); //Заново запускаем функцию и выводим эту строку на экран
                echo "таблица пуста";
            }
           
           ?>     
            
            
            
            
            
            
            
            
            
            
            </div>
						
			<div>			
                <button type="button" onclick="add_line();" id="a_add_line" class="a_demo_three b_green">
                   Добавить строку
                </button>
			</div>
		</div><!-- конец layerA   -->


		


		
		
<!--  Блок модального окна показа миниатюр -->			
		<div id="modal_form_for_photo"><!-- Сaмo oкнo --> 
			<span id="modal_close_form_for_photo">X</span> <!-- Кнoпкa зaкрыть --> 
			
			<div id="div_large_photo" class="button_container"><img id="largeImg" src="" alt="Large image">
			</div>
		
			<div id="div_photo" class="button_container">
				 <ul id="thumbs">
				 </ul>
			</div>
		</div>
		
		<div id="overlay_for_photo">
		</div><!-- Пoдлoжкa -->

		<div id="status">					
		</div>
<!--  /Блок модального окна -->	
				
</div><!-- end site-about -->	
<br />

<!-- Блок экспериментов-->

<?php
    //echo '<pre>'; 
    //echo 'listUsers= '; 
    //print_r($listUsers); 
  /*  echo 'model2= '; 
    print_r($model2);    
    echo 'listFlights= '; 
    print_r($listFlights);
    echo 'ru_rows_array=';  
    print_r($ru_rows_array);
    echo '</pre>';  */
?>


<!-- /Блок экспериментов-->				