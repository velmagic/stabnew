<?php 

//echo __DIR__.'<br>';
include(__DIR__."/smsc_api.php");
//echo __DIR__.'/smsc_api.php<br>';
$balance = get_balance();
echo '<div style="color:blue;font-size:18px;margin:100px 0 0 100px;">Баланс '.$balance.'</div>';
?>