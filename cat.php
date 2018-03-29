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
				'xpath' => "//product[category='Стабилизатор напряжения' and phases=1 and (series='CLASSIC' or series='ULTRA' or series='PREMIUM')]",
				'caption' => "Стабилизаторы напряжения Classic, Ultra, Premium",
				'title' => "Стабилизаторы напряжения Classic, Ultra, Premium | Интернет магазин электрооборудования",
				'keywords' => "стабилизаторы, энергия, classic, ultra, premium",
				'description' => "Однофазные стабилизаторы напряжения Classic, Ultra и Premium на 220В"
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
		'units' => array(
				'xpath' => "//product[category='Блок' or category='Модуль']",
				'caption' => "Блоки контроля и защиты",
				'title' => "Блоки контроля и защиты | Интернет магазин электрооборудования",
				'keywords' => "блоки контроля и защиты",
				'description' => "Блоки контроля и защиты",
				),
		'other' => array(
				'xpath' => "//product[category='Блок' or category='Модуль' or category='Стойка коммутационная']",
				'caption' => "Монтаж и защита",
				'title' => "Монтаж и защита | Интернет магазин электрооборудования",
				'keywords' => "Монтаж и защита",
				'description' => "Монтаж и защита",
				),
		'rucelf-sd' => array(
				'xpath' => "//product[category='Стабилизатор напряжения' and trademark='Rucelf' and (series='SDF' or series='SDW' or series='SDV')]",
				'caption' => "Стабилизаторы Rucelf SDF/SDW/SDV",
				'title' => "Стабилизаторы Rucelf SDF/SDW/SDV | Интернет магазин электрооборудования",
				'keywords' => "Стабилизаторы Rucelf SDF/SDW/SDV",
				'description' => "Стабилизаторы Rucelf SDF/SDW/SDV",
				),
		'rucelf-sr' => array(
				'xpath' => "//product[category='Стабилизатор напряжения' and trademark='Rucelf' and (series='SRF' or series='SRW')]",
				'caption' => "Стабилизаторы Rucelf SRF/SRW",
				'title' => "Стабилизаторы Rucelf SRF/SRW | Интернет магазин электрооборудования",
				'keywords' => "Стабилизаторы Rucelf SRF/SRW",
				'description' => "Стабилизаторы Rucelf SRF/SRW",
				),
		'star' => array(
				'xpath' => "//product[category='Стабилизатор напряжения' and trademark='Rucelf' and series='STAR']",
				'caption' => "Стабилизаторы Rucelf СтАР",
				'title' => "Стабилизаторы Rucelf СтАР | Интернет магазин электрооборудования",
				'keywords' => "Стабилизаторы Rucelf СтАР",
				'description' => "Стабилизаторы Rucelf СтАР",
				),
		'kotel' => array(
				'xpath' => "//product[category='Стабилизатор напряжения' and trademark='Rucelf' and series='KOTEL']",
				'caption' => "Стабилизаторы Rucelf КОТЁЛ",
				'title' => "Стабилизаторы Rucelf КОТЁЛ | Интернет магазин электрооборудования",
				'keywords' => "Стабилизаторы Rucelf КОТЁЛ",
				'description' => "Стабилизаторы Rucelf КОТЁЛ",
				),
		'boiler' => array(
				'xpath' => "//product[category='Стабилизатор напряжения' and phases=1 and (series='APC' or series='KOTEL')]",
				'caption' => "Специализированные стабилизаторы для газовых котлов",
				'title' => "Специализированные стабилизаторы для газовых | Интернет магазин электрооборудования",
				'keywords' => "стабилизаторы, энергия, rucelf, котёл, apc, арс",
				'description' => "Специализированные стабилизаторы для газовых котлов Энергия АРС, Rucelf КОТЁЛ",
				),
		'ibp-pro' => array(
				'xpath' => "//product[category='Инвертор' and series='IBP-PRO']",
				'caption' => "Инверторы Энергия ИБП Pro",
				'title' => "Инверторы (преобразователи напряжения) ИБП Pro | Интернет магазин электрооборудования",
				'keywords' => "инвертор, преобразователь напряжения, энергия, ИБП Pro",
				'description' => "Инверторы (преобразователи напряжения) Энергия ИБП Pro для построения систем бесперебойного питания"
				),
		'upi' => array(
				'xpath' => "//product[category='Инвертор' and series='UPI']",
				'caption' => "Инверторы Rucelf UPI",
				'title' => "Инверторы (преобразователи напряжения) Rucelf UPI | Интернет магазин электрооборудования",
				'keywords' => "инвертор, преобразователь напряжения, rucelf, UPI",
				'description' => "Инверторы (преобразователи напряжения) Rucelf UPI для построения систем бесперебойного питания"
				),
		'generator' => array(
				'xpath' => "//product[category='Генератор']",
				'caption' => "Генераторы",
				'title' => "Генераторы | Интернет магазин электрооборудования",
				'keywords' => "генератор, электростанция, бензиновый генератор, бензогенератор",
				'description' => ""
				),
		'patriot-srge' => array(
				'xpath' => "//product[category='Генератор' and series='SRGE']",
				'caption' => "Генераторы Patriot Max Power SRGE",
				'title' => "Генераторы Patriot Max Power SRGE | Интернет магазин электрооборудования",
				'keywords' => "генератор, patriot, max power, SRGE, электростанция, бензиновый генератор, бензогенератор",
				'description' => ""
				),
		'patriot-gp' => array(
				'xpath' => "//product[category='Генератор' and series='GP']",
				'caption' => "Генераторы Patriot GP",
				'title' => "Генераторы Patriot GP | Интернет магазин электрооборудования",
				'keywords' => "генератор, patriot gp, GP, электростанция, бензиновый генератор, бензогенератор",
				'description' => ""
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