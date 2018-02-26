## Описание
Сервис для частного охранного предприятия, позволяющий вести контроль и учёт рейсов охранников с охраняемым грузом. Менеджер заносит в базу рейсы (заказчик, время отправления, стоимость и т.д.) и распределяет, кому из охранников какой рейс достанется. После завершения рейса автоматически подсчитывается его цена, с учётом возможного простоя и неустоек. Охранник же со своей стороны (используя любой смартфон) фиксирует номер автомашины, реальные время отправления и завершения рейса, а так же делает и отправляет фотографии (экипажа и пломб). 


## Для чего это
Сервис сделан ради улучшения контроля за охраной грузов фирмой, осуществляющей услуги по охране. До его внедрения охранники перед выездом инструктировались устно, а при отправлении и по завершении рейса отзванивались дежурному, который вносил информацию в табель рейсов вручную. На основе этого табеля, менеджер в экселе рассчитывал сроки доставки, неустойки и прочие финансы. Фиксация пломб на кузовах автотранспорта велась так же вручную. Сервис позволил автоматизировать эту работу. Кроме того, здесь есть отдельная страница для учёта табельного оружия и прочих спецсредств, выдаваемых охранникам. В таблице видны все охранники, получившие на текущий момент оружие и не сдавшие его. Можно просмотреть этот список за любую дату.

## Инсталляция
Скопировать файлы на хостинг, импортировать БД clear_batabase.sql, переименовать config-example.ini в cofig.ini и задать в нём параметры доступа к БД: hostname, username, password, dbName.

## Скриншоты
Интерфейс менеджера  
![Интерфейс менеджера](screenshots/Manager1.png)

Изменение даты рейса  
![Изменеие даты рейса](screenshots/Manager2.png)

Список охранников  
![Список охранников](screenshots/Guards.png)

Список фирм клиентов  
![Список фирм клиентов](screenshots/Clients.png)

Список зарегистрированного оружия  
![Список фирм клиентов](screenshots/Guns.png)

Постовая ведомость  
![Список фирм клиентов](screenshots/Sentry.png)

Галерея фотографий с рейса  
![Галерея фотографий с рейса](screenshots/Manager5.png)

Интерфейс охранника (выбор дня, на котором есть рейсы)  
![Интерфейс охранника (выбор дня, на котором есть рейсы)](screenshots/Guard1.png)