<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#">
<head>
<meta http-equiv="content-type" content="text/html; charset=windows-1251" />
<title>������������� ����������</title>
</head>
<body>
<h1>StabilOK - ��������-������� �������������� ���������� � ������ ���������� ������� ������� �������</h1><br>
������������<br>
���<br>
�������� �������������� �������<br>
���������<br>
������������� �������<br>
voltron<br>
upower<br>
������������<br>

<?php
require 'browser.php';
$isBot = false;
$br = new Browser;
$arr = array();
$arr = json_decode(file_get_contents ( 'counter.json'),true);

$brName = $br->getBrowser();
if ($br->isRobot())
{
	$isBot = true;
	if(isset($arr['robots']))
		$arr['robots']++;
	else
			$arr['robots'] = 1;		
}
if($isBot == false)
{
	if (BotName() != false)
		if(isset($arr['robots']))
			$arr['robots']++;
		else
			$arr['robots'] = 1;		
}		
/*echo '<br><pre>';
var_dump($arr);
echo '</pre><br>';
*/
$cnt = $arr['cnt'];
if ('185.8.217.7' != $_SERVER["REMOTE_ADDR"])
{
	if(isset($arr[$brName]))
		$arr[$brName]++;
	else
		$arr[$brName] = 1;
	$cnt++;
	$arr['cnt'] = $cnt;
}
else
{
	if(isset($arr['itsme']))
		$arr['itsme']++;
	else
		$arr['itsme'] = 1;
	echo '<br>����� ������:<br><pre>';
	var_dump($arr);
	echo '</pre><br>';
	echo '�������: '.$brName.' OS :'.$br->getPlatform().'<br><br>';
	echo '<pre>';
	var_dump($br);
	echo '</pre><br>';
}

if (false===file_put_contents ('counter.json',json_encode($arr)))
	 echo "Not writing<br>";		 
	 
function BotName(){
/* ��� ������� ����� ���������, �������� �� ���������� ������� ��������� ������� */
  $bots = array(
    'rambler','googlebot','aport','yahoo','msnbot','turtle','mail.ru','omsktele',
    'yetibot','picsearch','sape.bot','sape_context','gigabot','snapbot','alexa.com',
    'megadownload.net','askpeter.info','igde.ru','ask.com','qwartabot','yanga.co.uk',
    'scoutjet','similarpages','oozbot','shrinktheweb.com','aboutusbot','followsite.com',
    'dataparksearch','google-sitemaps','appEngine-google','feedfetcher-google',
    'liveinternet.ru','xml-sitemaps.com','agama','metadatalabs.com','h1.hrn.ru',
    'googlealert.com','seo-rus.com','yaDirectBot','yandeG','yandex',
    'yandexSomething','Copyscape.com','AdsBot-Google','domaintools.com',
    'Nigma.ru','bing.com','dotnetdotcom'
  );
  foreach($bots as $bot)
    if(stripos($_SERVER['HTTP_USER_AGENT'], $bot) !== false){
      return $bot;
    }
  return false;
}
?>

</body></html>
