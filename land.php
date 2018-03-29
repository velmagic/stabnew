<?php
require_once 'config.php';

if ((!isset($_GET['page'])) || (!preg_match('/^([0-9]|[a-zA-Z_-])*$/', $_GET['page'])) ||
    !file_exists( dirname(__FILE__) . '/custom/landings/' . $_GET['page'] . '/index.tmpl' ))
{
//echo $_GET['page'].'<br>';
//echo 'file '. dirname(__FILE__) . '/custom/landings/' . $_GET['page'] . '/index.html'. ' '.file_exists( dirname(__FILE__) . '/custom/landings/' . $_GET['page'] . '/index.html');
    echo '<div style="color:#a00;font-size:44px;margin:30px;">Нет страницы '.$_GET['page'].' </div>';//return; //redirect('/');
sleep(2);
	 redirect('http://best.stabilok.ru');
}

$text = file_get_contents(dirname(__FILE__) . '/custom/landings/' . $_GET['page'] . '/index.tmpl');

$text = processExtendedTags($text);

// Обрабатываем TMPL_INCLUDE в тексте страницы
while (preg_match("/<TMPL_INCLUDE\s*FILE=\"(.*?)\">/i", $text, $matches))
{
  $incl_content = file_exists(dirname(__FILE__) . $matches[1]) ? file_get_contents( dirname(__FILE__) . $matches[1] ) : '';
  $text = str_replace($matches[0], $incl_content, $text);
}

$tmpl = startTemplate('land.tmpl');
// Обрабатываем TMPL_EXPORT в тексте страницы
while (preg_match("/<TMPL_EXPORT\s*NAME=[\"'](.*?)[\"']\s*VALUE=[\"'](.*?)[\"']?>/i", $text, $export))
{
  $tmpl->setvar($export[1], $export[2]);
  $text = str_replace($export[0], '', $text);
}

// Обрабатываем TMPL_VAR в тексте страницы
while (preg_match("/<TMPL_VAR\s*NAME=[\"'](.*?)[\"']>/i", $text, $var))
{
  $text = str_replace($var[0],  $tmpl->getVar($var[1]), $text);
}

$tmpl->setvar('html', $text);
$tmpl->pparse();

?>