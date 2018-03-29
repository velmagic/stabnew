<?php

require_once 'config.php';

foreach($_POST as $key => $value)
{
  if (isset($_SESSION[$key]) && is_numeric($value))
  {
    $_SESSION[$key] = (int)$value;
  }

}

redirect('cart.php');

?>