<?php
//   ������ �� ������������


$sendto = 'info@stabilok.ru';
$usermail = 'info@stabilok.ru'; 
$username = $_POST['yourname'];
$phn = $_POST['phn'];
$prod_code = $_POST['prod-code'];
?>
<html>
<head>
<script type="text/javascript" src="/custom/landings/best/files/js/jquery.min.js"></script>
<script type="text/javascript" src="/custom/landings/best/files/js/jquery-ui.min.js"></script>
</head>
<body>
<!--<div id="gg"> ���������</div> -->
<script type="text/javascript">

$("#gg").hide();
$(document).ready(function(){
$.ajax({
	url: '/ajax-oneclick-order.php',
	type: 'POST',
	dataType: 'json',
	cache: false,
	data: {
		'prod_code': '<?php echo $prod_code; ?>',
		'prod_quantity': '1',
		'fname': '<?php echo $username; ?>',
		'phone': '<?php echo $phn;?>'
	},
	success: function(data) {
		if (data.result == 1) {

			var url = window.location.protocol+'//'+window.location.hostname+'/thank-you02.php?phn=<?php echo $phn;?>&name=<?php echo iconv('windows-1251','UTF-8',$username);?>&prod_code=<?php echo $prod_code;?>';
			window.location.href = url;
		}
		else
		{
			var url = window.location.protocol+'//'+window.location.hostname+'/thank-you02.php?error="Server error"&phn=<?php echo $phn;?>&name=<?php echo iconv('windows-1251','UTF-8',$username);?>&prod_code=<?php echo $prod_code;?>';
			window.location.href = url;
		}			
	},
	error:function( XHR, textStatus, errorThrown )
	{
		//alert("textStatus="+textStatus + ' ' + 'errorThrown='+errorThrown);
			var url = window.location.protocol+'//'+window.location.hostname+'/thank-you02.php?error="ajax error"';
			window.location.href = url;
	}
});
});
/*
$.ajax({
	url: 'http://stabilok/ajax-oneclick-order.php',
	type: 'POST',
	dataType: 'json',
	cache: false,
	data: {
		'prod_code: ������������',
		'prod_quantity: 0',
		'fname: papa',
		'phone: 1234567890'
	},
	success: function(data) {
					alert('ok');
		if (data.result == 1) {

			var url = window.location.protocol+'//'+window.location.hostname+'/thank-you02.php?phn';
			window.location.href = url;
		}
	},
	error:function( XHR, textStatus, errorThrown )
	{
		alert('error');
			var url = window.location.protocol+'//'+window.location.hostname+'/thank-you02.php?error';
			window.location.href = url;
	}
});*/
</script>

</body>
