=== Plugin Name ===
Contributors: port20031
Donate link: https://github.com/port20031/motion_1wire_wp
Tags: 1wire , motion
Requires at least: 3.0.1
Tested up to: 4.4
Stable tag: 4.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Motion 1Wire WP .


== Installation ==

1.Upload the entire motion_1wire_wp folder to the /wp-content/plugins/ directory.

2.Activate the plugin through the 'Plugins' menu in WordPress.

3.You will find 'Motion 1Wire WP' menu in your WordPress admin panel - Options.

For basic usage, you can also have a look at the plugin homepage.
( https://github.com/port20031/motion_1wire_wp )


== Frequently Asked Questions ==

Для добавления типов устройств или изменения логики работы пишите на email : port20031@yandex.ru

To add device types or changing the logic of the send an email : port20031@yandex.ru


== Screenshots ==

https://github.com/port20031/motion_1wire_wp/blob/master/screencapture1.png

https://github.com/port20031/motion_1wire_wp/blob/master/screencapture2.png


== Description ==

Плагин реализует набор функций технологии "Умный дом" на технологии 1wire и Motion (linux).

The plugin implements the functionality of technology "Smart House".

Displaying Motion (linux) and 1wire in Wordpress.

Он работает для устройств 1wire :

DS18B20 (type 28) и DS2413 (type 3A).

DS2413 настроен так :

канал А - управляемый ключ ,

канал В - отображает состояние ключа .

Режим охраны заключается в превышении температуры выбираемого датчика или его повреждении.

Возможно внешнее добавление сигнала тревоги через добавление строки в таблицу "префикс"_port20031_alarm базы Wordpress.

INSERT INTO `wp_port20031_alarm`( `type_id`) VALUES (1) - на пример .

Пример из мотион :

on_picture_save  echo "insert into security(camera, filename, frame, file_type, time_stamp, event_time_stamp)
values('%t', '%f', '%q', '%n', '%Y-%m-%d %T', '%C');" | mysql -uroot -p123456 motion;

on_movie_start  echo "insert into security(camera, filename, frame, file_type, time_stamp, event_time_stamp)
values('%t', '%f', '%q', '%n', '%Y-%m-%d %T', '%C');" | mysql -uroot -p123456 motion;

Оповещение идет на указанный в настройках email 3 раза.

От кого и с какого адреса - системные настройки Wordpress ( можно реализовать через сторонние плагины ).

Опрос датчиков происходит запуском шорт кода [port20031_cron] .

Так же плагин реализует шорт коды для проектов motion (linux) ,Media Browser(https://emby.media ), Plex (https://plex.tv/)

и формирование ссылок на сохраненные видео .


Описание работы шорткодов .


[port20031_motion_linux port="8081" width="640" height="480" href="/wordpress/?p=96" ]
ЗАГОЛОВОК[/port20031_motion_linux]

Рисуется картинка программы motion с сервера вордпреса с порта , указанного в port="ХХХХ" (если не указан , port="8081") ,
размерами широта  width="ХХХ" , высота height="ХХХ" (если не указан , width="320" height="240"),
также картинка может иметь ссылку , если указан параметр href="/wordpress/?p=96" и
адресс ссылки получается _адрес_сервера_вордпресс_ плюс что указано в  href
( например адресс вордпресс http://ipserver/wordpress/  и href="/wordpress/?p=96" ,
то результатом будет ссылка на http://ipserver/wordpress/?p=96 (вордпрес был установлен в папку wordpress )).
ЗАГОЛОВОК (если он есть ) пишется над картинкой и может быть переведен сторонним плагином .


[port20031_mediabrowser]ЗАГОЛОВОК[/port20031_mediabrowser]

Генерируется ссылка с открытием в новом окне на проект Media Browser(http://mediabrowser.tv/) ,
который установлен на том же сервере ( http://ipserver:8096/mediabrowser). Если  ЗАГОЛОВОК не указан ,
то он имеет текст URL Media Browser . В случае когда указан , может быть переведен сторонним плагином .


[port20031_plex]ЗАГОЛОВОК[/port20031_plex]

Генерируется ссылка с открытием в новом окне на проект Plex (https://plex.tv/) ,
который установлен на том же сервере (http://ipserver:32400/web/). Если  ЗАГОЛОВОК не указан ,
то он имеет текст URL Plex . В случае когда указан , может быть переведен сторонним плагином .


[port20031_folder_video folder="/wp-content/gallery_video" sort="date_desc"] ЗАГОЛОВОК [/port20031_folder_video]

Генерируется список ссылок на файлы с расширением .mp4, которые открываются в новом окне и находятся в папке ,
указанной  в folder="/wp-content/gallery_video" (если не указана , смотрится папка /wp-content/gallery_video
(которая находится в папке установленного вордпреса). Если  ЗАГОЛОВОК списка  указан ,
то может быть переведен сторонним плагином . Если  ЗАГОЛОВОК не указан , то он имеет текст Folder video .

Ссылки могут быть отсортированы , указывая параметр sort :

"filename" - сортировка по возрастанию имени файлов,

"filename_desc" - сортировка по убыванию имени файлов,

"date" - от старшей даты к младшей файлов,

"date_desc" - от младшей даты к старшей файлов ( по умолчанию , если не указана ).


[port20031_temperature id="1" full="full" color="green" date="1"]

Система сама определяет температурные датчики и выдает им номера , в первый опрос пишет что не определен ,
потом уже собирает данные . В примере отображается датчик 1 (id="1"), full - по умолчанию равен full
(отображает график в картинке , если любое что-то другое - просто пишет что и сколько ) ,
color - цвет графика ( указывать в html разметке ), date - история данных , выраженная в часах ,
по умолчанию 240 часов (10 дней ).


[port20031_cron]

Нужен обязательно !!!!!!!

Шорт код опроса температурных датчиков  1wire ( сохраняет в базе для рисования графика ),
управление ключами по расписаниям , режим сигнализации ( опрос выполняется в момент запуска этого шорт кода )
чтобы  были регулярные данные  надо смотреть страницу с этим шорткодом регулярно ))).
В кроне смотрим каждую минуту

*/1 * * * * wget -O /dev/null -o /dev/null http://ipserver/wordpress/?p=95 > /dev/null 2>&1

http://localhost/wordpress/?p=95 - в примере адрес со страницей , на которой применен шорт код.


[port20031_status_key id="1"]

Шорт код отображения состояния ключей  1wire .

Желательно отображать после шорт кода [port20031_cron] , т.к. читает результат последнего крона из базы .

Если id не указан , то отображаются все ключи в системе .

Если id  указан , то отображается только статус этого ключа  в системе . В контенте ключа можно дать название ключу.

Пример :

[port20031_status_key][/port20031_status_key]

[port20031_status_key id="4"]Свет аквариума [/port20031_status_key]

[port20031_status_key id="4"][/port20031_status_key]

[port20031_status_key id="1"]Свет комнаты[/port20031_status_key]



Настройка 1wire .

wget https://www.dropbox.com/s/hpnbttcegq8fy15/owfs_libusb.patch

patch -Np1 -l -i owfs_libusb.patch

Надо смотреть как подключен адаптер и правим systemd файл на старт рута.

В плагине считается, что 1wire работает на 3000 порту

/opt/owfs/bin/owserver -d /dev/ttyUSB0 -p 3000

Для проверки устройств через веб.

/opt/owfs/bin/owhttpd -p 3001 -s localhost:3000

vi /usr/lib/systemd/system/owserver.service

[Unit]
Description=Backend server for 1-wire control
Documentation=man:owserver(1)
After=syslog.target network.target

[Service]
Type=forking
ExecStart=/opt/owfs/bin/owserver -d /dev/ttyUSB0 -p 3000
Restart=on-failure
User=root
Group=root

[Install]
WantedBy=multi-user.target


Установка вордпрес.

В вордпресе делаем динамическое корневое имя в файле wp-config.php

define('WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST'] . '/wm');

define('WP_HOME',    'http://' . $_SERVER['HTTP_HOST'] . '/wm');

/wm - в примере  это папка куда установили вордпресс

После этого Вордпресс не будет спрашивать доступ к ФТП и будет напрямую работать с файлами и обновляться

define('FS_METHOD', 'direct');


Настройка мотион.

В файле systemd указать что запуск от рута группы рут. В конфигурационном файле много нюансов (((( ,
смысл программы запись и вещание видео с камеры ( или камер - больше камер , мощнее компьютер ))) ) , фото движения.
Пример конфигурационных файлов motion в motion_etc.zip


Плагин Folder Gallery для отображения фото из папки , доступной по веб .

В крон положить скрипты перемещения  файлов  от результата работы мотион
(обратить внимание на используемые пути и пользователя веб сервера) :

Пример для картинок

./cron_move_foto_motion.sh

от рута добавляем строку командой  crontab -e

*/1 * * * * sh /var/www/html/cron_move_foto_motion.sh

Аналогия для видео с конвертацией для веба при слабых нагрузках

./cron_move_video_motion.sh


Установка рекомендуемых плагинов .

Easy WP SMTP (https://wordpress.org/plugins/easy-wp-smtp/)- почта через яндекс ,жмаил и прочее . - для отправки
сообщений на почту

Folder Gallery (https://wordpress.org/plugins/folder-gallery/)- отображает картинки и фото с папки .

MiwoFTP (https://wordpress.org/plugins/miwoftp/)- http://miwisoft.com/wordpress-plugins/miwoftp-wordpress-file-manager
-Browsing files and folders .

qTranslate X (https://wordpress.org/plugins/qtranslate-x/)- перевод страниц на язык .

Triagis® WordPress Security Evaluation  (https://wordpress.org/plugins/triagis-security-evaluation/)- безопасность

WP Lightbox 2 (https://wordpress.org/plugins/wp-lightbox-2/)- для отображения Folder Gallery и прочего .

Login-Logout (https://wordpress.org/plugins/login-logout/)- виджет авторизации .

User Access Manager(https://wordpress.org/plugins/user-access-manager/)

User Access Manager Private Extension (https://wordpress.org/plugins/user-access-manager-private-extension/)-
ограничение доступа к страницам и прочее .

Rename wp-login.php (https://wordpress.org/plugins/rename-wp-login/)- переименование скрипта логина на сайт.


== Changelog ==

= 1.0 =
* A change since the previous version.
* Another change.


== Upgrade Notice ==

= 1.0 =
Upgrade .



