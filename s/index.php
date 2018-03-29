<?php

require_once 'config.php';

$products = getProductsArray();

$tmpl = startTemplate('index.tmpl');
$tmpl->setLoop('products', $products);
$tmpl->pparse();

?>