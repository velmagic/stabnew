<?php

require_once 'config.php';

if ((!isset($_GET['product'])) || (!preg_match('/^([0-9a-zA-Z\-])*$/', $_GET['product'])))
{
  redirect('/');
}

if (isset($_SESSION[$_GET['product']])) {
  $_SESSION[$_GET['product']]++;
} else {
  $_SESSION[$_GET['product']] = 1;
}

redirect('cart.php');

?>