<?php

require_once 'config.php';

$offers = getProductsArray();

$base_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']);

print "<?xml version=\"1.0\" encoding=\"windows-1251\"?>\n";

$tmpl = startTemplate('yml.tmpl');
$tmpl->setLoop('offers', $offers);
$tmpl->setVar('DATE_TIME', date("Y-m-d H:i"));
$tmpl->setVar('BASE_URL', $base_url);
$tmpl->setVar('HOST', $_SERVER['HTTP_HOST']);
$tmpl->pparse();

?>