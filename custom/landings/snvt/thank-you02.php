<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ru-RU">

<head>

<title>Stabilok</title>

<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale = 1.0, user-scalable = no">
<meta name="apple-mobile-web-app-capable" content="yes">

<!-- Фавикон -->
<link rel="shortcut icon" href="favicon.ico" >


<!-- CSS -->
<link href="/custom/landings/best/files/css/style.thank-you.css" rel="stylesheet" type="text/css" />
<!-- /CSS -->

</head>

<body>
<!-- ======= Основа сайта ======= -->
<div id="main">

    
    <!-- ======= Картинка ======= -->
	<center class="zagolovok">«STABILOK.RU»</center>
<?php
	$usermail = 'order@stabilok.ru';
	$phn = $_GET['phn'];
	$username = $_GET['name'];
	$prod_code = $_GET['prod-code'];
if(!isset($_GET['error']))
{	

	$subject .= "Заказ с левой формы";

	echo '<!-- ======= Заголовок ======= -->
    <div class="zagolovok">СПАСИБО<br />Ваша заявка будет рассмотрена в ближайшее время.</div>
    
    <!-- ======= Подзаголовок ======= -->
    <div class="podzagolovok">Очень скоро наш менеджер свяжется с Вами.</div>
	<div class="podzagolovok"><a href="/" onmouseover="this.style.cursor=&quot;pointer&quot; " onfocus="this.blur();" style="cursor: pointer;color: #fff;text-decoration: none;">Вернуться на сайт >>></a></div>';
}
else
{
	$subject .= "Ошибка заказа с левой формы";
	echo '<!-- ======= Заголовок ======= -->
    <div class="zagolovok_err"><img src="/theme/img/error64.png" width="64" height="64" style="float:left;border:1px solid;"><span>Ошибка отправки заказа</span></div>
    
    <!--   ======= Подзаголовок ======= -->
    <div class="podzagolovok">Ошибка отправки заказа.<br>
							Сделайте, пожалуйста, заказ по телефону +7(499)322-8089.<br>Заказы принимаются <span style="font-weight:bold;">круглосуточно.</span>';
	echo '<div class="podzagolovok"><a href="http://best.stabilok.ru" onmouseover="this.style.cursor=&quot;pointer&quot; " onfocus="this.blur();" style="cursor: pointer;color: #fff;text-decoration: none;">Вернуться на сайт >>></a></div>';
}

$headers .= "From: " . strip_tags($usermail) . "\r\n";
$headers .= "Reply-To: ". strip_tags($usermail) . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html;charset=utf-8 \r\n";
$msg .= "<html><body style='font-family:Arial,sans-serif;'>";
$msg .= "<h2 style='font-weight:bold;border-bottom:1px dotted #ccc;'>Сделан заказ</h2>\r\n";
$msg .= "<p><strong>Имя:</strong> ".$username."</p>";
$msg .= "<p><strong>Номер телефона:</strong> ".$phn."</p>\r\n";
$msg .= "<p><strong>Продукт: </strong>".$prod_code."</p>\r\n";
$msg .= "</body></html>";
@mail('info@stabilok.ru', $subject, $msg, $headers);
?>	
</div> <!-- ======= / Основа сайта ======= -->

</body>

</html>

