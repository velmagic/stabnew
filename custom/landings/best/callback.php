<?php
	require_once 'Client.php';
	
	$test = false;
	$errorPresent = false;
	$json = array();
	$phone = $_POST['to'];
	$json['phone'] = $phone;
									// ��� ���
	$callParam = array('from'=>'+74993228089','to' =>$phone);
	
	$zd = new \Zadarma_API\Client('b2e1f6eebc034ac5b0f5', '2bd32cf17cb44fafbf04',false);
	if ($test == false)
	{	
		$answer = $zd->call('/v1/request/callback/', $callParam);
		$answerObject = json_decode($answer);
	}
	else
		$answerObject->status = 'success';
	
// ���������� �� �������
	
	$statusSave = $answerObject->status;
	if ($answerObject->status != 'success')  // Error
	{
		// ��������� �� ������
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
		// ������ ������� � �������� ��� � �����, ���� ������ 20 ������
		$answer = $zd->call('/v1/info/balance/');
		$answerObject = json_decode($answer);

		if ($answerObject->status == 'success') 
		{
			if ($answerObject->balance < 50)
			{
				// �������� ��� � �����
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
		$msg .= '<p style="color:red;font-size:140%;">�������!</p><br>';
$msg .= "<h2 style='font-weight:bold;border-bottom:1px dotted #ccc;'>������� �������� ������</h2>\r\n";
if ($json['status'] == -1)
	$msg .= '<p style="color:red;">�� ��������� ������</p>';
$msg .= "<p><strong>����� ��������:</strong> ".$phone."</p>\r\n";
$msg .= '<p style="color:green;">Status: ' .$json['status'].' ('.$statusSave.')</p>\r\n';
$msg .= '<br>������ Zadarma '.$answerObject->balance.'���';
$msg .= "</body></html>";
@mail('info@stabilok.ru', $subject, $msg, $headers);
	
	echo json_encode($json); // ���o��� �a���� o��e�a
	//die(); // ����ae�
?>