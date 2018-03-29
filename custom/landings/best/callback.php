<?php
	require_once 'Client.php';
	
	$test = false;
	$errorPresent = false;
	$json = array();
	$phone = $_POST['to'];
	$json['phone'] = $phone;
									// мой тел
	$callParam = array('from'=>'+74993228089','to' =>$phone);
	
	$zd = new \Zadarma_API\Client('b2e1f6eebc034ac5b0f5', '2bd32cf17cb44fafbf04',false);
	if ($test == false)
	{	
		$answer = $zd->call('/v1/request/callback/', $callParam);
		$answerObject = json_decode($answer);
	}
	else
		$answerObject->status = 'success';
	
// Статистика по месяцам
	
	$statusSave = $answerObject->status;
	if ($answerObject->status != 'success')  // Error
	{
		// Сообщение об ошибке
		$json['status'] = -1;
		$json['message'] = $answerObject->message;
		$json['from'] = $answerObject->from;
		$json['to'] = $answerObject->to;
		$errorPresent = true;
	}	
	else
	{	
		$json['status'] = 1;
		
		//$jsonAnsw = json_decode($answer, true);
				//print_r ($jsonAnsw);
		// Запрос баланса и отправка СМС и почты, если меньше 20 рублей
		$answer = $zd->call('/v1/info/balance/');
		$answerObject = json_decode($answer);

		if ($answerObject->status == 'success') 
		{
			if ($answerObject->balance < 50)
			{
				// Отослать СМС и почту
				$money =  'Zadarma:Balance is ' . $answerObject->balance . ' ' . $answerObject->currency;
			}
		}
	}
	
	$headers .= "From: callback@stabilok.ru\r\n";
$headers .= "Reply-To: callback@stabilok.ru\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html;charset=utf-8 \r\n";
$msg .= "<html><body style='font-family:Arial,sans-serif;'>";
if ($test == true)
		$msg .= '<p style="color:red;font-size:140%;">ОТЛАДКА!</p><br>';
$msg .= "<h2 style='font-weight:bold;border-bottom:1px dotted #ccc;'>Заказан обратный звонок</h2>\r\n";
if ($json['status'] == -1)
	$msg .= '<p style="color:red;">Но произошла ошибка</p>';
$msg .= "<p><strong>Номер телефона:</strong> ".$phone."</p>\r\n";
$msg .= '<p style="color:green;">Status: ' .$json['status'].' ('.$statusSave.')</p>\r\n';
$msg .= '<br>Баланс Zadarma '.$answerObject->balance.'руб';
$msg .= "</body></html>";
@mail('info@stabilok.ru', $subject, $msg, $headers);
	
	echo json_encode($json); // вывoдим мaссив oтвeтa
	//die(); // умирaeм
?>