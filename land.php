<?php
require_once 'config.php';

if ((!isset($_GET['page'])) || (!preg_match('/^([0-9]|[a-zA-Z_-])*$/', $_GET['page'])) ||
    !file_exists( dirname(__FILE__) . '/custom/landings/' . $_GET['page'] . '/index.tmpl' ))
{
//echo $_GET['page'].'<br>';
//echo 'file '. dirname(__FILE__) . '/custom/landings/' . $_GET['page'] . '/index.html'. ' '.file_exists( dirname(__FILE__) . '/custom/landings/' . $_GET['page'] . '/index.html');
    echo '<div style="color:#a00;font-size:44px;margin:30px;">��� �������� '.$_GET['page'].' </div>';//return; //redirect('/');
sleep(2);
	 redirect('http://best.stabilok.ru');
}

$text = file_get_contents(dirname(__FILE__) . '/custom/landings/' . $_GET['page'] . '/index.tmpl');

$text = processExtendedTags($text);

// ������������ TMPL_INCLUDE � ������ ��������
while (preg_match("/<TMPL_INCLUDE\s*FILE=\"(.*?)\">/i", $text, $matches))
{
  $incl_content = file_exists(dirname(__FILE__) . $matches[1]) ? file_get_contents( dirname(__FILE__) . $matches[1] ) : '';
  $text = str_replace($matches[0], $incl_content, $text);
}

$tmpl = startTemplate('land.tmpl');
// ������������ TMPL_EXPORT � ������ ��������
while (preg_match("/<TMPL_EXPORT\s*NAME=[\"'](.*?)[\"']\s*VALUE=[\"'](.*?)[\"']?>/i", $text, $export))
{
  $tmpl->setvar($export[1], $export[2]);
  $text = str_replace($export[0], '', $text);
}

// ������������ TMPL_VAR � ������ ��������
while (preg_match("/<TMPL_VAR\s*NAME=[\"'](.*?)[\"']>/i", $text, $var))
{
  $text = str_replace($var[0],  $tmpl->getVar($var[1]), $text);
}

$tmpl->setvar('html', $text);
$tmpl->pparse();

?>