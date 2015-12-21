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
