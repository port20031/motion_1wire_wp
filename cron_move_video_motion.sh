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
        ffmpeg -y -i "$filename" -c:v libx264 -profile:v high -level 4.2 -crf 18 -maxrate 10M -bufsize 16M -pix_fmt yuv420p -vf "scale=iw*sar:ih,scale='if(gt(iw,ih),min(1920,iw),-1)':'if(gt(iw,ih),-1,min(1080,ih))'" -x264opts bframes=3:cabac=1 -movflags faststart -c:a libfdk_aac -b:a 320k -ac 2 -y -s 640x480  "$new_filename" & wait $!;
        rm -f "$filename";
       
done
chown -R apache:apache $dir_kuda
