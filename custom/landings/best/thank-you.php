<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ru-RU">

<head>

<title>Stabilok</title>

<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale = 1.0, user-scalable = no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="designer" content="bablomag" />

<!-- Фавикон -->
<link rel="shortcut icon" href="/favicon.ico" >
<!--  --><link rel="icon" href="images/favicon.gif" type="image/gif" >

<!-- CSS -->
<link href="files/css/style.thank-you.css" rel="stylesheet" type="text/css" />
<!-- /CSS -->
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter39448085 = new Ya.Metrika({
                    id:39448085,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/39448085" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

</head>
<body>
<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-MS8DTB"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-MS8DTB');</script>
<!-- End Google Tag Manager -->

<!-- ======= Основа сайта ======= -->
<div id="main">

    
    <!-- ======= Картинка ======= -->
	<center class="zagolovok">«STABILOK»</center>
<?php
if(!isset($_GET['error']))
{	
	echo '<!-- ======= Заголовок ======= -->
    <div class="zagolovok">СПАСИБО<br />Ваша заявка будет рассмотрена в ближайшее время.</div>
    
    <!-- ======= Подзаголовок ======= -->
    <div class="podzagolovok">Очень скоро наш менеджер свяжется с Вами.</div>
	<div class="podzagolovok"><a href="/" onmouseover="this.style.cursor=&quot;pointer&quot; " onfocus="this.blur();" style="cursor: pointer;color: #fff;text-decoration: none;">Вернуться на сайт >>></a></div>';
}
else
{
	echo '<!-- ======= Заголовок ======= -->
    <div class="zagolovok_err">ОШИБКА<br />Произошел сбой заказа обратного звонка</div>
    
    <!-- ======= Подзаголовок ======= -->
    <div class="podzagolovok">Для совершения заказа позвоните по телефону 8(499)322-8089</div>
	<div class="podzagolovok"><a href="http://best.stabilok.ru" onmouseover="this.style.cursor=&quot;pointer&quot; " onfocus="this.blur();" style="cursor: pointer;color: #fff;text-decoration: none;">Вернуться на сайт >>></a></div>';
}
?>	
</div> <!-- ======= / Основа сайта ======= -->

</body>

</html>

