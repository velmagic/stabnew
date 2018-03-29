<?php

require_once 'config.php';

if (!isset($_REQUEST['cat'])) {
  if (function_exists('onCategoryInvalid'))
    call_user_func('onCategoryInvalid', '');
  redirect('/');
}

$cat = $_REQUEST['cat'];

$nav = array(
		'stab' => array(
				'xpath' => "//product[category='Стабилизатор напряжения']",
				'caption' => "Стабилизаторы напряжения",
				'title' => "Стабилизаторы напряжения | Интернет магазин электрооборудования",
				'keywords' => "стаблизатор напряжения",
				'description' => ""
				),
		'stab1' => array(
				'xpath' => "//product[category='Стабилизатор напряжения' and phases=1]",
				'caption' => "Однофазные стабилизаторы напряжения",
				'title' => "Однофазные стабилизаторы напряжения | Интернет магазин электрооборудования",
				'keywords' => "однофазный стаблизатор напряжения",
				'description' => ""
				),
		'stab3' => array(
				'xpath' => "//product[category='Стабилизатор напряжения' and phases=3]",
				'caption' => "Трехфазные стабилизаторы напряжения",
				'title' => "Трехфазные стабилизаторы напряжения | Интернет магазин электрооборудования",
				'keywords' => "трехфазный стабилизатор напряжения",
				'description' => ""
				),
		'converter' => array(
				'xpath' => "//product[category='Инвертор' or category='Аккумулятор']",
				'caption' => "Преобразователи напряжения",
				'title' => "Инверторы, преобразователи напряжения, ИБП, аккумуляторы | Интернет магазин электрооборудования",
				'keywords' => "",
				'description' => ""
				),
		'invertor' => array(
				'xpath' => "//product[category='Инвертор']",
				'caption' => "Инверторы (преобразователи напряжения)",
				'title' => "Инверторы (преобразователи напряжения) | Интернет магазин электрооборудования",
				'keywords' => "",
				'description' => ""
				),
		'snvt' => array(
				'xpath' => "//product[category='Стабилизатор напряжения' and phases=1 and series='CHBT']",
				'caption' => "Стабилизаторы напряжения Энергия СНВТ/1",
				'title' => "Стабилизаторы напряжения Энергия СНВТ/1 | Интернет магазин электрооборудования",
				'keywords' => "стабилизаторы, энергия, снвт, hybrid",
				'description' => "Однофазные стабилизаторы напряжения Энергия СНВТ/1 на 220В"
				),
		'voltron' => array(
				'xpath' => "//product[category='Стабилизатор напряжения' and phases=1 and series='PCH']",
				'caption' => "Стабилизаторы напряжения Voltron РСН",
				'title' => "Стабилизаторы напряжения Voltron РСН | Интернет магазин электрооборудования",
				'keywords' => "стабилизаторы, энергия, voltron, рсн",
				'description' => "Однофазные стабилизаторы напряжения Voltron РСН на 220В"
				),
		'ach' => array(
				'xpath' => "//product[category='Стабилизатор напряжения' and phases=1 and series='ACH']",
				'caption' => "Стабилизаторы напряжения Энергия АСН",
				'title' => "Стабилизаторы напряжения Энергия АСН | Интернет магазин электрооборудования",
				'keywords' => "стабилизаторы, энергия, ach, асн",
				'description' => "Однофазные стабилизаторы напряжения Энергия АСН на 220В"
				),
		'classic-ultra' => array(
				'xpath' => "//product[category='Стабилизатор напряжения' and phases=1 and (series='CLASSIC' or series='ULTRA')]",
				'caption' => "Стабилизаторы напряжения Classic и Ultra",
				'title' => "Стабилизаторы напряжения Classic и Ultra | Интернет магазин электрооборудования",
				'keywords' => "стабилизаторы, энергия, classic, ultra",
				'description' => "Однофазные стабилизаторы напряжения Classic и Ultra на 220В"
				),
		'apc' => array(
				'xpath' => "//product[category='Стабилизатор напряжения' and phases=1 and series='APC']",
				'caption' => "Стабилизаторы для газовых котлов Энергия АРС",
				'title' => "Стабилизаторы для газовых котлов Энергия АРС | Интернет магазин электрооборудования",
				'keywords' => "стабилизаторы, энергия, apc, арс",
				'description' => "Стабилизаторы для газовых котлов Энергия АРС"
				),
		'snvt-3' => array(
				'xpath' => "//product[category='Стабилизатор напряжения' and phases=3 and series='CHBT']",
				'caption' => "Стабилизаторы напряжения Энергия СНВТ/3",
				'title' => "Стабилизаторы напряжения Энергия СНВТ/3 | Интернет магазин электрооборудования",
				'keywords' => "стабилизаторы, энергия, снвт, hybrid",
				'description' => "Трехфазные стабилизаторы напряжения Энергия СНВТ/3 на 380В"
				),
		'voltron-3d' => array(
				'xpath' => "//product[category='Стабилизатор напряжения' and phases=3 and series='3D']",
				'caption' => "Трехфазные стабилизаторы напряжения Voltron 3D",
				'title' => "Трехфазные стабилизаторы напряжения Voltron 3D | Интернет магазин электрооборудования",
				'keywords' => "стабилизаторы, энергия, voltron, 3d, трехфазные",
				'description' => "Трехфазные стабилизаторы напряжения Voltron 3D на 380В"
				),
		'pn' => array(
				'xpath' => "//product[category='Инвертор' and series='PN']",
				'caption' => "Инверторы (преобразователи напряжения) Энергия ПН",
				'title' => "Инверторы (преобразователи напряжения) Энергия ПН | Интернет магазин электрооборудования",
				'keywords' => "инвертор, преобразователь напряжения, энергия, пн",
				'description' => "Инверторы (преобразователи напряжения) Энергия ПН для построения систем бесперебойного питания"
				),
		'battery' => array(
				'xpath' => "//product[category='Аккумулятор']",
				'caption' => "Аккумуляторы для инверторов и ИБП",
				'title' => "Аккумуляторные батареи (АКБ) для инверторов и ИБП | Интернет магазин электрооборудования",
				'keywords' => "АКБ, аккумулятор, инвертор, энергия",
				'description' => "Аккумуляторные батареи (АКБ) для инверторов и ИБП"
				),
		'rack' => array(
				'xpath' => "//product[category='Стойка коммутационная']",
				'caption' => "Стойки коммутационные для стабилизаторов",
				'title' => "Стойки коммутационные для стабилизаторов",
				'keywords' => "стойка, стаилизатор, стабилизатор напряжения, трехфазный, трехфазная сеть, трехфазный стабилизатор напряжения",
				'description' => "Стойки коммутационные для подключения стабилизаторов напряжения к трехфазной сети"
				),
		'welding' => array(
				'xpath' => "//product[category='Сварочный аппарат']",
				'caption' => "Сварочные аппараты (сварочные инверторы)",
				'title' => "Сварочные аппараты (сварочные инверторы)",
				'keywords' => "сварка, сварочный аппарат, сварочный инвертор",
				'description' => "Инверторые сварочные аппараты (сварочные инверторы) бытовые для сварки металлов"
				),
		'energy-sai' => array(
				'xpath' => "//product[category='Сварочный аппарат' and series='ENERGY-SAI']",
				'caption' => "Сварочные аппараты (сварочные инверторы) Энергия САИ",
				'title' => "Сварочные аппараты (сварочные инверторы) Энергия САИ",
				'keywords' => "сварка, сварочный аппарат, сварочный инвертор, Энергия, Энергия САИ",
				'description' => "Инверторые сварочные аппараты (сварочные инверторы) Энергия САИ - бытовые для сварки металлов"
				),
		'lux' => array(
				'xpath' => "//product[category='Стабилизатор напряжения' and phases=1 and series='LUX']",
				'caption' => "Стабилизаторы с сетевым фильтром Энергия Люкс",
				'title' => "Стабилизаторы напряжения со встроенным сетевым фильтром Энергия Люкс | Интернет магазин электрооборудования",
				'keywords' => "стабилизаторы, энергия, люкс",
				'description' => "Стабилизаторы напряжения Энергия Люкс"
				),
            );

if (file_exists(dirname(__FILE__) . '/custom/custom_cat.php')) {
  include dirname(__FILE__) . '/custom/custom_cat.php';
}

if (!isset($nav[$cat])) {
  if (function_exists('onCategoryInvalid'))
    call_user_func('onCategoryInvalid', $cat);
  redirect('/');
}

$CAT_TMPL_DIR = dirname(__FILE__) . '/custom/cat/';

$products = getProductsArray($nav[$cat]['xpath']);

$tmpl = startTemplate('cat.tmpl');
$tmpl->setLoop('products', $products);
foreach ($nav[$cat] as $key => $value)
{
    if ($key == 'include') {
        foreach ($value as $tmpl_key => $tmpl_value) {
            $tmpl_file = $CAT_TMPL_DIR . $tmpl_value;
            if (file_exists($tmpl_file)) {
                $tmpl_content = file_get_contents($tmpl_file);
                $tmpl->setVar($tmpl_key, $tmpl_content);
            }
        }
    } else {
        $tmpl->setVar($key, $value);
    }
}
$tmpl->pparse();

?>