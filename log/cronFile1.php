<?php
include("smsc_api.php");

if (
		(isset($argv) && 
		isset($argv[1]) && 
		$argv[1] == 'forum') || 
		isset($_GET['forum'])
	)
	$isForum = true;
else
	$isForum = false;

ini_set('display_errors',1);
  error_reporting(E_ALL);

if (!isset($argv))
	echo '<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#">
<head>
<meta http-equiv="content-type" content="text/html; charset=windows-1251" />
<meta http-equiv="Cache-Control" content="no-cache">
</head>
<body>';

define("_D_DEL","Удалён файл");
define("_D_EDIT","Изменён файл");
define("_D_NEW","Добавлен новый файл");
define("_NO","Нет");

if($isForum)
{	
	$pref = '/home/jarmagic/forum/'; // Корень сайта
	$logDir = __DIR__.'/forum/';
}
else
{	
	$pref = '/home/jarmagic/public_html/'; // Корень сайта
	$logDir = __DIR__.'/jar-magic/';
}	
// Создание каталога
if(!file_exists($logDir))
	mkdir($logDir, 0700);
	
$dump_cron_num = $logDir.'dump_cron_num.txt';
$dump_cron = $logDir.'dump_cron.txt';

$oldnum = GetOldLogNum();
$newnum = GetNextLogNum();
$nosend='';

//echo 'Oldnum = ' .$oldnum.' &nbsp; Newnum = '.$newnum.'<br>';

function GetLogName ($newLog = true)
{
	global $newnum,$oldnum,$pref,$logDir ;
	$num = $newnum;
	if ($newLog == false)
		$num = $oldnum;
	return $logDir.'dumplog'.$num.'.txt';
}
function GetDumpName ($newLog = true)
{
	global $newnum,$oldnum,$pref,$logDir; 
	$num = $newnum;
	if ($newLog == false)
		$num = $oldnum;
	return $logDir.'dump'.$num.'.txt';
}

function create_dump($dir, &$log) {
global $pref,$isForum;
	if (is_dir($dir)) 
	{
		if (strpos ($dir,'logs') !== false ||
			strpos ($dir,'cache') !== false ||
			strpos ($dir,'backup') !== false ||
			strpos ($dir,'uploads') !== false ||
			strpos ($dir,'attachments') !== false
			) // Stan
			return;
		//	echo 'Cur dir='.$dir.'<br>';
		if ($dh = opendir($dir)) {
			while (($file = readdir($dh)) !== false) {
				if ($file == "." || $file == "..") continue;				
				$location = $dir.$file;
				if (filetype($location) == "dir") 
				{
					create_dump($location."/", $log);
				} 
				else 
				{
					/* Исключение конкретных файлов */
						if (strpos($location,$pref.'OldUrl.log') === false  &&
						strpos($location,$pref.'DebugLog.log') === false  &&
						strpos($location,$pref.'sitemap.xml') === false  &&
						 strpos($location,$pref.'error_log') === false )
						 /*strpos($location,$pref.'config/counter/sess.txt') === false &&
						 strpos($location,'hack.txt') === false  &&
						 strpos($location,'cronFile') === false  &&
						 strpos($location,'log_admin') === false 
						)*/
						$log[$location] = md5_file($location);
				}
			}
			closedir($dh);
		}
	}
}

function write_dump($dump) {
//	echo 'write_dump<br>';
	if ($fp = fopen(GetDumpName(), "wb")) {
		$new = "";
		foreach ($dump as $location => $md5) $new .= $location."||".$md5."\n";
		flock($fp, 2);
		fwrite($fp, $new);
		flock($fp, 3);
		fclose($fp);
	}
	return ($fp) ? true : false;
}

function write_log($log) 
{
	global $newnum,$pref;
//	echo 'write_log<br>';
	/*if ($log == null)
		 'write_log: $log == null<br>';*/

	if ($fp = fopen(GetLogName(true), "wb")) 
	{ 
	//	echo 'File opened<br>';
		$log = ($log) ? implode("\n", $log) : _NO;
		flock($fp, 2);
		$len = fwrite($fp, date("d.m.Y - H:i:s")."\n---\n".$log."\n");
	//	$len = fwrite($fp, "llll\n---\n");
		flock($fp, 3);
		$ret = fflush ($fp);
		fclose($fp);
	//	echo 'File Writed: '.$len.' bytes ret='.$ret.'<br>';
	}
	else
	{
	//	echo 'Файл не открыт<br>';
	//	var_dump($fp);
	//	echo '<br>';
		$nosend = 'Файл не записан';
	}
}

function diff_dump($dump, $old) {
global $oldnum, $pref;
//echo 'diff_dump <br> '.dirname($_SERVER['PHP_SELF']).'<br>';
	$log = array();
	foreach ($old as $string) {
		list($location, $md5) = explode("||", trim($string));
		$new[$location] = $md5;
	}
	foreach ($new as $location => $md5) {
		if (!isset($dump[$location])) $log[] = _D_DEL.": ".$location;
	}
	$filedump = GetDumpName(false);
	$filelog = GetLogName(false);
//	echo 'diff_dump: $filedump == '.$filedump.' $filelog == '.$filelog.'<br><br>';
	foreach ($dump as $location => $md5) {
		if (strpos($filedump, substr($location, 2)) !== false || strpos($filelog, substr($location, 2))) continue;
		if (!isset($new[$location])) {
			$log[] = _D_NEW.": ".$location;
		} else if ($new[$location] != $dump[$location]) {
			$log[] = _D_EDIT.": ".$location;
		}
	}
	return (count($log) > 0) ? $log : false;
}

function GetNextLogNum()
{
global $dump_cron_num;
//echo 'GetNextLogNum: '.$dump_cron_num.'<br>';
	$sess_d = (file_exists($dump_cron_num) && filesize($dump_cron_num) != 0) ? file_get_contents($dump_cron_num) : 0;
	$sess_d++;

	if ($sess_d == 24)
		$sess_d = 0;
//	echo 'GetNextLogNum: '.$sess_d.'<br>';	
	if ($fp = fopen($dump_cron_num, "wb")) 
	{
		flock($fp, 2);
		fwrite($fp, $sess_d);
		flock($fp, 3);
		fclose($fp);
	}	
		return $sess_d;
}
function GetOldLogNum()
{
	global $dump_cron_num;
	$sess_d = (file_exists($dump_cron_num) && filesize($dump_cron_num) != 0) ? file_get_contents($dump_cron_num) : 0;
	return $sess_d;
}

# Mail send
function mail_send($email, $smail, $subject, $message, $id="", $pr="") {

	$mheader = "MIME-Version: 1.0\n"
	."Content-Type: text/html; charset=utf-8\n"
	."Content-Transfer-Encoding: 8bit\n"
	."Reply-To: \"$smail\" <$smail>\n"
	."From: \"$smail\" <$smail>\n"
	."Return-Path: <$smail>\n"
	."X-Priority: $pr\n"
	."X-Mailer: Jar-magic.ru\n";
/*			if ($_SERVER['HTTP_HOST'] != "85.202.227.13")
			{
			  $rett = mail($email, $subject, $message, $mheader);
			  echo '<br>mail_send() ret: <pre>';
			  var_dump($mheader);
			  echo '</pre><br>';
			}
			else*/
	return mail($email, $subject, $message, $mheader);
}


$sess_d = (file_exists($dump_cron) && filesize($dump_cron) != 0) ? file_get_contents($dump_cron) : 0;
$past = time() - intval(20);
if ($sess_d < $past) 
{
	@unlink($sess_f);
	$fp = fopen($dump_cron, "wb");
	fwrite($fp, time());
	fclose($fp);

	$safe = ini_get("safe_mode") == "1" ? 1 : 0;
	if (!$safe && function_exists("set_time_limit")) 
		set_time_limit(600);

	$dump = array();
	create_dump($pref, $dump);
	
	if (file_exists(GetDumpName(false)) && filesize(GetDumpName(false)) != 0) 
	{
		if ($log = diff_dump($dump, file(GetDumpName(false)))) 
			sort($log);
	} 
	else 
	{
		$log = false;
	}
	write_log ($log);
	write_dump($dump);

	if ($log) 
	{
		$log = ($log) ? implode("<br>", $log) : _NO;
		$subject = 'Ярило-Велес - cronFile';
		$where = ($isForum) ? 'форуме' : 'сайте';
		$mmsg = "На $where произошли изменения:<br>".date("d.m.Y - H:i:s")."<br><br>".$log."<br>".$nosend."<br>";

		mail_send('admin@jar-magic.ru', 'admin@jar-magic.ru', $subject, $mmsg, 0, 1);
//echo $mmsg;
		list($sms_id, $sms_cnt, $cost, $balance) = 
		send_sms('+79851246271', 'На '.$where.' произошли изменения ('.count($log).')', 0, 0, 0, 0, $sender = 'jar-magic', $query = "", $files = array());
		if ((int)$balance < 3)
		{
			sleep(1);
			send_sms('+79851246271', 'Баланс '.$balance, 0, 0, 0, 0, $sender = 'jar-magic', $query = "", $files = array());
		}
		if ($sms_id < 0)
			mail_send('admin@jar-magic.ru', 'admin@jar-magic.ru', 'Ошибка СМС для '.$where, 'Ошибка отправки СМС - '.$sms_id, 0, 1);
	}
//	echo 'End Site';
	
}
?>
