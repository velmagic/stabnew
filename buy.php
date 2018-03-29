<?php

require_once 'config.php';

if ((!isset($_GET['product'])) || (!preg_match('/^([0-9a-zA-Z\-])*$/', $_GET['product'])))
{
  redirect('/');
}

$product = getProductsArray("//product[@code='" . $_GET['product'] . "']");
if (count($product) != 1) {
  redirect('/');
}

if (isset($product[0]['composite']) && (is_array($product[0]['composite'])))
{
  $products = $product[0]['composite'];
} else {
  $products = array(
    array(
      'code' => $_GET['product'],
      'quantity' => 1,
    ),
  );
}

foreach ($products as $product)
{
  if (isset($_SESSION[$product['code']])) {
    $_SESSION[$product['code']] += $product['quantity'];
  } else {
    $_SESSION[$product['code']] = $product['quantity'];
  }
}

redirect('cart.php');

?>