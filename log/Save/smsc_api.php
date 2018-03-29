<?php
// SMSC.RU API (smsc.ru) ������ 3.0 (29.07.2014)

define("SMSC_LOGIN", "velmagic");		// ����� �������
define("SMSC_PASSWORD", "5a235b2b66cea0267720cdd09db98d01");	// ������ ��� MD5-��� ������ � ������ ��������
define("SMSC_POST", 0);					// ������������ ����� POST
define("SMSC_HTTPS", 0);				// ������������ HTTPS ��������
define("SMSC_CHARSET", "windows-1251");	// ��������� ���������: utf-8, koi8-r ��� windows-1251 (�� ���������)
define("SMSC_DEBUG", 0);				// ���� �������
define("SMTP_FROM", "api@smsc.ru");     // e-mail ����� �����������

// ������� �������� SMS
//
// ������������ ���������:
//
// $phones - ������ ��������� ����� ������� ��� ����� � �������
// $message - ������������ ���������
//
// �������������� ���������:
//
// $translit - ���������� ��� ��� � �������� (1,2 ��� 0)
// $time - ����������� ����� �������� � ���� ������ (DDMMYYhhmm, h1-h2, 0ts, +m)
// $id - ������������� ���������. ������������ ����� 32-������ ����� � ��������� �� 1 �� 2147483647.
// $format - ������ ��������� (0 - ������� sms, 1 - flash-sms, 2 - wap-push, 3 - hlr, 4 - bin, 5 - bin-hex, 6 - ping-sms, 7 - mms)
// $sender - ��� ����������� (Sender ID). ��� ���������� Sender ID �� ��������� ���������� � �������� �����
// �������� ������ ������ ��� �����.
// $query - ������ �������������� ����������, ����������� � URL-������ ("valid=01:00&maxsms=3&tz=2")
// $files - ������ ����� � ������ ��� �������� mms-���������
//
// ���������� ������ (<id>, <���������� sms>, <���������>, <������>) � ������ �������� ��������
// ���� ������ (<id>, -<��� ������>) � ������ ������

function send_sms($phones, $message, $translit = 0, $time = 0, $id = 0, $format = 0, $sender = false, $query = "", $files = array())
{
	static $formats = array(1 => "flash=1", "push=1", "hlr=1", "bin=1", "bin=2", "ping=1", "mms=1");

	$m = _smsc_send_cmd("send", "cost=3&phones=".urlencode($phones)."&mes=".urlencode($message).
					"&translit=$translit&id=$id".($format > 0 ? "&".$formats[$format] : "").
					($sender === false ? "" : "&sender=".urlencode($sender)).
					($time ? "&time=".urlencode($time) : "").($query ? "&$query" : ""), $files);

	// (id, cnt, cost, balance) ��� (id, -error)

	if (SMSC_DEBUG) {
		if ($m[1] > 0)
			echo "��������� ���������� �������. ID: $m[0], ����� SMS: $m[1], ���������: $m[2], ������: $m[3].\n";
		else 
			echo "������ �", -$m[1], $m[0] ? ", ID: ".$m[0] : "", "\n";
	}

	return $m;
}

// SMTP ������ ������� �������� SMS

function send_sms_mail($phones, $message, $translit = 0, $time = 0, $id = 0, $format = 0, $sender = "")
{
	return mail("send@send.smsc.ru", "", SMSC_LOGIN.":".SMSC_PASSWORD.":$id:$time:$translit,$format,$sender:$phones:$message", "From: ".SMTP_FROM."\nContent-Type: text/plain; charset=".SMSC_CHARSET."\n");
}

// ������� ��������� ��������� SMS
//
// ������������ ���������:
//
// $phones - ������ ��������� ����� ������� ��� ����� � �������
// $message - ������������ ��������� 
//
// �������������� ���������:
//
// $translit - ���������� ��� ��� � �������� (1,2 ��� 0)
// $format - ������ ��������� (0 - ������� sms, 1 - flash-sms, 2 - wap-push, 3 - hlr, 4 - bin, 5 - bin-hex, 6 - ping-sms, 7 - mms)
// $sender - ��� ����������� (Sender ID)
// $query - ������ �������������� ����������, ����������� � URL-������ ("list=79999999999:��� ������: 123\n78888888888:��� ������: 456")
//
// ���������� ������ (<���������>, <���������� sms>) ���� ������ (0, -<��� ������>) � ������ ������

function get_sms_cost($phones, $message, $translit = 0, $format = 0, $sender = false, $query = "")
{
	static $formats = array(1 => "flash=1", "push=1", "hlr=1", "bin=1", "bin=2", "ping=1", "mms=1");

	$m = _smsc_send_cmd("send", "cost=1&phones=".urlencode($phones)."&mes=".urlencode($message).
					($sender === false ? "" : "&sender=".urlencode($sender)).
					"&translit=$translit".($format > 0 ? "&".$formats[$format] : "").($query ? "&$query" : ""));

	// (cost, cnt) ��� (0, -error)

	if (SMSC_DEBUG) {
		if ($m[1] > 0)
			echo "��������� ��������: $m[0]. ����� SMS: $m[1]\n";
		else
			echo "������ �", -$m[1], "\n";
	}

	return $m;
}

// ������� �������� ������� ������������� SMS ��� HLR-�������
//
// $id - ID c��������
// $phone - ����� ��������
// $all - ������� ��� ������ ������������� SMS, ������� ����� ��������� (0 ��� 1)
//
// ���������� ������:
//
// ��� SMS-���������:
// (<������>, <����� ���������>, <��� ������ ��������>)
//
// ��� HLR-�������:
// (<������>, <����� ���������>, <��� ������ sms>, <��� IMSI SIM-�����>, <����� ������-������>, <��� ������ �����������>, <��� ���������>,
// <�������� ������ �����������>, <�������� ���������>, <�������� ����������� ������>, <�������� ������������ ���������>)
//
// ��� $all = 1 ������������� ������������ �������� � ����� �������:
// (<����� ��������>, <����� ��������>, <���������>, <sender id>, <�������� �������>, <����� ���������>)
//
// ���� ������ (0, -<��� ������>) � ������ ������

function get_status($id, $phone, $all = 0)
{
	$m = _smsc_send_cmd("status", "phone=".urlencode($phone)."&id=".$id."&all=".(int)$all);

	// (status, time, err, ...) ��� (0, -error)

	if (SMSC_DEBUG) {
		if ($m[1] != "" && $m[1] >= 0)
			echo "������ SMS = $m[0]", $m[1] ? ", ����� ��������� ������� - ".date("d.m.Y H:i:s", $m[1]) : "", "\n";
		else
			echo "������ �", -$m[1], "\n";
	}

	if ($all && count($m) > 9 && (!isset($m[14]) || $m[14] != "HLR")) // ',' � ���������
		$m = explode(",", implode(",", $m), 9);

	return $m;
}

// ������� ��������� �������
//
// ��� ����������
//
// ���������� ������ � ���� ������ ��� false � ������ ������

function get_balance()
{
	$m = _smsc_send_cmd("balance"); // (balance) ��� (0, -error)

	if (SMSC_DEBUG) {
		if (!isset($m[1]))
			echo "����� �� �����: ", $m[0], "\n";
		else
			echo "������ �", -$m[1], "\n";
	}

	return isset($m[1]) ? false : $m[0];
}


// ���������� �������

// ������� ������ �������. ��������� URL � ������ 3 ������� ������

function _smsc_send_cmd($cmd, $arg = "", $files = array())
{
	$url = (SMSC_HTTPS ? "https" : "http")."://smsc.ru/sys/$cmd.php?login=".urlencode(SMSC_LOGIN)."&psw=".urlencode(SMSC_PASSWORD)."&fmt=1&charset=".SMSC_CHARSET."&".$arg;

	$i = 0;
	do {
		if ($i) {
			sleep(2);

			if ($i == 2)
				$url = str_replace('://smsc.ru/', '://www2.smsc.ru/', $url);
		}

		$ret = _smsc_read_url($url, $files);
	}
	while ($ret == "" && ++$i < 3);

	if ($ret == "") {
		if (SMSC_DEBUG)
			echo "������ ������ ������: $url\n";

		$ret = ","; // ��������� �����
	}

	return explode(",", $ret);
}

// ������� ������ URL. ��� ������ ������ ���� ��������:
// curl ��� fsockopen (������ http) ��� �������� ����� allow_url_fopen ��� file_get_contents

function _smsc_read_url($url, $files)
{
	$ret = "";
	$post = SMSC_POST || strlen($url) > 2000;

	if (function_exists("curl_init"))
	{
		static $c = 0; // keepalive

		if (!$c) {
			$c = curl_init();
			curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 10);
			curl_setopt($c, CURLOPT_TIMEOUT, 60);
			curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
		}

		if ($post || $files)
		{
			list($url, $post) = explode("?", $url, 2);
			curl_setopt($c, CURLOPT_POST, true);

			if ($files) {
				parse_str($post, $m);
				$post = $m;
				foreach ($files as $i => $path)
					$post["file".$i] = "@".$path;
			}

			curl_setopt($c, CURLOPT_POSTFIELDS, $post);
		}

		curl_setopt($c, CURLOPT_URL, $url);

		$ret = curl_exec($c);
	}
	elseif ($files) {
		if (SMSC_DEBUG)
			echo "�� ���������� ������ curl ��� �������� ������\n";
	}
	else {
		if (!SMSC_HTTPS && function_exists("fsockopen"))
		{
			$m = parse_url($url);

			if (!$fp = fsockopen($m["host"], 80, $errno, $errstr, 10))
				$fp = fsockopen("212.24.33.196", 80, $errno, $errstr, 10);

			if ($fp) {
				fwrite($fp, ($post ? "POST $m[path]" : "GET $m[path]?$m[query]")." HTTP/1.1\r\nHost: smsc.ru\r\nUser-Agent: PHP".($post ? "\r\nContent-Type: application/x-www-form-urlencoded\r\nContent-Length: ".strlen($m['query']) : "")."\r\nConnection: Close\r\n\r\n".($post ? $m['query'] : ""));

				while (!feof($fp))
					$ret .= fgets($fp, 1024);
				list(, $ret) = explode("\r\n\r\n", $ret, 2);

				fclose($fp);
			}
		}
		else
			$ret = file_get_contents($url);
	}

	return $ret;
}

// Examples:
// include "smsc_api.php";
// list($sms_id, $sms_cnt, $cost, $balance) = send_sms("79999999999", "��� ������: 123", 1);
// list($sms_id, $sms_cnt, $cost, $balance) = send_sms("79999999999", "http://smsc.ru\nSMSC.RU", 0, 0, 0, 0, false, "maxsms=3");
// list($sms_id, $sms_cnt, $cost, $balance) = send_sms("79999999999", "0605040B8423F0DC0601AE02056A0045C60C036D79736974652E72750001036D7973697465000101", 0, 0, 0, 5, false);
// list($sms_id, $sms_cnt, $cost, $balance) = send_sms("79999999999", "", 0, 0, 0, 3, false);
// list($cost, $sms_cnt) = get_sms_cost("79999999999", "�� ������� ����������������!");
// send_sms_mail("79999999999", "��� ������: 123", 0, "0101121000");
// list($status, $time) = get_status($sms_id, "79999999999");
// $balance = get_balance();

?>
