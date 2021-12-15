<?php
/*
Plugin Name: Linux Motion
Plugin URI: http://ya.ru
Description: Displaying Motion linux in Wordpress.
Version: 1.0.0
Author: port20031
Author URI: http://ya.ru
*/

register_uninstall_hook( __FILE__, array( 'port20031', 'port20031_uninstall' ));
class port20031
{
	function __construct ()
   {
		if (function_exists ('add_shortcode') )
      {
      	register_activation_hook( __FILE__, array( &$this, 'port20031_install' ));

      	add_action('plugins_loaded', array( &$this, 'port20031_init' ) );
      	add_shortcode('port20031_motion_linux', array (&$this, 'motion_linux_online') );
			add_shortcode('port20031_plex', array (&$this, 'plex_url') );
			add_shortcode('port20031_mediabrowser', array (&$this, 'mediabrowser_url') );
			add_shortcode('port20031_folder_video', array (&$this, 'folder_video') );
			add_shortcode('port20031_cron', array (&$this, 'cron_job') );
			add_shortcode('port20031_temperature', array (&$this, 'temperature') );
			add_shortcode('port20031_status_key', array (&$this, 'status_key') );
			add_action('admin_menu',  array (&$this, 'admin') );


      }
   }
	function status_key( $atts,$content = null )
	{
		extract(shortcode_atts(array(
      'id'=> '0',
      ), $atts));
      global $wpdb;
      if($content == null or $content==' '  )
		{
			$content =__( "The key " ,"port20031");
		}

      $id = (int) $id;
      $res='';
		$table_name = $wpdb->prefix."port20031_name1wire";
		$sql ="";
		$namekey=$content;
		$namekey1=__( "The key " ,"port20031");
		$namekeyon=__( " on ." ,"port20031");
		$namekeyoff=__( " off ." ,"port20031");
		if( $id==0 )
		{
			//показываем состояния всех ключей
			$sql ="SELECT id FROM ".$table_name." WHERE id_type=3  ;";
		}
		else
		{
			//показываем определенный ключ
			$sql ="SELECT id FROM ".$table_name." WHERE id_type='3' AND `id`=".$id." ;";

		}
		//echo $sql;
		$res=$wpdb->get_col($sql);
		//print_r($res);
		if( empty($res) )
		{
			//такого ключа нет
			return  "<font color=\"blue\">".$namekey." ".$id." ".__("is not set .","port20031")."</font>";
		}
		else
		{
			//есть,показываем статус ключей
			$html='';
			for ($i = 0; $i <= (count($res))-1; $i++)
  			{
    			//$graf3=$graf3.'[' .($dats[$i]*1000+7200000) . ',' .$dats1[$i] . '],';
    			$table_name1 = $wpdb->prefix."port20031_state_key";
    			$sql1 ="SELECT state_real FROM ".$table_name1." WHERE key_id=" .$res[$i] . "  ;";
    			//echo $sql1;
    			$stkey = $wpdb->get_var($sql1);
    			if(1==$stkey )
    			{
    				//key on
    				$tempd=$res[$i];
    				if( $namekey!=$namekey1 )
    				{
    					$tempd='';
    				}
    				$html=$html."<div><font color=\"green\">".$namekey." ".$tempd." "
    				.$namekeyon."</font></div>";
    			}
    			else
    			{
    				//key off
    				$tempd=$res[$i];
    				if( $namekey!=$namekey1 )
    				{
    					$tempd='';
    				}
    				$html=$html."<div><font color=\"red\">".$namekey." ".$tempd." "
    				.$namekeyoff."</font></div>";
    			}
  			}
			return $html;
		}
	}
   function temperature($atts,$content = null)
   {
		extract(shortcode_atts(array(
      'id'=> '0',
      'full'=> 'full',
      'color'=>'#e4062d',
      'date'=>'240',
      ), $atts));
      global $wpdb;
      $id = (int) $id;
		$port20031_portownet=get_option('port20031_portownet');
      //ищем имя по id и типу = 1
      $res='';
		$table_name = $wpdb->prefix."port20031_name1wire";
		$sql ="SELECT name1wire FROM ".$table_name." WHERE id_type='1' AND `id`=".$id." ;";
		$res=$wpdb->get_row($sql,ARRAY_N);
      if( $res== null  )
      {
      	return  __( "The temperature sensor " ,"port20031")." ".$id." ".__("is not set .","port20031");
      }
     	$res1=$res[0];
 		$ddd=date('d.m.Y H:i:s',current_time('timestamp',0));
      //add_option
      $port20031_pathownet="".get_option('port20031_pathownet')."";
      if (file_exists($port20031_pathownet))
		{
    		//echo "<br>Файл $port20031_pathownet существует<br>";
    		//require_once $port20031_pathownet;
    	}
    	else
    	{
    		//echo "<br>Файл $port20031_pathownet не существует<br>";
    		//include internal ownet.php
    		$port20031_pathownet= "ownet.php";
    		//require_once $port20031_pathownet;
		}
      require_once $port20031_pathownet;
		//update_option('port20031_pathownet1', $port20031_pathownet);
		//require_once "/opt/owfs/share/php/OWNet/ownet.php";
		unset($ow1);
		//echo "<br>tcp://localhost:".$port20031_portownet."<br>";
		$ow1=new OWNet("tcp://localhost:".$port20031_portownet."");
		$dir1= array() ;
		//echo '<br>ow1= '.$ow1.'<br>';
		$dir1 = $ow1->dir('/');
		$t= array() ;
		$tt= array() ;
		$ttt='';
		$temp1 =null;
		if(!is_array($dir1))
		{
			$dir1=array();
			array_push($dir1, "port20031", $_ = null);
		}
		foreach( $dir1 as $key =>$val )
		{
			if($key=='data')
			{
				$t=explode(',', $val );
			}
		}
		foreach( $t as $key =>$val )
		{
			//работа с датчиками температуры , сбор
			if( $val ==$res1 )
			{
				$i=0;
				while($temp1 ==null)
				{
					$temp1 = $ow1->read($res1."/temperature");
					$i=$i-1;
					if(-100==$i )
					{
						$temp1=$i;
					}
				}
			}
		}
		if( $temp1==null )
		{
			return __( "The temperature sensor " ,"port20031")." ".$id." ".__("is broken .","port20031");
		}
		$result=__("Temperature Sensor ","port20031")." ".
		$id." ".__(" in ","port20031")." ".$ddd." ".$temp1." <sup>o</sup>C";
		//если нет , выход и выводим - датчик температуры $id не настроен
      //если есть опрашиваем датчики и в них ищем свой
      //если нет , выход и выводим - датчик температуры $id поврежден
      //если есть , опрашиваем датчик , время ,выход с выводом - датчик температуры $id в такое_время  _градусов_
      //если  $full == 'full' , рисуем график по истории
      if($full!='full')
      {
      	return $result;
      }
      //SELECT `temp_val`,UNIX_TIMESTAMP(`temp_data`) FROM `wp_port20031_log_temp` WHERE `id_name`=1
		//готовим данные
		$table_name = $wpdb->prefix."port20031_log_temp";




      $min1=$wpdb->get_row("select temp_val FROM ".$table_name." WHERE `id_name`=".$id. "
      AND `temp_data` >= (now()- INTERVAL ".$date. " HOUR )
      ORDER BY temp_val ASC limit 1",ARRAY_N);
		//$min=$min1[0]*0.9;
		$max1=$wpdb->get_row("select temp_val FROM ".$table_name." WHERE `id_name`=".$id."
		AND `temp_data` >= (now()- INTERVAL ".$date. " HOUR )
		ORDER BY temp_val DESC limit 1",ARRAY_N);
 		//$max=$max1[0]*1.1;
		$sql ="SELECT UNIX_TIMESTAMP(`temp_data`),`temp_val` FROM ".$table_name." WHERE `id_name`=".$id."
		AND `temp_data` >= (now()- INTERVAL ".$date. " HOUR )
		ORDER BY `temp_data` ASC ;";
		$dats=$wpdb->get_col($sql,0);
		$dats1=$wpdb->get_col($sql,1);
		$graf3='';
		if( $dats )
		{
			for ($i = 0; $i <= (count($dats))-1; $i++)
  			{
    			$graf3=$graf3.'[' .($dats[$i]*1000+7200000) . ',' .$dats1[$i] . '],';
  			}
		}
		/*
		$kjhkjh= "<script type=\"text/javascript\" src=\"" .
		plugins_url( '/port20031_motion/flot/jquery.flot.js')."\"></script>";
		$kjhkjh1= "<script type=\"text/javascript\" src=\"" .
		plugins_url( '/port20031_motion/flot/jquery.flot.time.js')."\"></script>";
		$kjhkjh2= '
		<style type="text/css">
		.legend table {
			width: auto;
			border: 0px;
		}
		.legend tr {
			border: 0px;
		}
		.legend td {
			padding: 5px;
			font-size: 12px;
			border: 0px;
		}
		</style>';
		*/
		require_once('flot_ext.php');
		$height='400px';
		$width='100%';
		$points='false';
		$fill='false';
		$steps='false';
		$legend='true';
		$miny='null';
		$maxy='null';
		$minx='null';
		$maxx='null';
		static $number = 0;
		$number++;
		$ttyuuuuu=$ddd." ".$temp1."C";;
		$content = strip_tags($ttyuuuuu);
		$var_html=
		'<div id="plotarea'.$number.'" style="height: '.$height.'; width: '.$width.';">
		</div>
		<script type="text/javascript">
		jQuery(document).ready(function($)
		{
			var dataseries'.$number.' = [{
				label: "'.$content.'",
				color: "'.$color.'",
				data: ['.$graf3.']
			}];
			var options ={
				series:{
					points:{
						show: '.$points.',
						radius: 3
					},
					lines:{
						show: true,
						fill:'.$fill.',
						fillColor: { colors: [ { opacity: 0.6 }, { opacity: 0.3 } ] },
						steps: '.$steps.',
						lineWidth: 1
					},
				},
				legend:{
					show: '.$legend.',
					position: "nw",
					backgroundOpacity: 0.7
				},
				grid:{
					backgroundColor: null
				},
				yaxis:{
					min: '.$miny.',
					max: '.$maxy.'
				},
				xaxis:{
					mode: "time",
					timeformat: "%H:%M %d.%m",
      		   min: '.$minx.',
					max: '.$maxx.'
				},
			};
			var plotarea'.$number.' = $("#plotarea'.$number.'");
			var plot'.$number.' = $.plot( plotarea'.$number.' , dataseries'.$number.', options );
			});
			</script>
		';
		unset($ow1);
		return $var_html;

	}
    function port20031_sendmesagealert()
    {
    	global $wpdb;
    	//port20031_act_alarm,port20031_email_alarm,port20031_email_header,port20031_email_text
    	//$multiple_to_recipients = array($adr1,$adr2,$adr3 );


    	$table_name = $wpdb->prefix . "port20031_alarm";
		$sql ="SELECT `id`,`num_mess` FROM `".$table_name."` WHERE  `num_mess`<3 ORDER BY `id`";
		if($wpdb->query($sql) )
		{
			$r0=$wpdb->get_col( $sql, 0 );
			$r1=$wpdb->get_col( $sql, 1 );
			if( $r0 )
			{
				for ($i = 0; $i <= (count($r0))-1; $i++)
  				{
    				//+1 num_mess
    				$sql1="UPDATE `".$table_name."`  SET `num_mess`=".(int)($r1[$i]+1)." WHERE `id` =".$r0[$i].";";
    				//echo $sql1;
    				$wpdb->query($sql1);
    			}
			}
			//send
			$multiple_to_recipients =get_option('port20031_email_alarm');
    		$port20031_email_header =get_option('port20031_email_header');
    		$port20031_email_text   =get_option('port20031_email_text');
			wp_mail($multiple_to_recipients, $port20031_email_header, $port20031_email_text );
		}



    }
    function cron_job($atts,$content = null)
	{
		//$this->port20031_sendmesagealert('port20031@ya.ru','port20031@ya.ru','port20031@ya.ru','алерт');
		//дергаем крон
		global $wpdb;
		//chek temp alarm

		//$to = "port20031@ya.ru";
		//$subject = 'wp_mail function test';
		//$message = 'This is a test of the wp_mail function: wp_mail is working';
		//$headers = '';
		//$sent_message = wp_mail( $to, $subject, $message, $headers );
/*
		$headers = 'From: My Name <port20031@yandex.ru>' . "\r\n";
		wp_mail('port20031@yandex.ru', 'Тема', 'Содержание', $headers);
*/
		//на основе расписаний заносим состояние ключей
		//получаем список включения ключей с сортировкой по ключу и типу расписания
		//$table_name = $wpdb->prefix . "port20031_job";

		//тут логика включения ключей
		//require_once $port20031_pathownet;
		$port20031_pathownet="".get_option('port20031_pathownet')."";
      if (file_exists($port20031_pathownet))
		{
    		//echo "<br>Файл $port20031_pathownet существует<br>";
    		//require_once $port20031_pathownet;
    	}
    	else
    	{
    		//echo "<br>Файл $port20031_pathownet не существует<br>";
    		//include internal ownet.php
    		$port20031_pathownet= "ownet.php";
    		//require_once $port20031_pathownet;
		}
      //ini_set('memory_limit', '-1');
      require_once $port20031_pathownet;
		//update_option('port20031_pathownet2', $port20031_pathownet);
		//require_once "/opt/owfs/share/php/OWNet/ownet.php";
      //require_once "/opt/owfs/share/php/OWNet/ownet.php";
		unset($ow);
		$port20031_portownet=get_option('port20031_portownet');
		$ow=new OWNet("tcp://localhost:".$port20031_portownet."");
		$dir =array() ;
		$dir = $ow->dir('/');

		$t= array() ;
		$tt= array() ;
		$ttt='';
		$temp1 =null;
		if(!is_array($dir))
		{
			$dir=array();
			array_push($dir, "port20031", $_ = null);
		}
		foreach( $dir as $key =>$val )
		{
			if($key=='data')
			{
				$t=explode(',', $val );
			}
		}
		foreach( $t as $key =>$val )
		{
			//работа с датчиками температуры , сбор
			if( strrpos ($val,'28.') )
			{
				$temp1 =null;
				$i=0;
				while($temp1 ==null)
				{
					$temp1 = $ow->read($val."/temperature");
					$i=$i-1;
					if(-100==$i )
					{
						$temp1=$i;
					}
				}
				//ищем устройство в списке , если нет добавляем и определяем id

				$table_name = $wpdb->prefix . "port20031_name1wire";
				$sql ="SELECT id FROM ".$table_name." WHERE `id_type`=1 AND `name1wire`='".$val."' ;";
				$res=$wpdb->get_col($sql,0);
				$res1=$res[0];
				if($res1!=null )
				{
					//уже есть в списке
					$table_name = $wpdb->prefix . "port20031_log_temp";
					$sql1 ="INSERT INTO ".$table_name."( `id_name`,  `temp_val`) VALUES ('".$res1."','".$temp1."')";
					$wpdb->query($sql1);
					$temp1 =null;
				}
				else
				{
					//нет в списке заносим
					$table_name = $wpdb->prefix . "port20031_name1wire";
					$sql ="INSERT INTO ".$table_name."( `name1wire`, `id_type`) VALUES ('".$val."',1);";
					$wpdb->query($sql);
					//$sql ="SELECT id FROM ".$table_name." WHERE `id_type`=1 AND `name1wire`='".$val."' ;";
					//$res1=$wpdb->query($sql);
					//$table_name = $wpdb->prefix . "port20031_log_temp";
					//$sql ="INSERT INTO ".$table_name."( `id_name`,  `temp_val`) VALUES ('".$res1."','".$temp1."')";
					//$wpdb->query($sql);
					$temp1 =null;
				}
				$temp1 =null;

			}
			elseif( strrpos ($val,'3A.') )
			{

				

				
				//ищем устройство в списке , если нет добавляем и определяем id
				//устройства 2413 канал А ключ , канал В  статус устройства
				//канал А ключ;
				$table_name = $wpdb->prefix . "port20031_name1wire";
				$sql ="SELECT id FROM ".$table_name." WHERE `id_type`=2 AND `name1wire`='".$val."' ;";
				$res=$wpdb->get_col($sql,0);
				$res1=$res[0];
				if($res1!=null )
				{
					//уже есть в списке , выставляем уровень по таблице стате 
					//$table_name = $wpdb->prefix . "port20031_log_key";
					//$sql1 ="INSERT INTO ".$table_name."( `id_name`,  `temp_val`) VALUES ('".$res1."','".$temp1."')";
					//$wpdb->query($sql1);
					//$temp1 =null;
				}
				else
				{
					//нет в списке заносим
					$table_name = $wpdb->prefix . "port20031_name1wire";
					$sql ="INSERT INTO ".$table_name."( `name1wire`, `id_type`) VALUES ('".$val."',2);";
					$wpdb->query($sql);
				}
				//канал В ключ;канал В  статус устройства
				$table_name = $wpdb->prefix . "port20031_name1wire";
				$sql ="SELECT id FROM ".$table_name." WHERE `id_type`=3 AND `name1wire`='".$val."' ;";
				$res=$wpdb->get_col($sql,0);
				$res13=$res[0];
				if($res13!=null )
				{
					//уже есть в списке ,
					//расчет состояния по расписанию key_id=$res13
					$states=0;
					//read jobs for
$table_name = $wpdb->prefix . "port20031_job";
$sql="SELECT `schedulers`, `key_id`, `type_id`,  `temp_id`, `autostop_climate`, `time1`, `time2`, `job_data`,`id`
FROM `" . $table_name . "` WHERE `key_id`=".$res13."
ORDER BY `type_id`; ";
$res0= $wpdb->get_col($sql,0);
$res1= $wpdb->get_col($sql,1);
$res2=$wpdb->get_col($sql,2);
$res3= $wpdb->get_col($sql,3);
$res4=$wpdb->get_col($sql,4);
$res5= $wpdb->get_col($sql,5);
$res6=$wpdb->get_col($sql,6);
$res7= $wpdb->get_col($sql,7);
$res8= $wpdb->get_col($sql,8);
if( $res0 )
{
	for ($i = 0; $i <= (count($res0))-1; $i++)
  	{
    	//echo "".$res0[$i]."=>".$res1[$i]."=>".$res2[$i]."=>".$res3[$i]."=>".$res4[$i]."=>".$res5[$i]
    	//."=>".$res6[$i]."=>".$res7[$i]."=>".$res8[$i].'<br>';
    	if(0 < ($this->port20031_activday($res0[$i],$res7[$i],$res8[$i])))
    	{
    		//echo "<br>find job<br>";
    		$states=$states+($this->port20031_job ($res2[$i],$res3[$i],$res4[$i],$res5[$i],$res6[$i]));
    	}
    	else
    	{
    		$states=$states+0;
		}
   }
}
					//update program state for key
					if(0<$states  )
					{
						//заносим 1 в програм состояние датчика  WHERE `key_id`=".$res13."
						//UPDATE `wp_port20031_state_key` SET `state_program`=1 WHERE `key_id` =
						$table_name33 = $wpdb->prefix . "port20031_state_key";
						$sql33="UPDATE `".$table_name33."` SET `state_program`=1 WHERE `key_id` =".$res13." ";
						$wpdb->query($sql33);
					}
					else {
						//заносим 0 в програм состояние датчика  WHERE `key_id`=".$res13."
						$table_name33 = $wpdb->prefix . "port20031_state_key";
						$sql33="UPDATE `".$table_name33."` SET `state_program`=0 WHERE `key_id` =".$res13." ";
						$wpdb->query($sql33);
					}
					//расчет состояния по расписанию
					//set for program
					$table_name1 = $wpdb->prefix . "port20031_state_key";
					$sql22 ="SELECT `state_program` FROM `".$table_name1."` WHERE `key_id`=".$res13." ";
					$res22=$wpdb->get_col($sql22,0);
					$res12=$res22[0];
					if($res12 >0)
					{
						$res12=1;
					}
					$ow->set ($val."/PIO.A", $res12);
					//читаем статус  и заносим в базу
					$key11 =null;
					$key11 = $ow->read($val."/sensed.B");//$ow1->read ('/3A.E3420D00000018/sensed.B')
					if( $key11!=1)
					{
						$key11 = $ow->read($val."/sensed.B");
					}
					if( $key11!=1)
					{
						$key11 = 0;
					}
					$table_name1 = $wpdb->prefix . "port20031_state_key";
					$sql1 ="UPDATE `".$table_name1."` SET `state_real`=".$key11." WHERE `key_id`=".$res13."";
					//$sql1 ="INSERT INTO ".$table_name."( `id_name`,  `temp_val`) VALUES ('".$res1."','".$temp1."')";
					//echo $sql1;
					$wpdb->query($sql1);
					
				}
				else
				{
					//нет в списке заносим
					$table_name = $wpdb->prefix . "port20031_name1wire";
					$sql ="INSERT INTO ".$table_name."( `name1wire`, `id_type`) VALUES ('".$val."',3);";
					$wpdb->query($sql);
					//получаем номер ключа
					$table_name1 = $wpdb->prefix . "port20031_name1wire";
					$sql11 ="SELECT id FROM ".$table_name1." WHERE `id_type`=3 AND `name1wire`='".$val."' ;";
					$res11=$wpdb->get_col($sql11,0);
					$num111=$res11[0];
					$key11 =null;
					$key11 = $ow->read($val."/sensed.B");//$ow1->read ('/3A.E3420D00000018/sensed.B')
					if($key11!=1 )
					{
						$key11=0;
					}
					//заносим в таблицу состояния ключей
					$table_name2 = $wpdb->prefix . "port20031_state_key";
					$sql2 ="INSERT INTO ".$table_name2."( `key_id`, `state_program`, `state_real`)
					VALUES (".$num111.",0,".$key11.")";
					$wpdb->query($sql2);
				}
				//$key11 =null;
			}
			//тут логика включения ключей
			//1-read job
			//2-обрабатываем строки


			//тут логика включения ключей
			//чистка старого
			//1 day = 86400 sec
			$table_name = $wpdb->prefix . "port20031_log_temp";
			//$sql ="DELETE FROM ".$table_name." WHERE `temp_data` < (now()- INTERVAL 5 SECOND ) ;";
			$sql ="DELETE FROM ".$table_name." WHERE `temp_data` < (now()- INTERVAL 10 DAY);";
			$wpdb->query($sql);
			//delete bad temperatyre
			$table_name = $wpdb->prefix . "port20031_log_temp";
			$sql ="DELETE FROM ".$table_name." WHERE `temp_val`=-100;";
			$wpdb->query($sql);
			//чистка старого
			//работа с датчиками температуры , сбор
		}
		$z=get_option('port20031_sensortempalarm');
		if( 1000!=$z )
		{
			//chek alert
			//name temp sensor
			$table_name = $wpdb->prefix . "port20031_name1wire";
			$query = "
			SELECT `name1wire` FROM `".$table_name."` WHERE `id`= ".$z."
			";
			$cta=$wpdb->get_col($query, 0);
			$ctaname=$cta[0];
			if( $ctaname!=NULL )
			{
				//
				$temp1 =null;
				$i=900;
				while($temp1 ==null)
				{
					$temp1 = $ow->read($ctaname."/temperature");
					$i=$i+1;
					if(1000==$i )
					{
						$temp1=$i;
					}
				}
				if($temp1>get_option('port20031_alarmtempownet' ) )
				{
					//send alarm
					$table_name44 = $wpdb->prefix . "port20031_alarm";
					$sql44="INSERT INTO `".$table_name44."`( `type_id`) VALUES (1)";
					//echo '<br>'.$sql44.'<br>';
					$wpdb->query($sql44);
				}
			}
		}
		//send alarm update_option('port20031_act_alarm', "checked");
		if( get_option('port20031_act_alarm')=="checked")
		{
			$this->port20031_sendmesagealert();
		}
		unset($ow);
		return ;
	}
   function  port20031_gettimer($time1,$time2)
   {
		$dh=date('H',current_time('timestamp',0));//date('d.m.Y H:i:s',current_time('timestamp',0));
		$dm=date('i');
		$tnow=(int)($dh.$dm);
		//echo '<br>'.$tnow.' rrrrrrrrrrrrrrrrrr<br>';
		if( $tnow<$time1)
		{
			return 0;
		}
		elseif($tnow>=$time2)
		{
			return 0;
		}
		else {
			return 1 ;
		}
   }
   function  port20031_gettemp($num_temp,$climat)
   {
   	global $wpdb;
		//read name temp datchik SELECT `name1wire` FROM `wp_port20031_name1wire` WHERE `id` =
		$table_name = $wpdb->prefix . "port20031_name1wire";
		$sql="SELECT `name1wire` FROM `".$table_name."` WHERE `id` =".$num_temp."";
		$res=$wpdb->get_col($sql,0);
		$val=$res[0];
		$port20031_pathownet="".get_option('port20031_pathownet')."";
      if (file_exists($port20031_pathownet))
		{
    		//echo "<br>Файл $port20031_pathownet существует<br>";
    		//require_once $port20031_pathownet;
    	}
    	else
    	{
    		$port20031_pathownet= "ownet.php";
    	}
      require_once $port20031_pathownet;
		//update_option('port20031_pathownet2', $port20031_pathownet);
		//require_once "/opt/owfs/share/php/OWNet/ownet.php";
      //require_once "/opt/owfs/share/php/OWNet/ownet.php";
		unset($ow);
		$port20031_portownet=get_option('port20031_portownet');
		$ow=new OWNet("tcp://localhost:".$port20031_portownet."");
		$temp1 =null;
		$i=900;
		while($temp1 ==null)
		{
			$temp1 = $ow->read($val."/temperature");
			$i=$i+1;
			if(1000==$i )
			{
				$temp1=$i;
			}
		}
		unset($ow);
		if( $temp1 > ($climat+1) )
		{
   	 	return 0 ;
   	}
   	elseif( $temp1 < ($climat) )
   	{
   		return 1 ;
   	}
   	else {
   		return 0;
   	}
   }
   function  port20031_activday($shed,$date,$num)
   {
   	global $wpdb;
   	if( 10000000< $shed)
   	{
   		//shedule
   		$dn=date('N');
   		if(1<=substr($shed, $dn, 1))
   		{
   			return 1;
   		}
   		else {
   			return 0;
   		}
   	}
   	else
   	{
   		//echo "<br>1day<br>";
   		$dnow=date('N');
   		$ddate=date('N',strtotime($date));
   		//echo "<br>".$dnow."daaaaaaaaaaaaay".$ddate."<br>";
   		if($dnow==$ddate)
   		{
   			//echo "<br>1dayyyyyyyyyyyyyyyyyy<br>";
   			return 1;
   		}
   		else {
   			//del num job
   			$table_name = $wpdb->prefix . "port20031_job";
   			$sql = "DELETE FROM `" . $table_name . "` WHERE `id`=".$num."";
   			$wpdb->query($sql);
   			//echo "<br>1dayeeeeeeeeeeeend<br>";
   			return 0 ;
   		}
   	}
   	//return 0 ;
   }
   function  port20031_job ($tj,$it,$cl,$t1,$t2)
   {
   	if( $tj==1 )
   	{
   		//on
			return 1;
   	}
   	elseif($tj==2 )
   	{
			//autostop
			$t2m=($cl+($t1%100))%60;
			$t2h1=floor($t1/100);
			$t2h2=floor(($cl+($t1%100))/60);
			$t2=100*($t2h1+$t2h2)+$t2m;
			//echo "<br>".$t1."-".$t2."<br>";
			if(0<($this->port20031_gettimer($t1,$t2)) )
			{
				//echo '<br>'.$this->port20031_gettimer($t1,$t2).' fffffffffffffffffff<br>';
				return 1;
			}
			else {
				//echo '<br>'.$this->port20031_gettimer($t1,$t2).' ooooooooooooooooooof<br>';
				return 0;
			}
   	}
   	elseif($tj==3 )
   	{
			//climate
			$tempdat=$this->port20031_gettemp($it,$cl);
			if(0==$tempdat )
			{
				return 0;
			}
			else
			{
				return 1;
			}
   	}
   	elseif($tj==4 )
   	{
			//timer
			if(0<($this->port20031_gettimer($t1,$t2)) )
			{
				//echo '<br>'.$this->port20031_gettimer($t1,$t2).' fffffffffffffffffff<br>';
				return 1;
			}
			else {
				//echo '<br>'.$this->port20031_gettimer($t1,$t2).' ooooooooooooooooooof<br>';
				return 0;
			}
   	}
   	elseif($tj==5 )
   	{
			//combi
			if(0<($this->port20031_gettimer($t1,$t2)) )
			{
				//echo '<br>'.$this->port20031_gettimer($t1,$t2).' fffffffffffffffffff<br>';
				$tempdat=$this->port20031_gettemp($it,$cl);
				if(0==$tempdat )
				{
					return 0;
				}
				else
				{
					return 1;
				}
			}
			else {
				//echo '<br>'.$this->port20031_gettimer($t1,$t2).' ooooooooooooooooooof<br>';
				return 0;
			}
   	}
   	else
   	{
   		return 0 ;
   	}
	}
   function  port20031_install()
   {
   	//что делаем при установке плагина
   	/*
   	1.Таблица типов устройств type1wire (id ,type_name)(0-температура ,1- ключ ,2- статус )
   	2.Таблица списка устройств name1wire (id ,name,id_type)
   	3.Таблица истории датчиков temperature1wire(id ,id_name ,time ,val_temp )
   	*/

   	global $wpdb;
   	$table_name = $wpdb->prefix . "port20031_type1wire";
   	//$charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";
   	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
   	{
   		//create table
   		$sql = "CREATE TABLE " . $table_name . "
   		( 	id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
   			type_name text NOT NULL,
   			UNIQUE KEY id (id)
   		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;";
   		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   		dbDelta($sql);
   		$sql = "INSERT INTO `" . $table_name . "` (`id`, `type_name`) VALUES
			(1, 'temperature'),(2, 'key'),(3, 'status');";
			$wpdb->query($sql);
		}
   	else
   	{
   		//уже есть такая таблица !!!!!
   		//echo _e('Already have such a table !!!!!', 'port20031');
   	}
		$table_name = $wpdb->prefix . "port20031_name1wire";
   	//$charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";
   	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
   	{
   		//create table
   		$sql1 = "CREATE TABLE " . $table_name . "
   		( 	id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
   			name1wire text NOT NULL,
   			id_type bigint(20) NOT NULL,
   			UNIQUE KEY id (id)
   		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;";
   		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   		dbDelta($sql1);

		}
   	else
   	{
   		//уже есть такая таблица !!!!!
   		//echo _e('Already have such a table !!!!!', 'port20031');
   	}
   	$table_name = $wpdb->prefix . "port20031_log_temp";
   	//$charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";
   	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
   	{
   		//create table
   		$sql2 = "CREATE TABLE " . $table_name . "
   		( 	id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
   			id_name bigint(20) NOT NULL,
   			temp_data TIMESTAMP NOT NULL DEFAULT now(),
   			temp_val DECIMAL(9,2) NOT NULL DEFAULT '0',
   			UNIQUE KEY id (id)
   		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;";
   		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   		dbDelta($sql2);

		}
   	else
   	{
   		//уже есть такая таблица !!!!!
   		//echo _e('Already have such a table !!!!!', 'port20031');
   	}
		$table_name = $wpdb->prefix . "port20031_type_job";
   	//$charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";
   	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
   	{
   		//create table
   		$sql3 = "CREATE TABLE " . $table_name . "
   		( 	id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
   			type_job text NOT NULL,
   			UNIQUE KEY id (id)
   		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;";
   		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   		dbDelta($sql3);
   		$sql3 = "INSERT INTO `" . $table_name . "` (`id`, `type_job`) VALUES
			(1, 'on-off'),(2, 'autostop'),(3, 'climate'),(4, 'timer'),(5, 'climate+timer');";
			$wpdb->query($sql3);

		}
   	else
   	{
   		//уже есть такая таблица !!!!!
   		//echo _e('Already have such a table !!!!!', 'port20031');
   	}
   	$table_name = $wpdb->prefix . "port20031_state_key";
   	//$charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";
   	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
   	{
   		//create table
   		$sql4 = "CREATE TABLE " . $table_name . "
   		( 	id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
   			key_id bigint(20) NOT NULL,
   			state_program bigint(20) NOT NULL  DEFAULT '0',
   			state_real bigint(20) NOT NULL  DEFAULT '0',
   			UNIQUE KEY id (id)
   		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;";
   		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   		dbDelta($sql4);
   	}
   	else
   	{
   		//уже есть такая таблица !!!!!
   		//echo _e('Already have such a table !!!!!', 'port20031');
   	}
   	$table_name = $wpdb->prefix . "port20031_job";
   	//$charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";
   	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
   	{
   		//create table
   		$sql5 = "CREATE TABLE " . $table_name . "
   		( 	id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
   			type_id bigint(20) NOT NULL,
   			key_id bigint(20) NOT NULL,
   			schedulers bigint(20) NOT NULL  DEFAULT '0',
   			temp_id bigint(20) NOT NULL  DEFAULT '0',
   			autostop_climate bigint(20) NOT NULL  DEFAULT '20',
   			time1 bigint(20) NOT NULL  DEFAULT '0',
   			time2 bigint(20) NOT NULL  DEFAULT '0',
   			job_data TIMESTAMP NOT NULL DEFAULT now(),
   			UNIQUE KEY id (id)
   		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;";
   		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   		dbDelta($sql5);
   	}
   	else
   	{
   		//уже есть такая таблица !!!!!
   		//echo _e('Already have such a table !!!!!', 'port20031');
   	}
   	$table_name = $wpdb->prefix . "port20031_alarm";
   	//$charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";
   	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
   	{
   		//create table
   		$sql5 = "CREATE TABLE " . $table_name . "
   		( 	id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
   			type_id bigint(20) NOT NULL,
   			num_mess bigint(20) NOT NULL  DEFAULT '0',
   			alarm_data TIMESTAMP NOT NULL DEFAULT now(),
   			UNIQUE KEY id (id)
   		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;";
   		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   		dbDelta($sql5);
   	}
   	else
   	{
   		//уже есть такая таблица !!!!!
   		//echo _e('Already have such a table !!!!!', 'port20031');
   	}
   	
	}
   public static function  port20031_uninstall()
   {
   	//что делаем при удалении плагина DROP TABLE
		global $wpdb;
		$name_table=  $wpdb->prefix . "port20031_type1wire" ;
   	$sql = "DROP TABLE $name_table";
    	$wpdb->query($sql);
		$name_table=  $wpdb->prefix . "port20031_name1wire" ;
   	$sql = "DROP TABLE $name_table";
    	$wpdb->query($sql);
    	$name_table=  $wpdb->prefix . "port20031_log_temp" ;
   	$sql = "DROP TABLE $name_table";
    	$wpdb->query($sql);
    	$name_table=  $wpdb->prefix . "port20031_type_job" ;
   	$sql = "DROP TABLE $name_table";
    	$wpdb->query($sql);
    	$name_table=  $wpdb->prefix . "port20031_state_key" ;
   	$sql = "DROP TABLE $name_table";
    	$wpdb->query($sql);
    	$name_table=  $wpdb->prefix . "port20031_job" ;
   	$sql = "DROP TABLE $name_table";
    	$wpdb->query($sql);
    	$name_table=  $wpdb->prefix . "port20031_alarm" ;
   	$sql = "DROP TABLE $name_table";
    	$wpdb->query($sql);
    	delete_option('port20031_pathownet');
		delete_option('port20031_act_alarm');
		delete_option('port20031_email_alarm');
		delete_option('port20031_email_header');
		delete_option('port20031_email_text');
		delete_option('port20031_portownet');
		delete_option('port20031_alarmtempownet');
		delete_option('port20031_sensortempalarm');
		
   }
   function port20031_init()
   {
		load_plugin_textdomain( 'port20031', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		add_option('port20031_pathownet', '/usr/share/php/OWNet/ownet.php');
    	add_option('port20031_portownet', '3000');
	}

   function my_acf_admin_notice1() {
    ?>

<div class="updated notice is-dismissible">
    <strong><p><?php _e( 'Job is  created.', 'port20031' ); ?></p></strong>
</div>

    <?php
}
   function my_acf_admin_notice() {
    ?>
    <div class="notice error  is-dismissible" >
        <strong><p><?php _e( 'Job is not created.', 'port20031' ); ?></p></strong>
    </div>


    <?php
}

   function admin_form()
   {
		global $wpdb;
		$port20031_pathownet=get_option('port20031_pathownet');
    	if( isset($_GET['del_job']) )
    	{
    		//echo 'del job<br>';
    		$kk3=(int)$_GET['del_job'];
    		$table_name = $wpdb->prefix . "port20031_job";
    		$sql="DELETE FROM `" . $table_name . "` WHERE `id`=$kk3";
    		$wpdb->query($sql);
    		$this->cron_job(1);
    	}
    	if ( isset($_GET['keyon']) or isset($_GET['keyoff']) )
    	{
    		//echo 'turn button<br>';
    		//del shedule + on or off
    		$table_name = $wpdb->prefix . "port20031_job";
    		$sql="DELETE FROM `" . $table_name . "` WHERE `id`>0";
    		$wpdb->query($sql);
    		if( isset($_GET['keyon']) )
    		{
    			//echo 'turn button on<br>';
    			$table_name = $wpdb->prefix . "port20031_state_key";
    			$sql="UPDATE `wp_port20031_state_key` SET `state_program`=1 WHERE `state_program`=0";
    			$wpdb->query($sql);
    		}
    		else {
    			//echo 'turn button off<br>';
    			$table_name = $wpdb->prefix . "port20031_state_key";
    			$sql="UPDATE `wp_port20031_state_key` SET `state_program`=0 WHERE `state_program`=1";
    			$wpdb->query($sql);
    		}
    		$this->cron_job(1);

    	}

    	if ( isset($_POST['submit']) )
    	{
       	if ( function_exists('current_user_can') && !current_user_can('manage_options') )
       	{
       		die ( _e('Hacker?', 'port20031') );
       	}
         if (function_exists ('check_admin_referer') )
        	{
            check_admin_referer('port20031_form');
        	}
			//port20031_alarmtempownet,port20031_sensortempalarm
			$port20031_alarmtempownet=$_POST['port20031_alarmtempownet'];
			update_option('port20031_alarmtempownet', $port20031_alarmtempownet);
			$port20031_sensortempalarm=$_POST['port20031_sensortempalarm'];
			update_option('port20031_sensortempalarm', $port20031_sensortempalarm);
			$port20031_portownet=$_POST['port20031_portownet'];
			update_option('port20031_portownet', $port20031_portownet);
			$port20031_pathownet=$_POST['port20031_pathownet'];
			update_option('port20031_pathownet', $port20031_pathownet);
			//port20031_act_alarm,port20031_email_alarm,port20031_email_header,port20031_email_text
			$port20031_email_alarm=$_POST['port20031_email_alarm'];
//chek	port20031_email_alarm
			if(filter_var($port20031_email_alarm, FILTER_VALIDATE_EMAIL))
			{
    			//echo $email.'<br>';
    			update_option('port20031_email_alarm', $port20031_email_alarm);
			}

			$port20031_email_header=htmlentities($_POST['port20031_email_header']);
			update_option('port20031_email_header', $port20031_email_header);
			$port20031_email_text=htmlentities($_POST['port20031_email_text']);
			update_option('port20031_email_text', $port20031_email_text);
//chek port20031_act_alarm		$paachek
			if(isset($_POST['port20031_act_alarm']))
			{
				$port20031_act_alarm=(int)($_POST['port20031_act_alarm']);
				update_option('port20031_act_alarm', "checked");
			}
			else {
				update_option('port20031_act_alarm', "");
			}

			//если создается расписание
			if( isset($_POST['port20031_greatjob']) )
			{
				//echo '<br>port20031_greatjob<br>';
				//chek input port20031_schedule,port20031_key,port20031_typejob,
				//port20031_sensortemp,
				//port20031_climat,port20031_time1h,port20031_time1m,port20031_time2h,port20031_time2m

				$port20031_schedule  =(int)$_POST['port20031_schedule'];
				$port20031_key       =(int)$_POST['port20031_key'];
				$port20031_typejob   =(int)$_POST['port20031_typejob'];
				$port20031_sensortemp=(int)$_POST['port20031_sensortemp'];
				$port20031_climat    =(int)$_POST['port20031_climat'];//port20031_climat,
				$port20031_time1h    =(int)$_POST['port20031_time1h'];//port20031_time1h,
				$port20031_time1m    =(int)$_POST['port20031_time1m'];//port20031_time1m,
				$port20031_time2h    =(int)$_POST['port20031_time2h'];//port20031_time2h,
				$port20031_time2m    =(int)$_POST['port20031_time2m'];//port20031_time2m
				$port20031_time1=(int)(100*$port20031_time1h+$port20031_time1m);
				$port20031_time2=(int)(100*$port20031_time2h+$port20031_time2m);
				//echo '<br>'.$port20031_time1.'-'.$port20031_time2.'<br>';
				if(1000!=$port20031_key )
				{
					if(1000!=$port20031_typejob )
					{
						if( 1000!=$port20031_sensortemp )
						{
							if( $port20031_time2>=$port20031_time1)
							{
								//echo '<br>port20031_greatjob<br>';
								//INSERT INTO `wp_port20031_job`( `type_id`, `key_id`, `schedulers`,
								// `temp_id`, `autostop_climate`, `time1`, `time2`)
								//VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6],[value-7])
							 	$table_name = $wpdb->prefix . "port20031_job";
								$sql = "INSERT INTO `" . $table_name . "` ( `type_id`, `key_id`, `schedulers`,
								 `temp_id`, `autostop_climate`, `time1`, `time2`)  VALUES
								(".$port20031_typejob.",".$port20031_key.",".$port20031_schedule.",".$port20031_sensortemp.
								",".$port20031_climat.",".$port20031_time1.",".$port20031_time2.");";
								//echo '<br>'.$sql.'<br>';
								$wpdb->query($sql);
								$this->my_acf_admin_notice1();
							}
							else
							{
								$this->my_acf_admin_notice();
							}
						}
						else
						{
							$this->my_acf_admin_notice();
						}
					}
					else
					{
						$this->my_acf_admin_notice();
					}
				}
				else
				{
					$this->my_acf_admin_notice();
				}

			}
			else
			{
				$this->my_acf_admin_notice();
			}
			//надо дернуть расписание
			$this->cron_job(1);


			
    	}

/*
echo "<BR>";
echo 'POST';
echo "<BR>";
echo "<pre>";
print_r($_POST);
echo "</pre>";
echo "<BR><BR>";
echo "<BR><BR>";
echo '_GET';
echo "<BR>";
echo "<pre>";
print_r($_GET);
echo "</pre>";
echo "<BR><BR>";

*/
    	//получаем список ключей

						
		?>


		<div class='wrap'>
			<h2><?php _e('Motion 1Wire WP Options', 'port20031'); ?></h2>

<form name="port20031" method="post"
action="<?php echo $_SERVER['PHP_SELF']; ?>?page=port20031_linux_motion.php&amp;updated=true">
			<!-- Имя port20031_form используется в check_admin_referer -->
			<?php
			if (function_exists ('wp_nonce_field') )
			{
				wp_nonce_field('port20031_form');
			}
			?>
<p class="submit">
			<input type="submit" name="submit" value="<?php _e('Save Changes','port20031') ?>" />
			</p>
			
			<table >
			<tr align="left">
			<td>
  						<th scope="row"><?php _e('Greate job : ', 'port20031'); ?> 
			<input type="checkbox" name="port20031_greatjob" value="1"  ></th>

					</td>
			</tr>	
			<tr align="center">


					<td>
  						<?php _e('Schedule :', 'port20031'); ?>&nbsp;<br>
						<input type="text" name="port20031_schedule" size="7" value="11111111" />

					</td>

					<td>
						<?php _e('Кеу :', 'port20031'); ?><br>
						<select name="port20031_key"   size="1">
						<option value="1000" selected ><?php _e('We must choose ...', 'port20031'); ?></option>
  						<?php
						//получаем список ключей
						$table_name = $wpdb->prefix . "port20031_name1wire";
						$sql ="SELECT id , name1wire FROM ".$table_name." WHERE `id_type`=3  ORDER BY id ;";
						$dats=$wpdb->get_col($sql,0);
						$dats1=$wpdb->get_col($sql,1);
						if( $dats )
						{
							for ($i = 0; $i <= (count($dats))-1; $i++)
  							{
    								echo "<option value=".$dats[$i]." >".$dats1[$i]."</option>";
    						}
						}
						?>
  						</select>

  					</td>
  					<td>
  						<?php _e(' Type job : ', 'port20031'); ?>&nbsp;<br>
						<select name="port20031_typejob"   size="1">
						<option value="1000" selected="" ><?php _e('We must choose ...', 'port20031'); ?></option>
  						<?php
						//получаем список режимов
						$table_name = $wpdb->prefix . "port20031_type_job";
						$sql ="SELECT id , type_job FROM ".$table_name."   ORDER BY id ;";
						$dats=$wpdb->get_col($sql,0);
						$dats1=$wpdb->get_col($sql,1);
						if( $dats )
						{
							for ($i = 0; $i <= (count($dats))-1; $i++)
  							{
    								echo "<option value=".$dats[$i]." >".$dats1[$i]."</option>";
    								
  							}
						}
						?>
  						</select>
					</td>
					<td>
  						<?php _e(' Temperature sensor : ', 'port20031'); ?>&nbsp;<br>
						<select name="port20031_sensortemp"   size="1">
						<option value="1000" selected="" ><?php _e('We must choose ...', 'port20031'); ?></option>
  						<?php
						//получаем список режимов
						$table_name = $wpdb->prefix . "port20031_name1wire";
						$sql ="SELECT id , name1wire FROM ".$table_name." WHERE `id_type`=1  ORDER BY id ;";
						$dats=$wpdb->get_col($sql,0);
						$dats1=$wpdb->get_col($sql,1);
						if( $dats )
						{
							for ($i = 0; $i <= (count($dats))-1; $i++)
  							{
    								echo "<option value=".$dats[$i]." >".$dats1[$i]."</option>";
    								//echo $dats[$i].'-'.$dats1[$i].'<br>';
    								//$graf3=$graf3.'[' .($dats[$i]*1000+7200000) . ',' .$dats1[$i] . '],';
  							}
						}

						?>
  						</select>
					</td>
					<td>
  						<?php _e(' Climat :', 'port20031'); ?>&nbsp;<br>
						<input type="text" name="port20031_climat" size="3" value="20" />

					</td>
					<td>
  						<?php _e(' Time 1:', 'port20031'); ?>&nbsp;<br>
						<select size="1" name ="port20031_time1h">
						<option value="00" selected="">00</option>
						<?php
						$h=array('01','02','03','04','05','06','07','08','09','10','11',
							'12','13','14','15','16','17','18','19','20','21','22','23');
						for($i = 0; $i <= 22; $i=$i+1)
						{
    						echo "<option value=".$h[$i]." >".$h[$i]."</option>";
						}
						?>
						</select>&nbsp;:
						<select size="1" name ="port20031_time1m">
						<option value="00" selected="">00</option>
						<?php
						$m=array('05','10','15','20','25','30','35','40','45','50','55');
						for($i = 0; $i <= 10; $i=$i+1)
						{
    						echo "<option value=".$m[$i]." >".$m[$i]."</option>";
						}
						?>
						</select>

					</td>
					<td>
  						<?php _e(' Time 2:', 'port20031'); ?>&nbsp;<br>
						<select size="1" name ="port20031_time2h">
						<option value="00" selected="">00</option>
						<?php
						$h=array('01','02','03','04','05','06','07','08','09','10','11',
							'12','13','14','15','16','17','18','19','20','21','22','23');
						for($i = 0; $i <= 22; $i=$i+1)
						{
    						echo "<option value=".$h[$i]." >".$h[$i]."</option>";
						}
						?>
						</select>&nbsp;:
						<select size="1" name ="port20031_time2m">
						<option value="00" selected="">00</option>
						<?php
						$m=array('05','10','15','20','25','30','35','40','45','50','55');
						for($i = 0; $i <= 10; $i=$i+1)
						{
    						echo "<option value=".$m[$i]." >".$m[$i]."</option>";
						}
						?>
						</select>
					</td>
			</tr>
			</table>
			<table class="form-table">
			<tr valign="top">
			<th scope="row"><?php _e('Activation of protection : ', 'port20031'); ?>
			<td><input type="checkbox" name="port20031_act_alarm"
			value="1" <?php echo get_option('port20031_act_alarm'); ?> ></td>
			</th>
			<tr valign="top">
				<th scope="row"><?php _e('Path ownet.php:', 'port20031'); ?></th>
					<td>
						<input type="text" name="port20031_pathownet" size="80"
						value="<?php echo get_option('port20031_pathownet'); ?>" />
					</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Port ownet :', 'port20031'); ?></th>
					<td>
						<input type="text" name="port20031_portownet" size="80"
						value="<?php echo get_option('port20031_portownet'); ?>" />
					</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Temperature alarm sensor :', 'port20031'); ?></th>
				<td>
  						<?php


  						echo '<select name="port20031_sensortempalarm"   size="1">

  						';
  						$zzz=get_option('port20031_sensortempalarm');
  						$ttttt='';
  						if(1000==get_option('port20031_sensortempalarm'))
  						{
  							$ttttt='selected=""';
  						}
  						echo '<option value="1000" '.$ttttt.' >'; _e('You can choose...', 'port20031'); echo '</option>';
  						$table_name = $wpdb->prefix . "port20031_name1wire";
						$sql ="SELECT id , name1wire FROM ".$table_name." WHERE `id_type`=1  ORDER BY id ;";
						$dats=$wpdb->get_col($sql,0);
						$dats1=$wpdb->get_col($sql,1);

						if( $dats )
						{
							for ($i = 0; $i <= (count($dats))-1; $i++)
  							{
    								$zz='';
    								if($dats[$i]==$zzz)
    								{
    									$zz='selected=""';
    								}

    								echo "<option value=".$dats[$i]." ".$zz." >".$dats1[$i]."</option>";
    								//echo $dats[$i].'-'.$dats1[$i].'<br>';
    								//$graf3=$graf3.'[' .($dats[$i]*1000+7200000) . ',' .$dats1[$i] . '],';
  							}
						}

  						?>
  						</select>
  						<input type="text" name="port20031_alarmtempownet" size="20"
						value="<?php echo get_option('port20031_alarmtempownet'); ?>" />
				</td>

			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Email notification :', 'port20031'); ?></th>
					<td>
						<input type="text" name="port20031_email_alarm" size="80"
						value="<?php echo get_option('port20031_email_alarm'); ?>" />
					</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Email header :', 'port20031'); ?></th>
					<td>
						<input type="text" name="port20031_email_header" size="80"
						value="<?php echo get_option('port20031_email_header'); ?>" />
					</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Text of the letter :', 'port20031'); ?></th>
					<td>
						<input type="text" name="port20031_email_text" size="80"
						value="<?php echo get_option('port20031_email_text'); ?>" />
					</td>
			</tr>
			
			</table>
			</tr>
			<input type="hidden" name="action" value="update" />
			<input type="hidden" name="page_options"
			value="
			port20031_pathownet,port20031_portownet,
			port20031_alarmtempownet,port20031_sensortempalarm,
			port20031_act_alarm,port20031_email_alarm,port20031_email_header,port20031_email_text,
			port20031_greatjob,port20031_schedule,port20031_key,port20031_typejob,
			port20031_sensortemp,
			port20031_climat,port20031_time1h,port20031_time1m,port20031_time2h,port20031_time2m" />
			<p class="submit">
			<input type="submit" name="submit" value="<?php _e('Save Changes','port20031') ?>" />
			</p>
			</form>
		</div>
<br>

<?php _e('Attention ! The button apply all keys and delete all schedules ( for test or by crash ).', 'port20031'); ?>



<form action="./options-general.php">
<input type="hidden" name="page" value="port20031_linux_motion" />
<input type="hidden" name="keyoff"   value="off" />
<button type="submit"><?php _e('Turn off everything', 'port20031'); ?></button>
</form>

<br>
	<?php

echo _e('List of schedules.', 'port20031').'<br>';
$table_name = $wpdb->prefix . "port20031_job";
$sql="SELECT `schedulers`, `key_id`, `type_id`,  `temp_id`, `autostop_climate`, `time1`, `time2`, `job_data`,`id`
FROM `" . $table_name . "`
ORDER BY `key_id`,`type_id`; ";
$res0= $wpdb->get_col($sql,0);
$res1= $wpdb->get_col($sql,1);
$res2=$wpdb->get_col($sql,2);
$res3= $wpdb->get_col($sql,3);
$res4=$wpdb->get_col($sql,4);
$res5= $wpdb->get_col($sql,5);
$res6=$wpdb->get_col($sql,6);
$res7= $wpdb->get_col($sql,7);
$res8= $wpdb->get_col($sql,8);
if( $res0 )
{
	echo "
	<table>

	";
	for ($i = 0; $i <= (count($res0))-1; $i++)
  	{
    	$table_name11 = $wpdb->prefix . "port20031_type_job";
    	$sql12="SELECT `type_job` FROM `" . $table_name11 . "` WHERE `id`=$res2[$i] ";
    	$kk1='
    	<form action="./options-general.php">
<input type="hidden" name="page" value="port20031_linux_motion" />
<input type="hidden" name="del_job"   value="'.$res8[$i].'" />

<button type="submit"  >'. __('Delete job','port20031') .'</button>
</form>
    	';
    	$kk=$wpdb->get_var($sql12);
    	echo "<tr><td>";
		echo " ".$res0[$i]." => key ".$res1[$i]."=>".$kk."=> sensor temp ".$res3[$i]." => ".$res4[$i]." => ".$res5[$i]
    	." => ".$res6[$i]." => ".$res7[$i]."</td><td>".$kk1."</td></tr>
    	";

   }
   echo "</table>
	";
}
echo _e('Status keys(id key=>program state=>real state):', 'port20031').'<br>';
$table_name = $wpdb->prefix . "port20031_state_key";
$sql="SELECT `key_id`, `state_program`, `state_real` FROM `" . $table_name . "` ORDER BY `key_id`";
//SELECT `key_id`, `state_program`, `state_real` FROM `wp_port20031_state_key` ORDER BY `key_id`
$res0= $wpdb->get_col($sql,0);
$res1= $wpdb->get_col($sql,1);
$res2=$wpdb->get_col($sql,2);
//$res3= $wpdb->get_col($sql,3);
if( $res0 )
{
	for ($i = 0; $i <= (count($res0))-1; $i++)
  	{
    	echo "".$res0[$i]."=>".$res1[$i]."=>".$res2[$i].'<br>';
   }
}
//Types of devices
echo _e('Types of devices (id-types of devices):', 'port20031').'<br>';
$table_name = $wpdb->prefix . "port20031_type1wire";
$sql="SELECT `id`, `type_name` FROM `" . $table_name . "` ORDER BY `id`";
$res0= $wpdb->get_col($sql,0);
$res1= $wpdb->get_col($sql,1);
if( $res0 )
{
	for ($i = 0; $i <= (count($res0))-1; $i++)
  	{
    	echo "".$res0[$i]."=>".$res1[$i].'<br>';
   }
}

echo _e('Description devices(id key=>name key=>id types of devices):', 'port20031').'<br>';
$table_name = $wpdb->prefix . "port20031_name1wire";
$sql="SELECT `id`, `name1wire`, `id_type` FROM `" . $table_name . "` ORDER BY `id`";
$res0= $wpdb->get_col($sql,0);
$res1= $wpdb->get_col($sql,1);
$res2=$wpdb->get_col($sql,2);
if( $res0 )
{
	for ($i = 0; $i <= (count($res0))-1; $i++)
  	{
    	echo "".$res0[$i]."=>".$res1[$i]."=>".$res2[$i].'<br>';
   }
}


echo _e('
Attention ! To create a schedule, you must be sure to:<br>
- Select the checkbox to create a schedule;<br>
- Complete the timetable (8-digit number - so it  will always be executed;<br>
&nbsp;&nbsp; 7 and less digit -number  - it will be executed once; <br>
&nbsp;&nbsp; if the number is greater than 0 - is performed on the corresponding day of the week <br>
&nbsp;&nbsp; (for example - 11100010 every Monday, Tuesday and Saturday));<br>
- Select the key;<br>
- Select the type of operation mode;<br>
- Select the temperature sensor;<br>
- Fill in the climate <br>
&nbsp;&nbsp;(on-off - will not affect, autostop - in how many minutes will turn off ,<br>
&nbsp;&nbsp;in the other modes the temperature level sensor);<br>
- Time  2 must be greater than or equal to Time 1 and time fields located during one day <br>
&nbsp;&nbsp;(on-off and climat - no effect, autostop - Time 1 is only used  , <br>
&nbsp;&nbsp;timer and climat + timer - the time range in one day).
','port20031');

   }
   function admin ()
	{
    	if ( function_exists('add_options_page') )
    	{
        	add_options_page('Motion 1Wire WP Options',
            'Motion 1Wire WP', 8, basename(__FILE__),
            array (&$this, 'admin_form') );
    	}
	}

   function folder_video($atts,$content = null)
	{
		extract(shortcode_atts(array(
      'folder'=> '/wp-content/gallery_video',
      'sort'=> 'date_desc',
      ), $atts));
		//
		if ( ! in_array( $sort, array( 'filename','filename_desc','date','date_desc' ) ) )
			$sort = 'filename';
		if($content == null )
		{
			$content = "Folder video";
		}
		$folder = rtrim( $folder, '/' ); // Remove trailing / from path

		if ( !is_dir( '.'.$folder ) )
		{
			return '<div align="center"><p style="color:red;"><strong>Error : нет директории .'.
			$folder.'</strong></p></div>';
		}
		$video = $this->file_array( '.'.$folder, $sort );
		$NoP = count( $video );
		if ( 0 == $NoP )
		{
			return '<div align="center"><p style="color:red;"><strong>Нет видео в папке .</strong></p></div>';
		}
		$ttt='<div align="center">'.$content.'</div><div align="center">';
		foreach($video as $val)
   	{
      	$ttt= $ttt.'<a target="_blank" href="'.site_url().$folder.'/'.$val.'">'.$val.'</a>&nbsp; &nbsp; ';
   	}
		$ttt=$ttt.'</div>';
		return $ttt;
	}
   function myglob( $directory )
   {
		$files = array();
		if( $handle = opendir( $directory ) )
		{
			while ( false !== ( $file = readdir( $handle ) ) )
			{
				$ext = strtolower( pathinfo( $file, PATHINFO_EXTENSION ) );
    			if ( 'mp4' == $ext  )
    			{
					$files[] = $file;
				}
			}
		closedir( $handle );
		}
		return $files;
	}
   function file_array( $directory , $sort)
   {
   	// List all video files in $directory
		$cwd = getcwd();
		chdir( $directory );
		$files = glob( '*.{mp4}' , GLOB_BRACE );
		// Free.fr doesn't accept glob function. Use a workaround
		if ( 0 == count( $files ) ||  $files === FALSE ) {
			chdir( $cwd ); // Back to root
			$files = $this->myglob( $directory );
			chdir( $directory );
		}
		// Verify there's something to sort
		if ( 0 == count($files) || $files === FALSE ) {
			chdir( $cwd );
			return array();
		}
		// Remove first file if its name is !!!
		sort( $files ); // Sort by name
		$firstfile = $files[0];
		if ( $this->filename_without_extension( $firstfile ) == '!!!' ) {
			unset( $files[0] );
		} else {
			$firstfile = false;
		}
		// Sort files
		switch ( $sort ) {
			case 'random' :
				shuffle( $files );
			break;
			case 'date' :
				array_multisort( array_map( 'filemtime' , $files ) , SORT_ASC, $files );
			break;
			case 'date_desc' :
				array_multisort( array_map( 'filemtime' , $files ) , SORT_DESC, $files );
			break;
			case 'filename_desc' :
				rsort( $files );
			break;
			default:
				//sort( $files ); already done above
		}
		// Set back !!! file, if any
		if ( $firstfile ) {
			array_unshift( $files, $firstfile );
		}
		chdir( $cwd );
		return $files;
	}
	function filename_without_extension ( $filename )
	{
		$info = pathinfo($filename);
		return basename($filename,'.'.$info['extension']);
	}
	function mediabrowser_url($atts,$content = null)
	{
		extract(shortcode_atts(array(
      'url'=> $_SERVER['SERVER_NAME'],
      'port'=> 8096,
      ), $atts));
		if($content == null )
		{
			$content = "URL Media Browser";
		}
		$maintext='
		<br>
		<!-- Ссылка откроется в этом окне -->
		<!-- <div align="center"><a href="http://'.$url.':'.$port.'/mediabrowser">'.$content.'</a></div> -->
		<!-- Ссылка откроется в новом окне -->
  		<div align="center"><a href="http://'.$url.':'.$port.'/mediabrowser" target="_blank">'.$content.'</a></div>
		<br>
		';
		return $maintext;
	}
   function plex_url($atts,$content = null)
	{
		extract(shortcode_atts(array(
      'url'=> $_SERVER['SERVER_NAME'],
      'port'=> 32400,
      ), $atts));
		if($content == null )
		{
			$content = "URL Plex";
		}
		$maintext='
		<br>
		<!-- Ссылка откроется в этом окне -->
		<!-- <div align="center"><a href="http://'.$url.':'.$port.'/web">'.$content.'</a></div> -->
		<!-- Ссылка откроется в новом окне -->
  		<div align="center"><a href="http://'.$url.':'.$port.'/web" target="_blank">'.$content.'</a></div>
		<br>
		';
		return $maintext;
	}
   function motion_linux_online($atts,$content = null)
	{
		extract(shortcode_atts(array(
      'port'=> 8081,
      'width'=> 320,
      'height'=> 240,
      'href'=> 'none',
   	), $atts));
		$maintext='';
		if($content == null )
		{
			if( $href=='none' )
			{
				$maintext='	<br>	<div align="center">
				<img src="http://'.$_SERVER['SERVER_NAME'].':'.$port.'" width="'.$width.'" height="'.$height.'" /></div>
				<br><br>';
			}
			else
			{
				$maintext='	<br>	<div align="center">
				<a href="http://'.$_SERVER['SERVER_NAME'].''.$href.'">
				<img src="http://'.$_SERVER['SERVER_NAME'].':'.$port.'" width="'.$width.'" height="'.$height.'"
				border="0" >
				</a></div><br><br>';
			}
		}
		else
		{
			if( $href=='none' )
			{
				$maintext='	<br>	<div align="center">'.$content.'<br>
				<img src="http://'.$_SERVER['SERVER_NAME'].':'.$port.'" width="'.$width.'" height="'.$height.'" /></div>
				<br><br>';
			}
			else
			{
				$maintext='	<br>	<div align="center">'.$content.'<br>
				<a href="http://'.$_SERVER['SERVER_NAME'].''.$href.'">
				<img src="http://'.$_SERVER['SERVER_NAME'].':'.$port.'" width="'.$width.'" height="'.$height.'"
				border="0" >
				</a></div><br><br>';
			}
		}
		return $maintext;
	}
}
$port20031 = new port20031();

