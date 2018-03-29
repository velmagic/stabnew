<!DOCTYPE html>
<html lang="ru">
<head>
  <meta http-equiv="content-type" content="text/html;charset=windows-1251">
</head><body>
<?php
/*
Stabilok 74993228089
powerpartners 74952551970
*/

//Показ РК в выходные

require_once 'Client.php';
$params = array(
    'id' => '481169',
);


$zd = new \Zadarma_API\Client('b2e1f6eebc034ac5b0f5', '2bd32cf17cb44fafbf04',false);
$answer = $zd->call('/v1/sip/redirection/', $params);

$answerObject = json_decode($answer);

if ($answerObject->status == 'success')
{
   // var_dump($answerObject);

	echo '<br>Текущая переадрессация <span style="color:blue;font-size:125%;font-weight:bold;">'.$answerObject->info[0]->destination_value.'</span><br>';
	
	if ($answerObject->info[0]->destination_value == '74952551970')
		$phTo = '79851246271';
	else
		$phTo = '74952551970';

	$params = array(
		'id' => '481169',
		'type' => 'phone',
		'number' => $phTo
	);

	$answer = $zd->call('/v1/sip/redirection/', $params, 'put');

	$answerObject = json_decode($answer);

	if ($answerObject->status == 'success') {
		echo 'Redirection on your SIP "' . $answerObject->sip . ' has been changed to <span style="color:green;font-size:125%;font-weight:bold;">' . $answerObject->destination . "</span>.";
	} else {
		echo $answerObject->message;
	}
	
}
else 
{
    echo $answerObject->message;
}
?>
</body>

