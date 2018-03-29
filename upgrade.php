<?php

require_once 'config.php';

function checkDir($dir)
{
  $res = "";
  if (!is_writable(dirname(__FILE__) . $dir))
    $res = "Нет прав записи в каталог $dir<br>\n";
  $dh = opendir(dirname(__FILE__) . $dir);
  while( $file = readdir($dh) )
  {
    if ( is_file(dirname(__FILE__) . $dir . $file) )
    {
      if (!is_writable(dirname(__FILE__) . $dir . $file))
        $res .= "Нет прав записи в файл $dir$file<br>\n";
    }
  }
  closedir($dh);
  return $res;
}

function check_cURL() {
    $res = "";
    if (!function_exists('curl_version')) {
        $res = "Не установлено расширение cURL.<br>\n";
    }
    return $res;
}

function checkInstall()
{
  $msg = checkDir('/');
  $msg .= checkDir('/pages/');
  $msg .= checkDir('/pages/img/');
  $msg .= checkDir('/products/');
  $msg .= checkDir('/products/html/');
  $msg .= checkDir('/products/img/');
  $msg .= check_cURL();

  if ( strlen($msg) > 0 )
  {
     print "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\">\n";
     print "<HTML><HEAD>\n";
     print "<TITLE>Ошибка конфигурации сайта</TITLE>\n";
     print "</HEAD><BODY>\n";
     print "<H1>Ошибка конфигурации сайта</H1>\n";
     print "<i>Внимание! В конфигурации сайта обнаружены критические ошибки. Работа сайта невозможна до устранения следующих проблем:</i>\n";
     print "<p>$msg\n";
     print "</BODY></HTML>\n";
     exit;
  }
}

function upgrade()
{
  global $BILLING_SERVER, $VERSION_INFO_FILE;

  
  if ((!file_exists($VERSION_INFO_FILE)) || (!is_writable($VERSION_INFO_FILE))) 
  {
    checkInstall();
  }

  $version = 0;
  if (file_exists($VERSION_INFO_FILE))
  {
    $fh = fopen($VERSION_INFO_FILE, 'r');
    $RawData = fread($fh, filesize($VERSION_INFO_FILE));
    fclose($fh);
    if (preg_match("/^\s*(\d+)/i", $RawData, $matches)) $version = $matches[1];
  }

  $status = ServerRequest('version=' . urlencode($version), $BILLING_SERVER, '/upgrade.php');

  if (preg_match("/NO_NEWER_VERSION/i", $status, $matches))
  {
    touch($VERSION_INFO_FILE);
    return;    
  }

  if (preg_match_all("/(\d+)\s*(\S*?)\s*([0-9a-f]*)\n/i", $status, $matches, PREG_SET_ORDER))
  {
    foreach ($matches as $upgrade)
    {
      $status = ServerRequest('version=' . urlencode($upgrade[1]), $BILLING_SERVER, '/getupdate.php');
      if ($fh = fopen(dirname(__FILE__) . '/' . $upgrade[2], "wb"))
      {
        fwrite($fh, $status);
        fclose($fh);
        if ($vh = fopen($VERSION_INFO_FILE, "wb"))
        {
          fwrite($vh, (string)$upgrade[1]);
          fclose($vh);
        } else {
          checkInstall();
          return;
        }
      } else {
        checkInstall();
        return;
      }
    }
  }

}

function update_xml()
{
    global $BILLING_SERVER;
    $xml = ServerRequest('', $BILLING_SERVER, '/getxml.php');

    if ($fh = fopen(dirname(__FILE__) . '/products/shop.xml', "wb"))
    {
        fwrite($fh, $xml);
        fclose($fh);
    } else {
        checkInstall();
        return;
    }
}

upgrade();
//echo '<br><div style="font-size:230%;">adr:'. $_SERVER['SERVER_NAME'].'</div>';
//exit;
//if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1')
	update_xml();

?>