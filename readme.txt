=== Plugin Name ===
Contributors: port20031
Donate link: https://github.com/port20031/motion_1wire_wp
Tags: 1wire , motion
Requires at least: 3.0.1
Tested up to: 4.1
Stable tag: 4.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Displaying Motion (linux) and 1wire in Wordpress.

== Description ==
Данный плагин работает для устройств 1wire :
DS18B20 (type 28) и DS2413 (type 3A)
DS2413 настроен так :
канал А - управляемый ключ ,
канал В - отображает состояние ключа .
Режим охраны заключается в превышении температуры выбираемого датчика или его повреждении.
Возможно внешнее добавление сигнала тревоги через добавление строки в таблицу "префикс"_port20031_alarm 
INSERT INTO `wp_port20031_alarm`( `type_id`) VALUES (1) - на пример .
Оповещение идет на указанный в настройках email 3 раза.
От кого и с какого адреса - системные настройки Wordpress ( можно реализовать через сторонние плагины ).
Опрос датчиков происходит запуском шорт кода [port20031_cron] .
Так же плагин реализует шорт коды для проектов motion (linux) , 
Media Browser(http://mediabrowser.tv ), Plex (https://plex.tv/) 
и формирование ссылок на сохраненные видео .  

Описание работы шорткодов .

[port20031_motion_linux port="8081" width="640" height="480" href="/wordpress/?p=96" ]
ЗАГОЛОВОК[/port20031_motion_linux]

Рисуется картинка программы motion с сервера вордпреса с порта ,
указанного в port="ХХХХ" (если не указан , port="8081") ,
размерами широта  width="ХХХ" , высота height="ХХХ" (если не указан , width="320" height="240"),
также картинка может иметь ссылку , если указан параметр href="/wordpress/?p=96" и
адресс ссылки получается _адрес_сервера_вордпресс_ плюс что указано в  href
( например адресс вордпресс http://192.168.1.98/wordpress/  и href="/wordpress/?p=96" ,
то результатом будет ссылка на http://192.168.1.98/wordpress/?p=96
(вордпрес был установлен в папку wordpress )).
ЗАГОЛОВОК (если он есть ) пишется над картинкой и может быть переведен сторонним плагином .

[port20031_mediabrowser]ЗАГОЛОВОК[/port20031_mediabrowser]

Генерируется ссылка с открытием в новом окне на проект Media Browser(http://mediabrowser.tv/) ,
который установлен на том же сервере (http://192.168.1.98:8096/mediabrowser).
Если  ЗАГОЛОВОК не указан , то он имеет текст URL Media Browser .
В случае когда указан , может быть переведен сторонним плагином .

[port20031_plex]ЗАГОЛОВОК[/port20031_plex]

Генерируется ссылка с открытием в новом окне на проект Plex (https://plex.tv/) ,
который установлен на том же сервере (http://192.168.1.98:32400/web/).
Если  ЗАГОЛОВОК не указан , то он имеет текст URL Plex .
В случае когда указан , может быть переведен сторонним плагином .

[port20031_folder_video folder="/wp-content/gallery_video" sort="date_desc"] ЗАГОЛОВОК [/port20031_folder_video]

Генерируется список ссылок , которые открываются в новом окне , на файлы с расширением .mp4 , находящиеся в папке ,
указанной  в folder="/wp-content/gallery_video" (если не указана , смотрится папка /wp-content/gallery_video
(которая находится в папке установленного вордпреса).
Если  ЗАГОЛОВОК списка  указан , то может быть переведен сторонним плагином .
Если  ЗАГОЛОВОК не указан , то он имеет текст Folder video .
Ссылки могут быть отсортированы , указывая параметр sort :
"filename" - сортировка по возрастанию имени файлов,
"filename_desc" - сортировка по убыванию имени файлов,
"date" - от старшей даты к младшей файлов,
"date_desc" - от младшей даты к старшей файлов ( по умолчанию , если не указана ).

[port20031_temperature id="1" full="full" color="green" date="1"]

Система сама определяет температурные датчики и выдает им номера , в первый опрос пишет что не определен , потом уже собирает данные .
В примере отображается датчик 1 (id="1"),
full - по умолчанию равен full , отображает график в картинке , если любое что-то другое - просто пишет что и сколько
color - цвет графика , указывать в html разметке
date - история данных , выраженная в часах , по умолчанию 240 часов (10 дней )
 
[port20031_cron]
Нужен обязательно !!!!!!!
Шорт код опроса температурных датчиков  1wire ( сохраняет в базе для рисования графика ), 
управление ключами по расписаниям , режим сигнализации ( опрос выполняется в момент запуска этого шорт кода )
чтобы  были регулярные данные  надо смотреть страницу с этим шорткодом регулярно ))).
В кроне смотрим каждую минуту 
*/1 * * * * wget http://192.168.1.98/wordpress/?p=95 > /dev/null 2>&1
http://192.168.1.98/wordpress/?p=95 - в примере адрес со страницей , на которой применен шорт код.

Настройка 1wire .
wget https://www.dropbox.com/s/hpnbttcegq8fy15/owfs_libusb.patch
patch -Np1 -l -i owfs_libusb.patch
owserver -d /dev/ttyUSB0 -p 3000
Надо смотреть как подключен адаптер и правим systemd файл на старт рута.
В плагине считается, что 1wire работает на 3000 порту
/opt/owfs/bin/owserver -d /dev/ttyUSB0 -p 3000 
Для проверки устройств через веб.
/opt/owfs/bin/owhttpd -p 3001 -s localhost:3000
Установка вордпрес.
В вордпресе делаем динамическое корневое имя в файле wp-config.php
define('WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST'] . '/wm');
define('WP_HOME',    'http://' . $_SERVER['HTTP_HOST'] . '/wm');
/wm - в примере  это папка куда установили вордпресс
После этого Вордпресс не будет спрашивать доступ к ФТП и 
будет напрямую работать с файлами и обновляться 
define('FS_METHOD', 'direct');

Настройка мотион.
В файле systemd указать что запуск от рута группы рут
В конфигурационном файле много нюансов (((( , смысл программы запись и вещание видео с камеры 
( или камер - больше камер , мощнее компьютер ))) ) , фото движения
Плагин Folder Gallery для отображения фото из папки , доступной по веб 
В крон положить скрипты перемещения  файлов  от результата работы мотион 
пример для картинок (обратить внимание на используемые пути) :
./cron_move_files_motion.sh

#!/bin/bash
#proverka papki
dir_otkuda=/var/motion
dir_kuda=/var/www/html/wordpress/wp-content/gallery
if ! [ -d  $dir_kuda ]; then
echo 'No directory'
mkdir -p $dir_kuda
fi
#peremeschaem files
#mv otkuda kuda
mv -f $dir_otkuda/*.jpg $dir_kuda
#prava
chown -R apache:apache $dir_kuda

от рута добавляем строку командой  crontab -e

*/1 * * * * sh /var/www/html/cron_move_files_motion.sh
 
Аналогия для видео с конвертацией для веба при слабых нагрузках
#######################################################
#!/bin/bash
#proverka papki
dir_otkuda=/var/motion/
dir_kuda=/var/www/html/wordpress/wp-content/gallery_video
if ! [ -d  $dir_kuda ]; then
#echo 'No directory'
mkdir -p $dir_kuda
fi
#peremeschaem files
#mv otkuda kuda
#mv -f /var/motion/*.avi $dir_kuda
echo "Send files..."
find $dir_otkuda -type f -name "*.avi"|while read filename;
do
        new_filename=$dir_kuda"/"$(echo $filename | sed -e 's/^.*\///') ;
        #new_filename=$(echo "$filename" | sed "s/^\(.*\)avi$/\1avi/g");
        echo "$filename";
        echo "$new_filename";

        mv -f $filename $new_filename;
done
echo "Convert video..."
#prava

chown -R apache:apache $dir_kuda
#find $dir_kuda -type f -name "*.avi" -print
find $dir_kuda -type f -name "*.avi" | while read filename;
do
        new_filename=$(echo "$filename" | sed "s/^\(.*\)avi$/\1mp4/g");
        echo "$filename";
        echo "$new_filename";
        ffmpeg -y -i "$filename" -c:v libx264 -profile:v high -level 4.2 -crf 18 -maxrate 10M -bufsize 16M -pix_fmt yuv420p -vf "scale=iw*sar:ih, scale='if(gt(iw,ih),min(1920,iw),-1)':'if(gt(iw,ih),-1,min(1080,ih))'" -x264opts bframes=3:cabac=1 -movflags faststart -c:a libfdk_aac -b:a 320k -ac 2 -y -s 640x480  "$new_filename" & wait $!;
        rm -f "$filename";
       
done
chown -R apache:apache $dir_kuda


Установка рекомендуемых плагинов .

Easy WP SMTP (https://wordpress.org/plugins/easy-wp-smtp/)-
почта через яндекс ,жмаил и прочее . - для отправки сообщений на почту
Folder Gallery (https://wordpress.org/plugins/folder-gallery/)-
отображает картинки и фото с папки .
MiwoFTP (https://wordpress.org/plugins/miwoftp/)- 
http://miwisoft.com/wordpress-plugins/miwoftp-wordpress-file-manager
Browsing files and folders .
qTranslate X (https://wordpress.org/plugins/qtranslate-x/)-
перевод страниц на язык .
Triagis® WordPress Security Evaluation  (https://wordpress.org/plugins/triagis-security-evaluation/)-
безопасность
WP Lightbox 2 (https://wordpress.org/plugins/wp-lightbox-2/)-
для отображения Folder Gallery и прочего .
Login-Logout (https://wordpress.org/plugins/login-logout/)-
виджет авторизации .
User Access Manager(https://wordpress.org/plugins/user-access-manager/)
User Access Manager Private Extension (https://wordpress.org/plugins/user-access-manager-private-extension/)-
ограничение доступа к страницам и прочее .
Rename wp-login.php (https://wordpress.org/plugins/rename-wp-login/)-
переименование скрипта логина на сайт.




== Changelog ==

= 1.0 =
* A change since the previous version.
* Another change.



== Upgrade Notice ==

= 1.0 =
Upgrade .




