<?php

require_once 'config.php';

if ((!isset($_GET['product'])) || (!preg_match('/^(\-|[0-9]|[a-zA-Z])*$/', $_GET['product'])))
{
  if (function_exists('onProductInvalid'))
    call_user_func('onProductInvalid');
  redirect('/');
}

$product = getProductsArray("//product[@code='" . $_GET['product'] . "']");
if (count($product) != 1) {
  if (function_exists('onProductNotExist'))
    call_user_func('onProductNotExist', $_GET['product']);
  redirect('/');
}

$text = '';
if (file_exists(dirname(__FILE__) . '/custom/products/html/' . $_GET['product'] . '.htm'))
{
  $text = file_get_contents(dirname(__FILE__) . '/custom/products/html/' . $_GET['product'] . '.htm');
} else {
  if (file_exists(dirname(__FILE__) . '/products/html/' . $_GET['product'] . '.htm'))
    $text = file_get_contents(dirname(__FILE__) . '/products/html/' . $_GET['product'] . '.htm');
}

$text = processExtendedTags($text);

$tmpl = startTemplate('product.tmpl');
$tmpl->setvar('text', $text);

foreach ($product[0] as $key => $value) {
    if (is_scalar($value)) {
	$tmpl->setVar($key, $value);
    }
}

$deliveryCost = ($product[0]['price'] > $tmpl->getVar('DELIVERY_FREE_LIMIT')) ? 0 : $tmpl->getVar('DELIVERY_PRICE');
$tmpl->setvar('DELIVERY_COST', $deliveryCost);

$tmpl->pparse();

?>