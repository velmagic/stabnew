 <?php 
 $phones[] = array('name'=>'����', 'phone'=>'+79031311168');
 $phones[] = array('name'=>'������', 'phone'=>'+79032195491');
 $phones[] = array('name'=>'���������', 'phone'=>'+79031017441');
 $phones[] = array('name'=>'�', 'phone'=>'+79851246271');
 
 $error='';
if ($_POST["send"]) 
{ 
	$pref = __DIR__.'/../';
	include_once($pref."log/smsc_api.php");
	$phone='';
	$sender='';
	if ($_POST['name'] != '')
	{
		if ($_POST['theinput'] == '����')
			$phone = '+79031311168';
		else if ($_POST['theinput'] == '������')
			$phone = '+79032195491';
		else if ($_POST['theinput'] == '�')
			$phone = '+79851246271';
		else if ($_POST['theinput'] == '���������')
			$phone = '+79031017441';
	}
	else
	{
		if (isset($_POST['theinput']) && $_POST['theinput'] != '')
			$phone = $_POST['theinput'];
		else
		{
			$error = '<script>alert("����� �������� ���?");</script>';
			ShowForm();
			return;
		}	
	}		
	
	if (isset($_POST['sender']))
		$sender = $_POST['sender'];
	else
		$sender = 'jar-magic';
	
	if ($_POST['message'] == '')
	{
		$error = '<script>alert("������ ���������?");</script>';
		ShowForm();
		return;
	}
    $r = send_sms($phone, $_POST["message"], 
                $_POST["translit"], $_POST["time"], 0, 
                $_POST["flash"], $sender); 

    // $r = array(<id>, <���������� sms>, <���������>, <������>) ��� array(<id>, -<��� ������>) 
	 $ok = 0;
	 $cnt=0;
	 for (;$cnt<count($phones);++$cnt)
		if ($phones[$cnt]['phone'] == $phone)
		{
			$ok=1;
			break;
		}	
	$nam='';
	if (ok)		
		$nam = '('.$phones[$cnt]['name'].')';
   if ($r[1] > 0) 
        echo '��������� from '.$_POST["sender"].' to '.$phone. $nam.' ����������. ������ '.$r[3]; 
   else 
        echo "��������� ������ � ", -$r[1]; 
		  
	echo '<div style="height:20px;"></div>';	  
	echo '<a href="index.php"><input type="button" value="�������"></a> &nbsp;&nbsp;&nbsp;&nbsp; 
			<a href="send.php"><input type="button" value="���������"></a>';
} 
else
	ShowForm();
	
function ShowForm()	
{ global $error, $phones;
	echo '<!DOCTYPE html><html>
	<style>
	.clg, table.clg td, table.clg th { 
border: 1px solid #AADDFF;
border-collapse: collapse;
border-spacing: 0px;
}
������� ������ user agent
table { 
border-collapse: separate;
border-spacing: 2px;
display: table;
font-size: use-lang-def;
font-style: normal;
font-weight: 400;
letter-spacing: normal;
text-align: default;
text-indent: 0px;
white-space: normal;
width: 100%;
word-spacing: normal;
 }
 .hd, .hd a { 
color: #2277AA;
font-weight: 700;
}
main_v9.css:31
.hd { 
background-color: #D0E0F0;
}
 </style>
 <BODY onLoad="init(\'thelist\', \'theinput\')">';
 if ($error != '')
	echo $error;
	
echo '<br><form method="post" action="send.php">

������� <input type="text" id="theinput" name="theinput" /> &nbsp; ��� ������� 

<select style="padding:6px;" name="thelist" id="thelist" onChange="combo(this, \'theinput\')" onkeypress="combo(this, \'theinput\')">';
	for ($i = 0;$i < count($phones);++$i)
		echo '<option>'.$phones[$i]['name'].'</option>';
/*  <option>����</option>
  <option>������</option>
  <option>�</option>
  <option>���������</option>
*/
echo '</select> <br>
���������<br/><textarea name="message" rows="4" cols="80"></textarea><br/><br/>
����������<br/> <!-- <input name="sender" value="jar-magic"><br/><br/> -->
   <input type="radio" name="sender" value="jar-magic">jar-magic<Br>
   <input type="radio" name="sender" value="Stanislav">Stanislav<br>
   <input type="radio" name="sender" value="CTAC">CTAC</p>

�������� ��<br/><input name="time" value="" > (��.��.�� ��:��)<br/><br/>

<input type="checkbox" name="translit">��������
<input type="checkbox" name="flash"> Flash SMS<br/><br/>
<input type="submit" name="send" value="���������">
</form>


N � ����� ������, ����� ��������� ��������� ��������:<br><br>

<table width=100% class=clg cellpadding=3>
<tr class=hd><th width=20%>��������<th>��������
<tr><th>1<td>������ � ����������.
<tr><th>2<td>�������� ����� ��� ������.
<tr><th>3<td>������������ ������� �� ����� �������.
<tr><th>4<td>IP-����� �������� ������������ ��-�� ������ ������ � ��������. <a href=/faq/#a99>���������</a>
<tr><th>5<td>�������� ������ ����.
<tr><th>6<td>��������� ��������� (�� ������ ��� �� ����� �����������).
<tr><th>7<td>�������� ������ ������ ��������.
<tr><th>8<td>��������� �� ��������� ����� �� ����� ���� ����������.
<tr><th>9<td>�������� ����� ������ ����������� ������� �� �������� SMS-���������
���� ����� ���� ���������� �������� �� ��������� ��������� ��������� � ������� ������.
</table>

</body>
<script>
function init(thelist, theinput)
{
  thelist = document.getElementById(thelist);  
	combo(thelist, theinput);
}

function combo(thelist, theinput)
{
people = new Array( ';
	$cnt = 0;
	foreach ($phones as $key=>$val)
	{
		if ($cnt++ != 0)
			echo (',');
		$cnt1 = 0;
		echo '{';
		foreach ($val as $k=>$v)
		{
			echo "\"$k\":\"$v\"";
			if ($cnt1++ == 0)
				echo ',';
		}
		echo '}';

	}
	/*{attr1":"text1","attr2":"text2"},
	{"attr1":"text3","attr2":"text4"}*/
	echo ');
	//alert("dfdfd");
/*	for (i = 0;i<people.length;i++)
		for (var key in people[i]) 
	    alert(key+":"+people[i][key]);*/
  theinput = document.getElementById(theinput);  
  var idx = thelist.selectedIndex;
  var content = people[idx].phone; //thelist.options[idx].innerHTML;
  theinput.value = content;	
}
</script>
</html> ';
}
?> 