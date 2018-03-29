<?php

require_once 'config.php';

$DELIVERY_COST = 0;
$TOTAL_QUANTITY = 0;
$TOTAL_WEIGHT = 0;
$TOTAL_COST = 0;

if (!isset($_SESSION['delivery'])) {
  $_SESSION['delivery'] = 0;
}

$products = getProductsArray();
$deliveries = getDeliveryArray();

$cart_calback = create_function('$item', "return isset(\$item['quantity']);");
$cart = array_filter($products, $cart_calback);

$weight_callback = create_function('$item', "\$item['product_weight'] = \$item['weight']; \$item['weight'] = \$item['quantity'] * \$item['weight']; return \$item;");
$cart = array_map($weight_callback, $cart);

$total_quantity_callback = create_function('$item', "global \$TOTAL_QUANTITY; \$TOTAL_QUANTITY += \$item['quantity'];");
array_map($total_quantity_callback, $cart);

$total_weight_callback = create_function('$item', "global \$TOTAL_WEIGHT; \$TOTAL_WEIGHT += \$item['weight'];");
array_map($total_weight_callback, $cart);

$total_cost_callback = create_function('$item', "global \$TOTAL_COST; \$TOTAL_COST += \$item['price'] * \$item['quantity'];");
array_map($total_cost_callback, $cart);

$delivery_cost_callback = create_function('$item', "global \$DELIVERY_COST, \$TOTAL_COST; if ((isset(\$item['code'])) && (\$item['code'] == \$_SESSION['delivery']) && (\$TOTAL_COST < 10000)) { \$DELIVERY_COST = \$item['price']; }");
array_map($delivery_cost_callback, $deliveries);

$_SESSION['cart_count'] = $TOTAL_QUANTITY;
$_SESSION['cart_value'] = $TOTAL_COST;

$delivery = $_SESSION['delivery'];

$TOTAL_COST += $DELIVERY_COST;


$tmpl = startTemplate('cart.tmpl');
$tmpl->setLoop('products', $cart);
$tmpl->setLoop('deliveries', $deliveries);
$tmpl->setVar('delivery', $delivery);
$tmpl->setVar('delivery_cost', $DELIVERY_COST);
$tmpl->setVar('total_quantity', $TOTAL_QUANTITY);
$tmpl->setVar('total_weight', $TOTAL_WEIGHT);
$tmpl->setVar('total_cost', $TOTAL_COST);
$tmpl->pparse();

?>