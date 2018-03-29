<?php
require_once 'config.php';

$city = null;

$city = $_REQUEST['city'];

$products = getProductsArray();
$cart_callback = create_function('$item', "return isset(\$item['quantity']);");
$cart = array_filter($products, $cart_callback);

$cart_products = array();
foreach ($cart as $item) {
  $cart_product_item = array(
    'code' => $item['code'],
    'quantity' => $item['quantity'],
  );
  $cart_products[] = $cart_product_item;
}

$data = array('city' => $city, 'products' => json_encode($cart_products));

$ch = curl_init($CALC_SERVER);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

$result = curl_exec($ch);

$result = json_decode($result);

curl_close($ch);

if ( count($result) > 0 ) {
    if (!isset($result->error)) {
        print json_encode(array('result' => '1', 'data' => $result));
    } else {
        print json_encode(array('result' => '2'));
    }
} else {
    print json_encode(array('result' => '0'));
}

