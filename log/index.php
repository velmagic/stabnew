<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#">
<head>
<meta http-equiv="content-type" content="text/html; charset=windows-1251" />
<meta http-equiv="Cache-Control" content="no-cache">
</head>
<body>

<?php
echo '
<style>
#wr0
{
	top: 20%; /* ������ � ��������� �� �������� ���� ���� */
	left: 50%; /* ������ � ��������� �� ������ ���� ���� */
	width: auto; /* ������ ����� */
	height: auto; /* ������ ����� */
	position: absolute; /* ���������� ���������������� ����� */
	margin-top: -225px; /* ������������� ������ �� �������� ���� ��������, ������ ��������� �������� ������ ����� �� ������ ����� */
	margin-left: -225px; /* ������������� ������ �� ������ ���� ��������, ������ ��������� �������� ������ ����� �� ������ ����� */
}
.wr
{
margin:100px 0 0 100px;
border:1px solid #000;
background-color:#feb;
padding: 20px 0;
display:inline-block;
}
.cl
{
	margin: 0 40px;
	font-size:18px;
	line-height:1.5em;
}
</style>
<div id="wr0">
<span class="wr">
		<div class="cl"><a href="cronFile.php">�������� �����</a></div>
		<div class="cl"><a href="cronFile.php?forum">�������� ������</a></div>
		<div class="cl"><a href="showlog.php">�������� ���� �����</a></div>
		<div class="cl"><a href="showlog.php?forum">�������� ���� ������</a></div>
		<div class="cl"><a href="open.php">������� ����</a></div>
		<div class="cl"><a href="close.php">������� ����</a></div>
		<div class="cl"><a href="balance.php">������</a></div>
		<br><div class="cl"><a href="send.php">Send SMS</a></div>
		</span></div>';
?>