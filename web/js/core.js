/*
 Основная логика сервиса
 */

 
//<!-- AJAX блок -->
function XmlHttp()
{
    var xmlhttp;
    try{xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");}
    catch(e)
    {
        try {xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");} 
        catch (E) {xmlhttp = false;}
    }
    if (!xmlhttp && typeof XMLHttpRequest!='undefined')
    {
        xmlhttp = new XMLHttpRequest();
    }
    return xmlhttp;
}


/* function ajax(param)
{
    if (window.XMLHttpRequest) req = new XmlHttp();
    method=(!param.method ? "POST" : param.method.toUpperCase());

    if(method=="GET")
    {
        send=null;
        param.url=param.url+"&ajax=true";
    }
    else
    {
        send="";
        for (var i in param.data) send+= i+"="+param.data[i]+"&";
        // send=send+"ajax=true"; // если хотите передать сообщение об успехе
    }

    req.open(method, param.url, true);
    if(param.statbox)document.getElementById(param.statbox).innerHTML = '<img src="./img/wait.gif">';
    req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    req.send(send);
    req.onreadystatechange = function()
    {
        if (req.readyState == 4 && req.status == 200) //если ответ положительный
        {
            if(param.success)param.success(req.responseText);
        }
    }
}
//<!-- конец AJAX блока -->  */
 
 


//Вспомогательная функция, берёт выбранное значение из списка
function GetData(name_selector)
    {
        // получаем индекс выбранного элемента
        var selind = document.getElementById(name_selector).options.selectedIndex;
        var txt= document.getElementById(name_selector).options[selind].text; //Выбранный пункт списка
        var val= document.getElementById(name_selector).options[selind].value;//Его номер по порядку
        //alert("Теxt= "+ txt +" " + "Value= " + val);
        return txt;
    }

    

// ++Запись изменённой ячейки (отправка её содержимого, column и id php-скрипту)
function change_cell(cell_value, cell_id)
{
    //console.log("cell_value: "+cell_value+" cell_id: "+cell_id+" \n");
    var position_minus = cell_id.indexOf("-");        //найти позицию символа -
    var column_in_db = cell_id.substring(0, position_minus);//все символы до -, включительно (получаем название столбца в БД)
    var id_in_db = cell_id.substring(position_minus+1, cell_id.length);//все символы от - и до конца включительно (получаем id строки в БД)
    console.log("column_in_db: "+column_in_db+" id_in_db: "+id_in_db+" cell_value: "+cell_value+" \n");
    $.ajax({
            url:"index.php?r=manager/change",
            type:"POST",
            async: true,
            statbox:"status",
            
            data:
            {
                cell_value:cell_value,    
                id_in_db:id_in_db,
                column_in_db:column_in_db,
                _csrf: yii.getCsrfToken(),
            },
            success: function (data) {
                document.getElementById("status").innerHTML=''; //удалить значок ожидания
                console.log(data);
                
                try {
                    var changed_cells = JSON.parse(data);
                    if ((column_in_db == 'prostoj_summa') || (column_in_db == 'stavka_bez_nds') || (column_in_db == 'stavka_s_nds')){
                        //console.log("refresh_cell");    
                        var cell_adress = "schet-" + id_in_db;
                        document.getElementById(cell_adress).value = changed_cells.schet; //Обновляем ячейку "Счёт"
                    }
                    if ( ((column_in_db == 'prinjatie') || (column_in_db == 'sdacha')) ){
                        var cell_adress = "fakticheskij_srok_dostavki-" + id_in_db;        

                        document.getElementById(cell_adress).value = changed_cells.fakticheskij_srok_dostavki; //Обновляем ячейку "fakticheskij_srok_dostavki"
                    }
                    if ((column_in_db == 'prostoj_chasy') || (column_in_db == 'prostoj_stavka_za_ohrannika')){
                            
                        var cell_adress = "prostoj_summa-" + id_in_db;
                        document.getElementById(cell_adress).value = changed_cells.prostoj_summa; //Обновляем ячейку "prostoj_summa"
                        
                        var cell_adress2 = "schet-" + id_in_db;
                        document.getElementById(cell_adress2).value = changed_cells.schet; //Обновляем ячейку "Счёт"
                    }
                    
                    if (column_in_db == 'arenda_mashin'){
                            
                        var cell_adress = "oplata_mashin-" + id_in_db;
                        document.getElementById(cell_adress).value = changed_cells.oplata_mashin; //Обновляем ячейку "oplata_mashin"
                        
                        var cell_adress = "itogo-" + id_in_db;
                        document.getElementById(cell_adress).value = changed_cells.itogo; //Обновляем ячейку "itogo"
                    }
                    
                    if ((column_in_db == 'zp') || (column_in_db == 'prostoj') || (column_in_db == 'oplata_mashin')){
                            
                        var cell_adress = "itogo-" + id_in_db;
                        document.getElementById(cell_adress).value = changed_cells.itogo; //Обновляем ячейку "itogo"
                        
                        var cell_adress = "zp_plus_prostoj-" + id_in_db;
                        document.getElementById(cell_adress).value = changed_cells.zp_plus_prostoj; //Обновляем ячейку "zp_plus_prostoj"
                    } 
                } catch (err) {
                    
                }
            },
            error: function (error) {
                console.log("eror_change_cell");
            }
        })    
}


//++Запись изменённой ячейки Постовой ведомости (отправка её содержимого, column и id в SentryController.php)
function changeSentry(cell_value, cell_id)
{
    //console.log("cell_value: "+cell_value+" cell_id: "+cell_id+" \n");
    var position_minus = cell_id.indexOf("-");        //найти позицию символа -
    var column_in_db = cell_id.substring(0, position_minus);//все символы до -, включительно (получаем название столбца в БД)
    var id_in_db = cell_id.substring(position_minus+1, cell_id.length);//все символы от - и до конца включительно (получаем id строки в БД)
    console.log("column_in_db: "+column_in_db+" \n"+" id_in_db: "+id_in_db+" \n"+" cell_value: "+cell_value+" \n");
    $.ajax({
            url:"index.php?r=sentry/changesentry",
            type:"POST",
            async: true,
            statbox:"status",
            
            data:
            {
                cell_value:cell_value,    
                id_in_db:id_in_db,
                column_in_db:column_in_db,
                _csrf: yii.getCsrfToken(),
            },
            success: function (data) {
                document.getElementById("status").innerHTML=''; //удалить значок ожидания
                console.log(data);
                //если изменяли охранника в постовой ведомости, то изменить и его оружие в соседней графе таблицы
                if (column_in_db == 'full_name') {   
                    var changed_cells = JSON.parse(data);
                    //console.log("изменён охранник "+cell_value+" " + changed_cells[3] + "\n");
                    var list = document.getElementById('gun-'+id_in_db); //элемент
                    while (list.lastChild) {
                        list.removeChild(list.lastChild);
                    }
                    
                    //Добавим "Оружие не выбрано"
                    var option = document.createElement("option");
                    option.value = "no_gun";
                    option.text = "Оружие не выбрано";
                    list.appendChild(option);
                    
                    array = changed_cells[3];
                    for (var i = 0; i < array.length; i++) {
                        var option = document.createElement("option");
                        option.value = array[i];
                        option.text = array[i];
                        list.appendChild(option);
                    }
                }

            },
            error: function (error) {
                console.log("eror_changeSentry");
            }
        })    
}

//++Запись изменённой ячейки в списке юзеров (отправка её содержимого, column и id php-скрипту)
// Пока что используется только для смены отдела охранника.
function changeUser(cell_value, cell_id)
{
    //console.log("cell_value: "+cell_value+" cell_id: "+cell_id+" \n");
    var position_minus = cell_id.indexOf("-");        //найти позицию символа -
    var column_in_db = cell_id.substring(0, position_minus);//все символы до -, включительно (получаем название столбца в БД)
    var id_in_db = cell_id.substring(position_minus+1, cell_id.length);//все символы от - и до конца включительно (получаем id строки в БД)
    console.log("column_in_db: "+column_in_db+" \n"+" id_in_db: "+id_in_db+" \n"+" cell_value: "+cell_value+" \n");
    $.ajax({
            url:"index.php?r=signup/changeuser",
            type:"POST",
            async: true,
            statbox:"status",
            
            data:
            {
                cell_value:cell_value,    
                id_in_db:id_in_db,
                column_in_db:column_in_db,
                _csrf: yii.getCsrfToken(),
            },
            success: function (data) {
                document.getElementById("status").innerHTML=''; //удалить значок ожидания
                console.log('ok '+data);
                //если изменяли охранника в постовой ведомости, то изменить и его оружие в соседней графе таблицы
                
            },
            error: function (error) {
                console.log("eror_changeUser");
            }
        })    
}


//----- ++Скрипт загрузки фото для рейса из интерфейса охранника ----- 
function submitFile( jQuery ) {
(function($){
	var files;  // Глобальная переменная куда будут располагаться данные файлов. С ней будем работать

	// Вешаем функцию на событие
	$('input[type=file]').change(function(){
		ajax_respond.style.visibility='hidden';	//Скрываем ответ сервера
		files = this.files;	// Получим данные файлов и добавим их в переменную
		event.stopPropagation(); // Остановка происходящего
		event.preventDefault();  // Полная остановка происходящего

		// Создадим данные формы и добавим в них данные файлов из files
		var data1 = new FormData();
		$.each( files, function( key, value ){
			data1.append( key, value );
			console.log("№1 " + number_flight);
			data1.append("number_flight", number_flight);
		});
		console.log(data1.getAll('number_flight'));

        //console.log("data1 " + data1);
		// Отправляем запрос
        //var csrfToken = $('meta[name="csrf-token"]').attr("content");
		nFlight = 5;
        $.ajax({
			url: 'index.php?r=guard/uploadfiles',
			type: 'POST',
			statbox:"status",
            data: data1,
			cache: false,
            
			dataType: 'json',	// тип загружаемых данных
			processData: false, // Не обрабатываем файлы (Don't process the files)
			contentType: false, // Так jQuery скажет серверу что это строковой запрос
			success: function( respond, textStatus, jqXHR ){
				//console.log(respond);
                //document.getElementById("status").innerHTML='oooo'; //удалить значок ожидания

                // Если все ОК
				if( typeof respond.error === 'undefined' ){
					// Файлы успешно загружены, делаем что-нибудь здесь

					// выведем пути к загруженным файлам в блок '.ajax-respond'
					//var files_path = respond.files;
					//var html = '';
					//$.each( files_path, function( key, val ){ html += val +'<br>'; } )
					//$('.ajax-respond').html( html );
					
				//	var html = "<img src='success.png' alt='OK' />";
				//	$('.ajax-respond').html( html );
					//document.getElementById("ajax-respond").innerHTML="";
					ajax_respond.style.visibility='visible'; //Показываем ответ сервера
				}
				else{
					console.log('ОШИБКИ ОТВЕТА сервера: ' + respond.error );
				}
			},
			error: function( jqXHR, textStatus, errorThrown ){
				console.log('ОШИБКИ AJAX запроса: ' + textStatus );
			}
		});
	});

	})(jQuery)
}	

$( document ).ready( submitFile );	//Запускаем ф-ию submitFile после полной зарузки страницы		
//---------------------------

  