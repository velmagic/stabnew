<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#">
<head>
<meta http-equiv="content-type" content="text/html; charset=windows-1251" />
</head>
<body>
<?php
$pref = __DIR__.'/../';
$dump_cron_num = $pref.'config/counter/dump_cron_numF.txt';
$dump_cron = $pref.'config/counter/dump_cronF.txt';

$oldnum = GetOldLogNum();

function GetOldLogNum()
{
	global $dump_cron_num;
	$sess_d = (file_exists($dump_cron_num) && filesize($dump_cron_num) != 0) ? file_get_contents($dump_cron_num) : 0;
	return $sess_d;
}
function GetLogName ()
{
	global $newnum,$oldnum,$pref; 
	return $pref.'config/logs/dumplogF'.$oldnum.'.txt';
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
		$echo .= '<div style="font-size:16px;margin-bottom:5px;'.$st.';">'.$str.'</div>';
	}	
	echo $echo;	
}
else 
	echo '<div style="color:red;fon-size:18px;margin: 100px 0 0 100px;">Файл отсутствует или нулевой длины</div>';
?>
</body>