<?php
require_once 'config.php';
ob_start ();
//$BILLING_SERVER = 'billing.powerpartners.ru';
$ok = true;
$req = "fname=" . 'Отладка формы заказа';//urlencode(iconv('UTF-8', 'windows-1251', $_POST['fname']));
$req .= "&phone=" . 'Отладка формы заказа';//urlencode($_POST['phone']);
$req .= "&customer_type=1"; //физлицо
$req .= "&payment_type=1"; //Наличными
$req .= "&delivery=0";  //Курьером (Москва и МО)
$req .= "&site_id=0";//$SITE_ID";

$xml = '<?xml version="1.0" encoding="windows-1251"?>\n';
$xml .= "<order>\n";
$xml .= "  <item>\n";
$xml .= "    <code>" . $_POST['prod_code'] . "</code>\n";
$xml .= "    <quantity>" . $_POST['prod_quantity'] . "</quantity>\n";
$xml .= "  </item>\n";
$xml .= "</order>\n";

ShowVar($_POST, '_POST');
$reqMail = $req.'<br>'.$_POST['prod_code']. ' ' . $_POST['prod_quantity'].' шт.<br>';

$xml = urlencode($xml);
$req .= "&xml=$xml";
//ShowVar($reqMail,' $reqMail');
echo $reqMail;

/*	Раскоментировать для БОЕВОМ режиме */
//$status = ServerRequest($req, $BILLING_SERVER, '/order.php');
///$status = 'Order #: 902345';
if (preg_match('/Order #: ([0-9]*)/', $status, $matches)) 	
    print json_encode(array('result' => '1', 'data' => $matches[1]));
else
	$ok = false;

ShowVar($status, 'status');
$usermail = 'order@stabilok.ru';
if ($ok == true)
{	
	$subject .= 'ajax: Покупка '.$_POST['prod_code'];
	$tit ='';
}	
else
{
	$subject .= 'ajax: Ошибка покупки '.$_POST['prod_code'];
	$tit = 'Ошибка заказа';
}	

$out = ob_get_contents ();
ob_end_clean ();

$headers .= "From: " . strip_tags($usermail) . "\r\n";
$headers .= "Reply-To: ". strip_tags($usermail) . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html;charset=windows-1251 \r\n";
$msg .= "<html><body style='font-family:Arial,sans-serif;'>";
$msg .= "<h2 style='font-weight:bold;border-bottom:1px dotted #ccc;'>".$tit."</h2>\r\n";
$msg .= "<p><strong>Имя:</strong> ".$_POST['fname']."</p>";
$msg .= "<p><strong>Номер телефона:</strong> ".$_POST['phone']."</p>\r\n";
$r = (isset($status)!==false)?$status:'-7777777';
$msg .= '<p>++++Status: '.$r;
$msg .= '</p>\r\n';
//$msg .= '\r\n\r\n';
$msg .= $reqMail;
$msg .= '<br>'.$out."</body></html>";
@mail('info@stabilok.ru', $subject, $msg, $headers);

ShowVar( $msg,'msg');
/*	Закоментировать для БОЕВОМ режиме */
//print json_encode(array('result' => '1'));

/*else
{	
	echo $req;
	echo '<script> alert($req;);</script>';
}*/

echo $out;

function ShowVar($var,$str)
{
	echo '<br>----------- '.$str.' -------------<br><pre><code>';
	var_dump($var);
	echo '</code></pre><br>';
}