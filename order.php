<?php

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$req = "fname=" . urlencode($_POST['fname']);
	$req .= "&lname=" . urlencode($_POST['lname']);
	$req .= "&mname=" . urlencode($_POST['mname']);

	$req .= "&email=" . urlencode($_POST['email']);
	$req .= "&phone=" . urlencode($_POST['phone']);

    if ($_POST['delivery'] == 2) {
        $req .= "&transport_company=" . urlencode($_POST['transport_company']);
        $req .= "&delivery_city=" . urlencode($_POST['delivery_city']);
    }

	$req .= "&address=" . urlencode($_POST['address']);
	$req .= "&comments=" . urlencode($_POST['comments']);

	$req .= "&customer_type=" . urlencode($_POST['customer']);
	$req .= "&payment_type=" . urlencode($_POST['payment']);
	$req .= "&delivery=" . urlencode($_POST['delivery']);

	$req .= "&yur_payer=" . urlencode($_POST['yur_payer']);
	$req .= "&yur_address=" . urlencode($_POST['yur_address']);
	$req .= "&yur_inn=" . urlencode($_POST['yur_inn']);
	$req .= "&yur_kpp=" . urlencode($_POST['yur_kpp']);
	$req .= "&yur_rs=" . urlencode($_POST['yur_rs']);
	$req .= "&yur_bank=" . urlencode($_POST['yur_bank']);
	$req .= "&yur_ks=" . urlencode($_POST['yur_ks']);
	$req .= "&yur_bik=" . urlencode($_POST['yur_bik']);

	$req .= "&wmid=" . urlencode($_POST['wmid']);

	$req .= "&fiz_lname=" . urlencode($_POST['fiz_lname']);
	$req .= "&fiz_fname=" . urlencode($_POST['fiz_fname']);
	$req .= "&fiz_mname=" . urlencode($_POST['fiz_mname']);
	$req .= "&fiz_address=" . urlencode($_POST['fiz_address']);
		
	$req .= "&site_id=$SITE_ID";

	$xml = "<?xml version=\"1.0\" encoding=\"windows-1251\"?>\n";
	$xml .= "<order>\n";
	
        $products = getProductsArray();
        $cart_calback = create_function('$item', "return isset(\$item['quantity']);");
        $cart = array_filter($products, $cart_calback);

	foreach ($cart as $product) {
	  $xml .= "  <item>\n";
	  $xml .= "    <code>" . $product['code'] . "</code>\n";
	  $xml .= "    <quantity>" . $product['quantity'] . "</quantity>\n";
	  $xml .= "  </item>\n";
	}
	$xml .= "</order>\n";

	$xml = urlencode($xml);
	$req .= "&xml=$xml";

	$status = ServerRequest($req, $BILLING_SERVER, '/order.php');

	if (preg_match('/^Order #: ([0-9]*)$/', $status, $matches)) {
        	$tmpl = startTemplate('complete.tmpl');
        	$tmpl->setVar('order_no', $matches[1]);
        	$tmpl->pparse();
 	}

	exit;
}

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

$total_cost_callback = create_function('$item', "global \$TOTAL_COST; \$TOTAL_COST += \$item['price'] * \$item['quantity'];");
array_map($total_cost_callback, $cart);

$delivery_cost_callback = create_function('$item', "global \$DELIVERY_COST, \$TOTAL_COST; if ((isset(\$item['code'])) && (\$item['code'] == \$_SESSION['delivery']) && (\$TOTAL_COST < 10000)) { \$DELIVERY_COST = \$item['price']; }");
array_map($delivery_cost_callback, $deliveries);

$weight_callback = create_function('$item', "\$item['weight'] = \$item['quantity'] * \$item['weight']; return \$item;");
$cart = array_map($weight_callback, $cart);

$total_quantity_callback = create_function('$item', "global \$TOTAL_QUANTITY; \$TOTAL_QUANTITY += \$item['quantity'];");
array_map($total_quantity_callback, $cart);

$total_weight_callback = create_function('$item', "global \$TOTAL_WEIGHT; \$TOTAL_WEIGHT += \$item['weight'] * \$item['quantity'];");
array_map($total_weight_callback, $cart);


$TOTAL_COST += $DELIVERY_COST;


$tmpl = startTemplate('order.tmpl');
$tmpl->setLoop('products', $cart);
$tmpl->setLoop('deliveries', $deliveries);
$tmpl->setVar('delivery_cost', $DELIVERY_COST);
$tmpl->setVar('total_quantity', $TOTAL_QUANTITY);
$tmpl->setVar('total_weight', $TOTAL_WEIGHT);
$tmpl->setVar('total_cost', $TOTAL_COST);
$tmpl->pparse();

?>