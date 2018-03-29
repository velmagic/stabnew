<?php

require_once 'config.php';

if ((!isset($_GET['product'])) || (!preg_match('/^([0-9a-zA-Z\-])*$/', $_GET['product'])))
{
  redirect('/');
}

unset($_SESSION[ $_GET['product'] ]);

redirect('cart.php');

?>