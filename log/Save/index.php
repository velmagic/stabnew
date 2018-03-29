<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#">
<head>
<meta http-equiv="content-type" content="text/html; charset=windows-1251" />
<meta http-equiv="Cache-Control" content="no-cache">
</head>
<body>

<?php
echo '
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
margin:100px 0 0 100px;
border:1px solid #000;
background-color:#feb;
padding: 20px 0;
display:inline-block;
}
.cl
{
	margin: 0 40px;
	font-size:18px;
	line-height:1.5em;
}
</style>
<div id="wr0">
<span class="wr">
		<div class="cl"><a href="cronFile.php">Проверка сайта</a></div>
		<div class="cl"><a href="cronFile.php?forum">Проверка форума</a></div>
		<div class="cl"><a href="showlog.php">Просмотр лога сайта</a></div>
		<div class="cl"><a href="showlog.php?forum">Просмотр лога форума</a></div>
		<div class="cl"><a href="open.php">Открыть сайт</a></div>
		<div class="cl"><a href="close.php">Закрыть сайт</a></div>
		<div class="cl"><a href="balance.php">Баланс</a></div>
		<br><div class="cl"><a href="send.php">Send SMS</a></div>
		</span></div>';
?>