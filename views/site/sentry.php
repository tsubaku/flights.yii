<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


$this->title = 'Постовая ведомость';
//$this->params['breadcrumbs'][] = $this->title;

?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="container-fluid"> 
    <div class="row">  
        <?php $form = ActiveForm::begin([
                        'id' => 'sentryForm',  
                    ]); ?>
        <div class="col-xs-2">  
            <?php
                $years=[
                   2017=>'2017',
                   2018=>'2018',
                   2019=>'2019',
                ];
                $param = ['options' =>[ $year => ['Selected' => true]]];
                echo Html::dropDownList('year', 'null', $years, $param); 
            ?>
        </div>
        <div class="col-xs-2">
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
                $param = ['options' =>[ $month => ['Selected' => true]]];
                echo Html::dropDownList('month', 'null', $months, $param);
            ?>
        </div> 
        <div class="col-xs-2">
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
                $param = ['options' =>[ $day => ['Selected' => true]]];
                echo Html::dropDownList('day', 'null', $days, $param);
            ?>
        </div> 
        <div class="col-xs-3">  
            <?= Html::submitButton('Обновить таблицу', ['class' => 'btn btn-primary', 'name' => 'refresh-button', 'value' => 'refresh-button']) ?>
        </div> 
        <div class="col-xs-3">  
            <?= Html::submitButton('Добавить строку', ['class' => 'btn btn-success', 'name' => 'add-button', 'value' => 'add-button']) ?>
        </div> 
        <?php ActiveForm::end(); ?>
        
        
    </div>

        
        
    <!-- Блок таблицы рейсов-->
    <div id="div_flights_table"> 
        <?php   
            #Рисуем таблицу рейсов
            if ($listSentry != NULL) { //иначе варнинги идут, если рейсов нет 
                echo "<table>";
                echo "<h1>Рейсы за $day $months[$month] $year</h1>"; //Название таблицы
                
                #Рисуем шапку таблицы
                echo "<tr>";
                ?>
                <td><b>№</b></td> 
                <td><b>№ поста/маршрута</b></td> 
                <td><b>Ф.И.О. охранника</b></td> 
                <td><b>Время заступления на службу</b></td>
                <td><b>Наличие оружия и спецсредств на посту</b></td>
                <td><b>Время окончания службы</b></td>
                <td><b>Воемя доклада об обстановке на посту</b></td>
                <td><b>Примечания</b></td> 
                <?php
                echo "</tr>";
                
                #Рисуем строки таблицы
                $js_change_cell = "changeSentry(this.value, this.id)"; //Ф-ия записи данных в ячейке при их изменении
                $js_change_list = "changeSentry(GetData(this.id), this.id)"; //Ф-ия записи данных в селекте при их изменении
                $i = 1;
                foreach ($listSentry as $key_id => $row_content) { //$key_id - номер строки в таблице, $row_content - массив ячеек в ряду
                    
                    $id_line   = $row_content['id']; //$id_line - id строки в БД Sentry
                    $fullName   = $row_content['full_name']; //$fullName - full_name юзера из строки в БД Sentry
                    
                    echo "<tr id='sentry-$id_line'>";
                    echo "<td><input type='text' id='number_line-$i' class='number_line' value='$i' disabled='disabled'> </input></td>"; //Вывод № строки
                    $i = $i + 1;
                    
                    foreach ($row_content as $column_name => $data) {  //$column_name - название столбца, $data - содержимое ячейки
                        #Определяем переменные для каждой ячейки строки
                        $container    = "container_default";
                        $type         = "text";

                        switch ($column_name) {
                            case 'id':
                            case 'date':
                                echo ""; //пропускаем столбцы id и date
                            break;

                            /* case 'number':
                                echo "<td><select size='0' id='f' name='f' class='numbers' onchange='$js_change_list'> 
                                    <option value='1'>1</option>
                                    <option value='2'>2</option>
                                    <option value='3'>3</option>
                                    <option value='4'>4</option>
                                    <option value='5'>5</option>
                                    <option value='6'>6</option>
                                    <option value='7'>7</option>
                                    <option value='8'>8</option>
                                    <option value='9'>9</option>
                                    <option value='10'>10</option>
                                    <option value='11'>11</option>
                                    
                                    </select></td>"; 
                                //echo '1';
                            break; */
                            
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
                                echo "<td ><div class='$container'>$full_name</div></td>";
                            break;
                            
                            case 'gun':
                                
                                //$data      -имя охранника
                                //$listUsers -массив полный список охранников
                                //$userGun   -массив полный список оружия
                                //$usersGuns -массив связей
                                
                                $gun = "<select size='0' id='gun-$id_line' name='gun-$id_line' class='list_guns' onchange='$js_change_list'>";
                                $list = array();
                                $j = 0;
                                foreach ($usersGuns as $key => $val) {
                                    $userOwnerName=$val['user'][0]['full_name'];
                                    if ($userOwnerName == $fullName) {
                                        $list[$j] = $val['gun'][0]['name'];
                                        $gun_n = str_replace(" ", "_", $list[$j]); //Заменяем пробелы на _, иначе браузер не понимает
                                        $gun .= "<option value=$gun_n";
                                        if (($list[$j] == $fullName) or ($fullName == NULL)) {
                                            $gun .= " selected='selected'";
                                        }
                                        $gun .= '>' . $list[$j];
                                        $j = $j + 1;
                                    }
                                } 
                                //if ($j == 0){
                                    $gun .= "<option value=no_gun>Оружие не выбрано";; 
                                //}
                                $gun .= "</select>"; 
                                echo "<td ><div class='$container'>$gun</div></td>"; 
                                
                                
                                //print_r ($listSentry[$i]['gun']);
                                //echo "</br>";
                                
                                
                                /* $gun = "<select size='0' id='gun-$id_line' name='gun-$id_line' class='list_guns' onchange='$js_change_list'>";
                                foreach ($listGuns as $value) {
                                    $gun_n = str_replace(" ", "_", $value); //Заменяем пробелы на _, иначе браузер не понимает
                                    $gun .= "<option value=$gun_n";
                                    if (($value == $data) or ($data == NULL)) {
                                        $gun .= " selected='selected'";
                                    }
                                    $gun .= '>' . $value;
                                }
                                $gun .= "</select>"; 
                                echo "<td ><div class='$container'>$gun</div></td>";  */
                                //echo"<script type='text/javascript'>userGun(value);</script>";
                                
                            break;
                            
                            
                            default:
                                echo "<td ><div class='$container'><input type='$type' id='$column_name-$id_line' name='$column_name-$id_line' class='$column_name' value='$data' onchange='$js_change_cell'></input></div></td>";
                                break;
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
        
        
        
        
        
</div>
<div id="status">					
</div>

	




<?php

echo '<pre>'; 
//print_r ($gun->name); //фактически - последний клиент из списка
//print_r ($usersGuns[1]['gun'][0]['name']);
//$use = "Акимов С.А.";
$use = "Аксенов В.В.";
$list = array();
$i = 0;
foreach ($usersGuns as $key => $val) {
    $t=$val['user'][0]['full_name'];
    echo "$t";
    
    if ($t == $use) {
        $list[$i] = $val['gun'][0]['name'];
        echo "+";
        $i = $i + 1;
    } else {
        echo "-";
    }
    
}
echo " xxx1 ";
print_r ($countListSentry);
echo " xxx2 ";
print_r ($usersGuns);
//print_r ($rows);
//echo count($usersGuns->gun);    //поcчитать, сколько продуктов имеется в $cats. "products" - обязательно должно совпадать с именем функции getProducts()

echo '</pre>'; 

?> 
       
<?php //echo count($usersGuns[0]->gun); ?> 

    

