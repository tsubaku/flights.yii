/*
 Скрипты для интерфейса охранника.
 */

 

//Показать один рейс для охранника
function show_one_flight(dat)
{		
	//console.log("период введён1: " + year + " " + month);
	//user_id_current = 103;
    console.log("dat: "+ dat + " \n");
	console.log("user_id_current: "+ user_id_current + " \n");
	$.ajax({
		url:"index.php?r=guard/showflight",
		type:"post",
		statbox:"status",
		data:
		{
			dat:dat,
			user_id_current:user_id_current,
            _csrf: yii.getCsrfToken(),
		},
		success: function (data) {
			document.getElementById("status").innerHTML=''; //удалить значок ожидания
			console.log("data = "+data);
			//document.getElementById("div_show_one_flight").innerHTML=data;
			var array_data_one_flight = JSON.parse(data);
			//console.log("# "+array_data_one_flight["sdacha_s_ohrany"]);
			if (array_data_one_flight['vremja']){	//Время
				array_data_one_flight['vremja'] = array_data_one_flight['vremja'].replace(":00.000000","");
				//array_data_one_flight[2] = array_data_one_flight[2].substring(0, 10);
			}
			if (array_data_one_flight['prinjatie']){	//Приняте
				array_data_one_flight['prinjatie'] = array_data_one_flight['prinjatie'].replace(".000000","");
				array_data_one_flight['prinjatie'] = array_data_one_flight['prinjatie'].substring(0, 10)+"T"+array_data_one_flight['prinjatie'].substring(11, 19);
			}
			if (array_data_one_flight['sdacha']){	//Сдача
				array_data_one_flight['sdacha'] = array_data_one_flight['sdacha'].replace(".000000","");
				array_data_one_flight['sdacha'] = array_data_one_flight['sdacha'].substring(0, 10)+"T"+array_data_one_flight['sdacha'].substring(11, 19);
			}
			
            console.log("data_id = "+array_data_one_flight['id']);
            
			number_flight = array_data_one_flight['id'];	//номер рейса
			document.getElementById("div_right_string0").innerHTML="Номер рейса: "+array_data_one_flight['id'];			
			document.getElementById("div_right_string1").innerHTML=array_data_one_flight['data_vyezda' ];
			document.getElementById("div_right_string2").innerHTML=array_data_one_flight['vremja'];			
			document.getElementById("div_right_string3").innerHTML=array_data_one_flight['klient' ];			
			document.getElementById("div_right_string5").innerHTML=array_data_one_flight['prinjatie_pod_ohranu'];			
			document.getElementById("div_right_string6").innerHTML=array_data_one_flight['sdacha_s_ohrany'];			
			
			document.getElementById("div_right_string4").innerHTML="<input type='text' id='nomer_mashiny-"+array_data_one_flight['id']+"'name='nomer_mashiny-"+array_data_one_flight['id']+"' class='nomer_mashiny_mobi' value='"+array_data_one_flight['nomer_mashiny' ]+"' onchange='change_cell(this.value, this.id)'></input>";	
			
			document.getElementById("div_right_string7").innerHTML="<input type='datetime-local' id='prinjatie-"+array_data_one_flight['id']+"'name='prinjatie-"+array_data_one_flight['id']+"' class='prinjatie_mobi' value='"+array_data_one_flight['prinjatie']+"' onchange='change_cell(this.value, this.id)'></input>";
			
			document.getElementById("div_right_string8").innerHTML="<input type='datetime-local' id='sdacha-"+array_data_one_flight['id']+"'name='sdacha-"+array_data_one_flight['id']+"' class='sdacha_mobi' value='"+array_data_one_flight['sdacha']+"' onchange='change_cell(this.value, this.id)'></input>";
			
			//document.getElementById("div_right_string1").innerHTML=array_data_one_flight[1];
			//document.getElementById("div_right_string2").innerHTML=array_data_one_flight[2];
			//document.getElementById("div_right_string3").innerHTML=array_data_one_flight[3];
			//document.getElementById("div_right_string4").innerHTML=array_data_one_flight[4];

		},
		error: function (error) {
			console.log("eror_show_one_flight");
		}
	})	
	
}
window.show_one_flight = show_one_flight;




	
//--------------------------
//Внешний вид и настройки календаря для выбора даты 
$(function(){
	//var array_date_of_departure = ["2017-10-03","2017-10-04"];
	//console.log("выезды: "+window.array_date_of_departure);	
	$("#calendar").datepicker({
        inline: true,
		language: 'ru',
        changeYear: true,
        changeMonth: true,
		
		defaultDate:'0Y',
		minDate:'-3Y',
		maxDate:'+3Y',
		buttonImage:'../img/favicon.png', 
		showOn:'both', 
		buttonImageOnly:true,
		
		// Перед показом каждой даты - прогоняем ее по массиву событий, чтобы выставить свойства.
        // Свойств 3: вкл/выкл, класс оформления и текст, который вставляется в title элемента td.
		beforeShowDay: function(date) {
			//Берём число date в календаре и приводим к формату yy-mm-dd, а затем проверяем, есть ли оно в массиве array_date_of_departure
			if($.inArray($.datepicker.formatDate('yy-mm-dd', date ), array_date_of_departure) > -1) {
				return [true,"date_of_departure","available"];
			}
			else {
				return [false,"not_date_of_departure","not available"];
			}
		},
		//onSelect - действия при клике по дате. https://habrahabr.ru/post/111155/
		onSelect: function(date) { 		//date - дата календаря, на которую нажали
			//console.log(date);
            setTimeout(function() { document.getElementsByName('buttonModal')[0].click(); },1) //ШАМАНСТВО. Программно нажимаем кнопку 
                                                                                               //вызова модального окна           
			show_one_flight(date);      //Внести данные рейса в модальное окно
            return false;
		},
    });
});


//-------------------------







