<?php
define("FUNC_FILE", true);
if ($_POST['engine'])
{
	$pref = __DIR__.'/../';
	include($pref."config/config.php");

	mysql_connect($dbhost,$dbuname,$dbpass) or
	die("mysql_connect:�� �������������� :".mysql_error());
  
	if (!mysql_select_db ($dbname))
	{
		echo '������ mysql_select_db - '.mysql_error().'\n';
		exit;
	}
		if ($_POST['engine'] == 'as')
			$num = 1;
		else
			$num = 2;
	$res = mysql_query("UPDATE `".$prefix."_close` SET `closed`=1 WHERE `id`=".$num);
	if (!$res)
	{
		echo '������ ���������� ������� close - '.mysql_error().'\n';
		exit;
	}
	else
	{
		if ($num == 1)
			$name = '����';
		else	
			$name = '�����';
		echo '<div style="color:red;margin:100px 0 0 100px;font-size:18px;">'.$name.' ������</div>';
	}	
	mysql_close();	
}
else
{

echo '<html>
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
margin:100px 0 0 50px;
border:1px solid #000;
background-color:#feb;
padding: 20px 120px;
display:inline-block;
}
.cl
{
	margin: 0 40px;
	font-size:18px;
	line-height:1.5em;
}
p {padding:0;}
</style>
<div id="wr0">
<span class="wr">
<form name="test" method="post" action="close.php">
  <p><b>����������:</b></p>
   <span style="color:#0c8"><input type="radio" name="engine" value="as"> ���� &nbsp;</span>
   <span style="color:#b09"><input type="radio" name="engine" value="smf"> �����
  </p>

  <p><input type="submit" value="���������">
 </form>

</body>
</html> </span></div>';
}
