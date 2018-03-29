<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#">
<head>
<meta http-equiv="content-type" content="text/html; charset=windows-1251" />
<meta http-equiv="Cache-Control" content="no-cache">
</head>
<body>
<?php
if (
		(isset($argv) && 
		isset($argv[1]) && 
		$argv[1] == 'forum') || 
		isset($_GET['forum'])
	)
	$isForum = true;
else
	$isForum = false;
	
$pref = __DIR__.'/';
if($isForum)
	$logDir = __DIR__.'/forum/';
else
	$logDir = __DIR__.'/jar-magic/';

$dump_cron_num = $logDir.'dump_cron_num.txt';
$dump_cron = $logDir.'dump_cron.txt';

$oldnum = GetOldLogNum();

function GetOldLogNum()
{
	global $dump_cron_num;
	$sess_d = (file_exists($dump_cron_num) && filesize($dump_cron_num) != 0) ? file_get_contents($dump_cron_num) : 0;
	return $sess_d;
}
function GetLogName ()
{
	global $newnum,$oldnum,$pref,$logDir; 
	return $logDir.'dumplog'.$oldnum.'.txt';
}

$name = GetLogName();
if (file_exists($name) && filesize($name) != 0)
{
	$s = file_get_contents($name);
	$arr = explode("\n",$s);
	$cnt = 0;
	$am = count($arr)-3;
	$echo='Количество '.$am.'<br>';
	foreach ($arr as $str)
	{
		if ($cnt++%2)
			$st = 'color:red';
		else
			$st = 'color:green';
		$echo .= '<div style="font-size:24px;margin-bottom:5px;'.$st.';">'.$str.'</div>';
	}
	echo $echo;	
}
else 
	echo '<div style="color:red;fon-size:18px;margin: 100px 0 0 100px;">Файл отсутствует или нулевой длины</div>';
	
?>

</body>