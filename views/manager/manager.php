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
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-2">	
                <p>Даты выезда: </p>  
            </div>	
            <div class="col-xs-8">	
                <?php $form = ActiveForm::begin([
                    'id' => 'manager-show-form',  
                ]); ?>
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
                        $param = [
                            'options' =>[ $month => ['Selected' => true]],
                            'onchange'  => 'changeDate()',
                            ];
                        echo Html::dropDownList('month', 'null', $months, $param);
                    ?>
                </div> 
                <div class="col-xs-2">  
                    <?php
                        $years=[
                           2017=>'2017',
                           2018=>'2018',
                           2019=>'2019',
                           2020=>'2020',
                        ];
                        $param = [
                            'options' =>[ $year => ['Selected' => true]],
                            'onchange'  => 'changeDate()',
                            ];
                        echo Html::dropDownList('year', 'null', $years, $param); 
                    ?>
                </div>
                <div class="col-xs-3">  
                    <?= Html::submitButton('Обновить таблицу', ['class' => 'btn btn-primary', 'id' => 'refreshButton', 'name' => 'refreshButton', 'value' => 'refreshButton']) ?>
                </div> 
                <div class="col-xs-3">  
                    <?= Html::submitButton('Добавить строку', ['class' => 'btn btn-success', 'name' => 'add-button', 'value' => 'add-button']) ?>
                </div> 
                <?php ActiveForm::end(); ?>
            
            </div>	<!-- form1-->
            
            <div class="col-xs-2">
                <?php //$form2 = ActiveForm::begin([
                    //'id' => 'manager-add-form',  
               // ]); ?>
                <?//= Html::submitButton('Добавить строку', ['class' => 'btn btn-success', 'name' => 'add-button', 'value' => 'add-button']) ?>
                <?php //ActiveForm::end(); ?>
            </div>  <!-- form2-->
        </div>	<!-- row -->
        
        <div class="row">
            <!-- Блок таблицы рейсов-->
            <div id="div_flights_table">
                <?php   
                    #Рисуем таблицу рейсов
                    if ($listFlight != NULL) { //иначе варнинги идут, если рейсов нет 
                        echo "<table class='table table-striped table-bordered table-hover'>";
                        echo "<h1>Рейсы за ". htmlspecialchars("$months[$month] $year", ENT_QUOTES) ."</h1>"; //Название таблицы
                        
                        #Рисуем шапку таблицы
                        echo "<thead>";
                            echo "<tr class='bg-primary'>";
                            foreach ($ru_rows_array as $key => $value) {
                                echo "<th scope='col'>" . htmlspecialchars("$value", ENT_QUOTES) . "</th>"; 
                            } 
                            echo "</tr>";
                        echo "</thead>";
                        #Рисуем строки таблицы
                        echo "<tbody>";
                            $js_change_cell = "change_cell(this.value, this.id)"; //Ф-ия записи данных в ячейке при их изменении
                            $js_change_list = "change_cell(GetData(this.id), this.id)"; //Ф-ия записи данных в селекте при их изменении
                            $i = 1;
                            foreach ($listFlight as $key_id => $row_content) { //$key_id - номер строки в таблице, $row_content - массив ячеек в ряду
                                
                                $id_line   = $row_content['id']; //$id_line - id строки в БД рейсов
                                $id_status = $row_content['fakticheskij_srok_dostavki']; //$id_status - status строки в БД
                                echo "<tr id='flight-" . htmlspecialchars("$id_line", ENT_QUOTES) . "'>";
                                echo "<th scope='row'>" . htmlspecialchars("$i", ENT_QUOTES) . "</th>"; //Вывод № строки
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
                                            $photo_name_array = null;
                                            $p = 0;
                                            foreach ($listPhoto as $key => $val) {
                                                if ($val['n_flight'] == $id_line) { 
                                                    $photo_name_array[$p] = $val['path'];
                                                    $p = $p + 1;
                                                }   
                                            } 

                                            
                                            if ($photo_name_array) { //Если имеется хотя бы одно фото
                                                $photo = "<button type='button' class='a_button_photo' onclick='get_photo(" . htmlspecialchars("$id_line", ENT_QUOTES) . ")'></button>";
                                            } else {
                                                $photo = "<button type='button' class='a_button_no_photo' onclick='get_photo(" . htmlspecialchars("$id_line", ENT_QUOTES) . ")'></button>";
                                            }
                                            $readonly  = "readonly";
                                            $container = "container_id";
                                            $button    = "<button type='button' class='a_button_delete' onclick='delete_line(" . htmlspecialchars("$data, $table", ENT_QUOTES) . ");'></button>";
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
                                            $id_lineSafe = htmlspecialchars("$id_line", ENT_QUOTES);
                                            $klient = "<select size='0' id='klient-$id_lineSafe' name='klient-$id_lineSafe' class='list_users' onchange='$js_change_list'>";
                                            foreach ($listClients as $value) {
                                                $user_n = str_replace(" ", "_", $value); //Заменяем пробелы на _, иначе браузер не понимает
                                                $user_nSafe = htmlspecialchars("$user_n", ENT_QUOTES);
                                                $klient .= "<option value=$user_nSafe";
                                                if (($value == $data) or ($data == NULL)) {
                                                    $klient .= " selected='selected'";
                                                }
                                                $klient .= '>' . $value;
                                            }
                                            $klient .= "</select>"; 
                                            break;
                                        
                                        case 'fio':
                                            $id_lineSafe = htmlspecialchars("$id_line", ENT_QUOTES);
                                            $fio = "<select size='0' id='fio-$id_lineSafe' name='fio-$id_lineSafe' class='list_users' onchange='$js_change_list'>";
                                            foreach ($listUsers as $value) {
                                                $user_n = str_replace(" ", "_", $value); //Заменяем пробелы на _, иначе браузер не понимает
                                                $user_nSafe = htmlspecialchars("$user_n", ENT_QUOTES);
                                                $fio .= "<option value=$user_nSafe";
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
                                        echo "<td><div class='container_default'>$fio</div></td>"; //
                                    } else if ($column_name == 'klient') {
                                        echo "<td><div class='container_default'>$klient</div></td>"; //
                                    } else {    //иначе просто инпут
                                        echo "<td>
                                            <div class='divButtonPhoto'>
                                                $photo
                                            </div>
                                            <div class='inputId'>
                                                <input type='$type' id='$column_name-$id_line' name='$column_name-$id_line' class='$column_name'  value='$data' onchange='$js_change_cell'></input>
                                            </div>
                                            <div class='divButtonDelet'>
                                                $button
                                            </div>
                                        </td>";
                                    }
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
	</div> <!-- container-fluid -->	

		
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
				



<!-- Блок экспериментов-->

<?php
    //echo '<pre>'; 
    //print_r ($text);
    //echo "$cats"; 
    
  
?>

<!-- /Блок экспериментов-->				