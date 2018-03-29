<?php

require_once 'config.php';

$test = true;
$error = 0;

$req = "fname=" . urlencode(iconv('UTF-8', 'windows-1251', $_POST['fname']));
$req .= "&phone=" . urlencode($_POST['phone']);
$req .= "&customer_type=1"; //физлицо
$req .= "&payment_type=1"; //Наличными
$req .= "&delivery=0";  //Курьером (Москва и МО)
$req .= "&site_id=$SITE_ID";

$xml = "<?xml version=\"1.0\" encoding=\"windows-1251\"?>\n";
$xml .= "<order>\n";
$xml .= "  <item>\n";
$xml .= "    <code>" . $_POST['prod_code'] . "</code>\n";
$xml .= "    <quantity>" . $_POST['prod_quantity'] . "</quantity>\n";
$xml .= "  </item>\n";
$xml .= "</order>\n";

$reqMail = $req.'<br>'.$_POST['prod_code']. ' ' . $_POST['prod_quantity'].' шт.<br>';
$xml = urlencode($xml);
$req .= "&xml=$xml";

/*	Раскоментировать для БОЕВОМ режиме */
if ($test == false)
	$status = ServerRequest($req, $BILLING_SERVER, '/order.php');
else
	$status = 'Order #: 777';

//$p = preg_match('/Order #: ([0-9]*)/', $status, $matches);
if (preg_match('/Order #: ([0-9]*)/', $status, $matches)) 
{
    print json_encode(array('result' => '1', 'data' => $matches[1]));
	$subject .= 'ajax: Покупка '.$_POST['prod_code'];
	$tit ='';
}	
else
{
	$subject .= 'ajax: Ошибка покупки '.$_POST['prod_code'];
	$tit = 'Ошибка заказа';
	$error = 1;
	print json_encode(array('result' => '0', 'data' => $status));
}

if ($test == true)
	$tit .= ' -= ОТЛАДКА =-';
$usermail = 'order@stabilok.ru';

$headers .= "From: " . strip_tags($usermail) . "\r\n";
$headers .= "Reply-To: ". strip_tags($usermail) . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html;charset=utf-8 \r\n";
$msg .= "<html><body style='font-family:Arial,sans-serif;'>";
$msg .= "<h2 style='font-weight:bold;border-bottom:1px dotted #ccc;'>".$tit."</h2>\r\n";
$msg .= "<p><strong>Имя:</strong> ".$_POST['fname']."</p>";
$msg .= "<p><strong>Номер телефона:</strong> ".$_POST['phone']."</p>\r\n";
$msg .= '<p>++++Status: '.$status;
$msg .= '</p>\r\n';
$msg .= 'matches[1]: '.$matches[1].'<br> p= '.$p.'<br>';
if ($error == 1)
	$msg .= '<br><p style="color:red;font-weight:bold;">Ошибка запроса. Status: ' .$status.'<p>';
$msg .= $reqMail;
$msg .= "</body></html>";
@mail('info@stabilok.ru', $subject, $msg, $headers);

