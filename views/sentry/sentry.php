<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


$this->title = 'Постовая ведомость';
//$this->params['breadcrumbs'][] = $this->title;

?>

<h1><?//= Html::encode($this->title) ?></h1>

<div class="container-fluid"> 
    <div class="row">  
        <?php $form = ActiveForm::begin([
                        'id' => 'sentryForm',  
                    ]); ?>
        <div class="col-xs-1">  
            <?php
                $years=[
                   2017=>'2017',
                   2018=>'2018',
                   2019=>'2019',
                ];
                $param = [
                    'options'   => [ $year => ['Selected' => true]],
                    'onchange'  => 'changeDate()',
                ];
                echo Html::dropDownList('year', 'null', $years, $param); 
            ?>
        </div>
        <div class="col-xs-1">
            <?php
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
                $param = [
                    'options'   =>[ $month => ['Selected' => true] ],
                    'onchange'  => 'changeDate()',
                ];
                echo Html::dropDownList('month', 'null', $months, $param);
            ?>
        </div> 
        <div class="col-xs-1">
            <?php
                $days=[
                    1=>'01',
                    2=>'02',
                    3=>'03',
                    4=>'04',
                    5=>'05',
                    6=>'06',
                    7=>'07',
                    8=>'08',
                    9=>'09',
                    10=>'10',
                    11=>'11',
                    12=>'12',
                    13=>'13',
                    14=>'14',
                    15=>'15',
                    16=>'16',
                    17=>'17',
                    18=>'18',
                    19=>'19',
                    20=>'20',
                    21=>'21',
                    22=>'22',
                    23=>'23',
                    24=>'24',
                    25=>'25',
                    26=>'26',
                    27=>'27',
                    28=>'28',
                    29=>'29',
                    30=>'30',
                    31=>'31',
                ];
                $param = [
                    'options'   => [ $day => ['Selected' => true] ],
                    'onchange'  => 'changeDate()',
                    'class'     => 'dateSelect',
                    'id'        => 'dateSelectDay',
                ];
                echo Html::dropDownList('day', 'null', $days, $param);
            ?>
        </div> 
        <div class="col-xs-2">  
            <?= Html::submitButton('Обновить таблицу', ['class' => 'btn btn-primary', 'id' => 'refreshButton', 'name' => 'refreshButton', 'value' => 'refreshButton']) ?>
        </div> 
        
        <div class="col-xs-2">  
            <?= Html::submitButton('Добавить строку', ['class' => 'btn btn-success', 'name' => 'add-button', 'value' => 'add-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
        
        <div class="col-xs-5 sentryHeader" id="sentryHeaderId">

        </div>
    </div>

        
    <div class="row">    
    <!-- Блок таблицы маршрутов-->
        <div id="div_flights_table"> 
            <?php   
                #Рисуем таблицу маршрутов
                if ($listSentry != NULL) { //иначе варнинги идут, если пусто 
                    echo "<table class='table table-striped table-bordered table-hover'>";
                    echo "<h1>Постовая ведомость за $day $months[$month] $year</h1>"; //Название таблицы
                    
                    #Рисуем шапку таблицы
                    echo "<thead>";
                        echo "<tr class='bg-primary'>";
                        ?>
                        <th scope='col'>№</th> 
                        <th scope='col'>№ поста/<br />маршрута</th> 
                        <th scope='col'>Ф.И.О. охранника</th> 
                        <th scope='col'>Дата заступления<br />на службу</th>
                        <th scope='col'>Время заступления<br />на службу</th>
                        <th scope='col'>Наличие оружия и спецсредств на посту</th>
                        <th scope='col'>Дата окончания<br />службы</th>
                        <th scope='col'>Время окончания службы</th>
                        <th scope='col'>Воемя доклада об обстановке на посту</th>
                        <th scope='col'>Примечания</th> 
                        <?php
                        echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                        #Рисуем строки таблицы
                        $js_change_cell = "changeSentry(this.value, this.id)"; //Ф-ия записи данных в ячейке при их изменении
                        $js_change_list = "changeSentry(GetData(this.id), this.id)"; //Ф-ия записи данных в селекте при их изменении
                        $i = 1;
                        
                        foreach ($listSentry as $key_id => $row_content) { //$key_id - номер строки в таблице, $row_content - массив ячеек в ряду
                            
                            $id_line    = $row_content['id'];           //$id_line - id строки в БД Sentry
                            $fullName   = $row_content['full_name'];    //$fullName - full_name юзера из строки в БД Sentry
                            $gunName    = $row_content['gun'];          //$gun - название оружия
                            
                            echo "<tr id='sentry-$id_line'>";
                            echo "<th scope='row'>$i</th>"; //Вывод № строки
                            $i = $i + 1;
                            
                            foreach ($row_content as $column_name => $data) {  //$column_name - название столбца, $data - содержимое ячейки
                                //$listUsers -массив полный список охранников
                                //$userGun   -массив полный список оружия
                                //$usersGuns -массив связей
                                
                                #Определяем переменные для каждой ячейки строки
                                $container      = "container_default";
                                $type           = "text";
                                $buttonTimeOn   = "";
                                $buttonTimeOff  = "";

                                switch ($column_name) {
                                    case 'id':
                                        $cellHtml = "";  
                                    break;

                                    
                                    case 'date':
                                    case 'date_off':
                                         #Проверяем, на сколько дней дата получения оружия отстоит от выбранного числа
                                         $currentDate = strtotime("$year" . "-" . "$month" . "-" . "$day");   //Выбранная дата
                                         $selectedDate = strtotime($data);                      //Дата в ячейке
                                         $diffDay = ($currentDate - $selectedDate)/86400;       //Разница в днях
                                         #И перекрашиваем дату в браузере в нужный цвет
                                         if ( $diffDay <= 0 ) {
                                             $colourClass = '';
                                         }
                                         if ( $diffDay > 0 ) {
                                             $colourClass = 'blueText';
                                             //$colourTdClass= 'table-secondary';
                                         }
                                         if ( $diffDay > 3 ) {
                                             $colourClass = 'purpleText';
                                             //$colourTdClass= 'table-warning';
                                         }
                                         if ( $diffDay > 7 ) {
                                             $colourClass = 'redText';
                                             //$colourTdClass= 'table-danger';
                                         }
                                         
                                         $type         = "date";
                                         $cellHtml = "<td><div class='$container'><input type='$type' id='$column_name-$id_line' name='$column_name-$id_line' class='$column_name $colourClass' value='$data' onchange='$js_change_cell'></input></div></td>";
                                    break;
                                    
                                    
                                    case 'time_on':
                                    case 'time_off':
                                    case 'time_report':
                                        $data = substr($data, 0, 5); // убираем секунды
                                        //$container      = "container_id";
                                        $container      = "inputTime";
                                        $buttonTimeOff = "<button type='button' id='$column_name-$id_line' class='button_set_time' onclick='setTime(this.id)'></button>";
                                        $cellHtml = "<td><div class='$container'><input type='$type' id='$column_name-$id_line' name='$column_name-$id_line' class='$column_name' value='$data' onchange='$js_change_cell'></input></div><div class='divButtonSetTime'>$buttonTimeOff</div></td>";
                                    break;
                                    
                                    
                                    case 'full_name':
                                        $full_name = "<select size='0' id='full_name-$id_line' name='full_name-$id_line' class='list_users' onchange='$js_change_list'>";
                                        foreach ($listUsers as $value) {
                                            $user_n = str_replace(" ", "_", $value); //Заменяем пробелы на _, иначе браузер не понимает
                                            $full_name .= "<option value=$user_n";
                                            if (($value == $data) or ($data == NULL)) {
                                                $full_name .= " selected='selected'";
                                            }
                                            $full_name .= '>' . $value;
                                        }
                                        $full_name .= "</select>"; 
                                        $cellHtml = "<td><div class='$container'>$full_name</div></td>";
                                    break;
                                    
                                    
                                    case 'gun': 
                                        $gun = "<select size='0' id='gun-$id_line' name='gun-$id_line' class='list_guns' onchange='$js_change_list'>";
                                        $list = array();
                                        $j = 0;
                                        foreach ($usersGuns as $key => $val) {
                                            $userOwnerName=$val['user'][0]['full_name'];
                                            if ($userOwnerName == $fullName) {
                                                $list[$j] = $val['gun'][0]['name'];
                                                $gun_n = str_replace(" ", "_", $list[$j]); //Заменяем пробелы на _, иначе браузер не понимает
                                                $gun .= "<option value=$gun_n";
                                                if ($list[$j] == $gunName) {
                                                    $gun .= " selected='selected'";
                                                }
                                                $gun .= '>' . $list[$j];
                                                $j = $j + 1;
                                            }
                                        } 
                                        $gun .= "<option value=no_gun";
                                        if ( (empty($gunName)) or ($gunName == 'Оружие не выбрано') ) {
                                            $gun .= " selected='selected'";
                                        }
                                        $gun .= '>' . 'Оружие не выбрано';
                                        $gun .= "</select>"; 
                                        $cellHtml = "<td><div class='$container'>$gun</div></td>"; 
                                    break;
                                    
                                    
                                    default:
                                        $cellHtml = "<td><div class='$container'><input type='$type' id='$column_name-$id_line' name='$column_name-$id_line' class='$column_name' value='$data' onchange='$js_change_cell'></input></div></td>";
                                        break;
                                } //end switch
                                echo $cellHtml; //нарисовать ячейку
                                
                            }
                            echo "</tr>";
                        }
                        echo "</tbody>";
                    echo "</table>";
                } else { //Если в таблице нет ни одного рейса за этот месяц и нет рейсов без даты, то:
                    //$stmt = $pdo->query('INSERT INTO flights () VALUES()'); //Добавляем пустую строку
                    //showTable($year, $month_name); //Заново запускаем функцию и выводим эту строку на экран
                    echo "Таблица пуста";
                }
               
            ?>     
        </div> <!-- div_flights_table -->
    </div> <!-- row -->
    
    <div class="row">
        <button class="btn btn-success" onclick="printImage()">Распечатать</button>
    </div> <!-- row -->
    
</div> <!-- container-fluid -->

<!--
        <div class="col-xs-3">  
            <?//= Html::submitButton('Печать', ['class' => 'btn btn-success', 'name' => 'print-button', 'value' => 'print-button']) ?>
        </div> 
 -->
 
<div id="status">	
				
</div>






<?php

echo '<pre>'; 
//print_r ($gun->name); //фактически - последний клиент из списка
//print_r ($usersGuns[1]['gun'][0]['name']);

//echo " xxx1 ";
//print_r ($countListSentry);
echo "$dateFlight";
//print_r ($res_array);
//print_r ($currentDate);
//echo count($usersGuns->gun);    //поcчитать, сколько продуктов имеется в $cats. "products" - обязательно должно совпадать с именем функции getProducts()

echo '</pre>'; 

?> 
       
<?php //echo count($usersGuns[0]->gun); ?> 

    

