<?php
define("FUNC_FILE", true);
if ($_POST['engine'])
{
	$pref = __DIR__.'/../';
	include($pref."config/config.php");

	mysql_connect($dbhost,$dbuname,$dbpass) or
	die("mysql_connect:Не подсоедениться :".mysql_error());
  
	if (!mysql_select_db ($dbname))
	{
		echo 'Ошибка mysql_select_db - '.mysql_error().'\n';
		exit;
	}
		if ($_POST['engine'] == 'as')
			$num = 1;
		else
			$num = 2;
	$res = mysql_query("UPDATE `".$prefix."_close` SET `closed`=1 WHERE `id`=".$num);
	if (!$res)
	{
		echo 'Ошибка обновления таблицы close - '.mysql_error().'\n';
		exit;
	}
	else
	{
		if ($num == 1)
			$name = 'Сайт';
		else	
			$name = 'Форум';
		echo '<div style="color:red;margin:100px 0 0 100px;font-size:18px;">'.$name.' закрыт</div>';
	}	
	mysql_close();	
}
else
{

echo '<html>
<style>
#wr0
{
	top: 20%; /* Отступ в процентах от верхнего края окна */
	left: 50%; /* Отступ в процентах от левого края окна */
	width: auto; /* Ширина блока */
	height: auto; /* Высота блока */
	position: absolute; /* Абсолютное позиционирование блока */
	margin-top: -225px; /* Отрицательный отступ от верхнего края страницы, должен равняться половине высоты блока со знаком минус */
	margin-left: -225px; /* Отрицательный отступ от левого края страницы, должен равняться половине высоты блока со знаком минус */
}
.wr
{
margin:100px 0 0 50px;
border:1px solid #000;
background-color:#feb;
padding: 20px 120px;
display:inline-block;
}
.cl
{
	margin: 0 40px;
	font-size:18px;
	line-height:1.5em;
}
p {padding:0;}
</style>
<div id="wr0">
<span class="wr">
<form name="test" method="post" action="close.php">
  <p><b>Остановить:</b></p>
   <span style="color:#0c8"><input type="radio" name="engine" value="as"> Сайт &nbsp;</span>
   <span style="color:#b09"><input type="radio" name="engine" value="smf"> Форум
  </p>

  <p><input type="submit" value="Отправить">
 </form>

</body>
</html> </span></div>';
}
