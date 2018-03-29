<?php
	require_once 'Client.php';
	
	$errorPresent = false;
	$json = array();
	$phone = $_POST['to'];
									// мой тел
	$callParam = array('from'=>'+74993228089','to' =>$phone);
	
	$zd = new \Zadarma_API\Client('b2e1f6eebc034ac5b0f5', '2bd32cf17cb44fafbf04',false);
	$answer = $zd->call('/v1/request/callback/', $callParam);
	
// Статистика по месяцам

	$json['phone'] = $phone;
	$answerObject = json_decode($answer);
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
	echo json_encode($json); // вывoдим мaссив oтвeтa
	//die(); // умирaeм
?>