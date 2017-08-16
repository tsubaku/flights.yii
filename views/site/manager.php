
		<!-- Дальше содержимое первой вкладки   -->
		<div id="layerA">
			
			<div class="button_container">	
				<p>Даты выезда:</p>
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
			
			<div class="button_container">				
                <button type="button" onclick="show_flights_table();" id="a_show_flights_table" class="a_demo_three b_green">
                    Обновить таблицу рейсов
                </button>
			</div>
			
			<!-- Блок таблицы рейсов-->
			<div id="div_flights_table">
				<table><caption><strong>Рейсы</strong></caption><tbody><tr><td><strong>№</strong></td><td class=""><strong>Номер рейса</strong></td><td class=""><strong>Дата выезда</strong></td><td class=""><strong>Время</strong></td><td class=""><strong>Клиент</strong></td><td class=""><strong>Подклиент</strong></td><td class=""><strong>Номер машины</strong></td><td class=""><strong>Принятие под охрану</strong></td><td class=""><strong>Сдача с охраны</strong></td><td class=""><strong>Состав ОХР</strong></td><td class=""><strong>ФИО</strong></td><td class=""><strong>Выдано</strong></td><td class=""><strong>Машина</strong></td><td class=""><strong>Срок доставки</strong></td><td class=""><strong>Принятие</strong></td><td class=""><strong>Сдача</strong></td><td class=""><strong>Фактич. срок доставки</strong></td><td class=""><strong>Простой часы</strong></td><td class=""><strong>Простой, ставка за охранника</strong></td><td class=""><strong>Простой сумма</strong></td><td class=""><strong>Ставка без НДС</strong></td><td class=""><strong>Ставка с НДС</strong></td><td class=""><strong>Счёт</strong></td><td class=""><strong>ЗП</strong></td><td class=""><strong>Простой</strong></td><td class=""><strong>Аренда машины</strong></td><td class=""><strong>Оплата машины</strong></td><td class=""><strong>ИТОГО</strong></td><td class=""><strong>ЗП+Простой</strong></td><td class=""><strong>Статус</strong></td><td class=""><strong></strong></td></tr></tbody></table>
			</div>
						
			<div>			
                <button type="button" onclick="add_line();" id="a_add_line" class="a_demo_three b_green">
                   Добавить строку
                </button>
			</div>
		</div><!-- конец layerA   -->


		
<!-- Блок экспериментов-->







<!-- /Блок экспериментов-->

		
		
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
				



				