<?php

require_once 'config.php';

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

$xml = urlencode($xml);
$req .= "&xml=$xml";

$status = ServerRequest($req, $BILLING_SERVER, '/order.php');

if (preg_match('/Order #: ([0-9]*)/', $status, $matches)) {
    print json_encode(array('result' => '1', 'data' => $matches[1]));
}