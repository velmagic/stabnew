<?php
// Работа с системными файлами, изменными мной
define ('delimiter', '_', true);
define("_D_DEL","Удалён файл");
define("_D_EDIT","Изменён файл");
define("_D_NEW","Добавлен новый файл");
define("_NO","Нет");

include("smsc_api.php");

$files = array('shop.xml'=>'products/shop.xml','upgrade.php'=>'upgrade.php','util.php'=>'util.php');
$myMail = 'info@stabilok.ru';
$siteName='stabnew';

// ---- МЕНЯТЬ!!! ------------
$rootDir = '/var/www/stabnew/'; // Корень сайта
//$rootDir = 'D:\\Web\\OpenServer\\_Sites\\stabnew\\'; // Корень сайта
// ==== МЕНЯТЬ!!! ============

$logDir = __DIR__.'/serious_files/';

// Создание каталога
if(!file_exists($logDir))
{
	mkdir($logDir, 0700);
	chown ( $logDir, 'stan');
}	

$dump_cron_num = $logDir.'dump_cron_num.txt';
$dump_cron = $logDir.'dump_cron.txt';

$oldnum = GetOldLogNum();
$newnum = GetNextLogNum();
$nosend='';

function GetLogName ($newLog = true)
{
	global $newnum,$oldnum,$rootDir,$logDir ;
	$num = $newnum;
	if ($newLog == false)
		$num = $oldnum;
	return $logDir.'dumplog'.$num.'.txt';
}
function GetDumpName ($newLog = true)
{
	global $newnum,$oldnum,$rootDir,$logDir; 
	$num = $newnum;
	if ($newLog == false)
		$num = $oldnum;
	return $logDir.'dump'.$num.'.txt';
}

function create_dump($dir, &$log)
{
	global $rootDir, $files;

	foreach ($files as $name=>$file)
	{
		$location = $dir.$file;
		{
			$log[$location] = md5_file($location);
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
	global $newnum,$rootDir;

	if ($fp = fopen(GetLogName(true), "wb")) 
	{ 
		$log = ($log) ? implode("\n", $log) : _NO;
		flock($fp, 2);
		$len = fwrite($fp, date("d.m.Y - H:i:s")."\n---\n".$log."\n");
		flock($fp, 3);
		$ret = fflush ($fp);
		fclose($fp);
	}
	else
	{
		$nosend = 'Файл не записан';
	}
}

function diff_dump($dump, $old) {
global $oldnum, $rootDir;
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
			// Файл изменился -> вернуть старый файл и отослать смс и почту
			ExchangeFiles($location);
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

	if ($sess_d == 2)
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

function CopyFiles2Dir()
{
	global $files,$logDir,$rootDir;
	foreach ($files as $name=>$location)
	{
		$f = $logDir.$name;
		if(!file_exists($f))
			copy ($rootDir.$location,$logDir.$name);
	}
}
function ExchangeFiles($location)
{
	global $rootDir,$logDir,$files;
	
	return;
	
	$rootPathLen = strlen($rootDir);
	$file = substr ($location,$rootPathLen);
	foreach($files as $name=>$path)
		if ($file == $path)
			$file = $name;
	if (copy ($location,$logDir.NewFileName($file)) == false)
		echo 'New File copy Error<br>';
	else if (copy ($logDir.$file,$location) == false)
		echo 'Old File copy Error<br>';
	else if (copy ($logDir.NewFileName($file),$logDir.$file) == false)
		echo 'New File to file copy Error<br>';
}

function PrintVar($var,$name='')
{
	$n = $name!='' ? $name.': ':'';
	echo $n.$var.'<br>';
}
function NewFileName($fileName)
{
	$ar = explode('.',$fileName);
	return $ar[0].delimiter.Date('Y-m-d').'.'.$ar[1];
}

// ----------------------------
CopyFiles2Dir(); // For first run

$sess_d = (file_exists($dump_cron) && filesize($dump_cron) != 0) ? file_get_contents($dump_cron) : 0;
$past = time() - intval(20);
echo'$sess_d-'.$sess_d.' $past-'.$past.'<br>';
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
	create_dump($rootDir, $dump);
	
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
		$subject = 'Stabilok - important';
		$where = 'stabilok.ru';
		$mmsg = "На $where important изменения:<br>".date("d.m.Y - H:i:s")."<br><br>".$log."<br>".$nosend."<br>";

		mail_send($myMail, $myMail, $subject, $mmsg, 0, 1);
//echo $mmsg;
		$sms = 'На '.$where.' important изменения ('.count($log).')';
		$str = iconv ( 'utf-8', 'windows-1251',  $sms );
		$outText = $sms;

		list($sms_id, $sms_cnt, $cost, $balance) = 
		send_sms('+79851246271', $str, 0, 0, 0, 0, $sender = 'jar-magic', $query = "", $files = array());
		/* Исправить if ((int)$balance < 3)
		{
			sleep(1);
//			$str = iconv('utf-8','windows-1251',
			send_sms('+79851246271', 'Баланс '.$balance, 0, 0, 0, 0, $sender = 'jar-magic', $query = "", $files = array());
		}
		*/
		if ($sms_id < 0)
			mail_send($myMail, $myMail, 'Ошибка СМС для '.$where, 'Ошибка отправки СМС - '.$sms_id, 0, 1);
	}
//	echo 'End Site';
	if (isset($outText) && isset($argv))
	{
		echo '<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache">
</head>
<body>'.$outText.'</body>';
	}
}
?>