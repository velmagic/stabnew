<?php
$ip=$_SERVER['REMOTE_ADDR'];
if ($ip == '127.0.0.1')
{
	error_reporting(E_ALL | E_STRICT) ;
	ini_set('display_errors', 'On');
}

require_once 'config.php';

$products = getProductsArray();

$tmpl = startTemplate('index.tmpl');
$tmpl->setLoop('products', $products);
$tmpl->pparse();

?>