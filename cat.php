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
				'xpath' => "//product[category='������������ ����������']",
				'caption' => "������������� ����������",
				'title' => "������������� ���������� | �������� ������� �������������������",
				'keywords' => "����������� ����������",
				'description' => ""
				),
		'stab1' => array(
				'xpath' => "//product[category='������������ ����������' and phases=1]",
				'caption' => "���������� ������������� ����������",
				'title' => "���������� ������������� ���������� | �������� ������� �������������������",
				'keywords' => "���������� ����������� ����������",
				'description' => ""
				),
		'stab3' => array(
				'xpath' => "//product[category='������������ ����������' and phases=3]",
				'caption' => "���������� ������������� ����������",
				'title' => "���������� ������������� ���������� | �������� ������� �������������������",
				'keywords' => "���������� ������������ ����������",
				'description' => ""
				),
		'converter' => array(
				'xpath' => "//product[category='��������' or category='�����������']",
				'caption' => "��������������� ����������",
				'title' => "���������, ��������������� ����������, ���, ������������ | �������� ������� �������������������",
				'keywords' => "",
				'description' => ""
				),
		'invertor' => array(
				'xpath' => "//product[category='��������']",
				'caption' => "��������� (��������������� ����������)",
				'title' => "��������� (��������������� ����������) | �������� ������� �������������������",
				'keywords' => "",
				'description' => ""
				),
		'snvt' => array(
				'xpath' => "//product[category='������������ ����������' and phases=1 and series='CHBT']",
				'caption' => "������������� ���������� ������� ����/1",
				'title' => "������������� ���������� ������� ����/1 | �������� ������� �������������������",
				'keywords' => "�������������, �������, ����, hybrid",
				'description' => "���������� ������������� ���������� ������� ����/1 �� 220�"
				),
		'voltron' => array(
				'xpath' => "//product[category='������������ ����������' and phases=1 and series='PCH']",
				'caption' => "������������� ���������� Voltron ���",
				'title' => "������������� ���������� Voltron ��� | �������� ������� �������������������",
				'keywords' => "�������������, �������, voltron, ���",
				'description' => "���������� ������������� ���������� Voltron ��� �� 220�"
				),
		'ach' => array(
				'xpath' => "//product[category='������������ ����������' and phases=1 and series='ACH']",
				'caption' => "������������� ���������� ������� ���",
				'title' => "������������� ���������� ������� ��� | �������� ������� �������������������",
				'keywords' => "�������������, �������, ach, ���",
				'description' => "���������� ������������� ���������� ������� ��� �� 220�"
				),
		'classic-ultra' => array(
				'xpath' => "//product[category='������������ ����������' and phases=1 and (series='CLASSIC' or series='ULTRA' or series='PREMIUM')]",
				'caption' => "������������� ���������� Classic, Ultra, Premium",
				'title' => "������������� ���������� Classic, Ultra, Premium | �������� ������� �������������������",
				'keywords' => "�������������, �������, classic, ultra, premium",
				'description' => "���������� ������������� ���������� Classic, Ultra � Premium �� 220�"
				),
		'apc' => array(
				'xpath' => "//product[category='������������ ����������' and phases=1 and series='APC']",
				'caption' => "������������� ��� ������� ������ ������� ���",
				'title' => "������������� ��� ������� ������ ������� ��� | �������� ������� �������������������",
				'keywords' => "�������������, �������, apc, ���",
				'description' => "������������� ��� ������� ������ ������� ���"
				),
		'snvt-3' => array(
				'xpath' => "//product[category='������������ ����������' and phases=3 and series='CHBT']",
				'caption' => "������������� ���������� ������� ����/3",
				'title' => "������������� ���������� ������� ����/3 | �������� ������� �������������������",
				'keywords' => "�������������, �������, ����, hybrid",
				'description' => "���������� ������������� ���������� ������� ����/3 �� 380�"
				),
		'voltron-3d' => array(
				'xpath' => "//product[category='������������ ����������' and phases=3 and series='3D']",
				'caption' => "���������� ������������� ���������� Voltron 3D",
				'title' => "���������� ������������� ���������� Voltron 3D | �������� ������� �������������������",
				'keywords' => "�������������, �������, voltron, 3d, ����������",
				'description' => "���������� ������������� ���������� Voltron 3D �� 380�"
				),
		'pn' => array(
				'xpath' => "//product[category='��������' and series='PN']",
				'caption' => "��������� (��������������� ����������) ������� ��",
				'title' => "��������� (��������������� ����������) ������� �� | �������� ������� �������������������",
				'keywords' => "��������, ��������������� ����������, �������, ��",
				'description' => "��������� (��������������� ����������) ������� �� ��� ���������� ������ �������������� �������"
				),
		'battery' => array(
				'xpath' => "//product[category='�����������']",
				'caption' => "������������ ��� ���������� � ���",
				'title' => "�������������� ������� (���) ��� ���������� � ��� | �������� ������� �������������������",
				'keywords' => "���, �����������, ��������, �������",
				'description' => "�������������� ������� (���) ��� ���������� � ���"
				),
		'rack' => array(
				'xpath' => "//product[category='������ ��������������']",
				'caption' => "������ �������������� ��� ��������������",
				'title' => "������ �������������� ��� ��������������",
				'keywords' => "������, �����������, ������������ ����������, ����������, ���������� ����, ���������� ������������ ����������",
				'description' => "������ �������������� ��� ����������� �������������� ���������� � ���������� ����"
				),
		'welding' => array(
				'xpath' => "//product[category='��������� �������']",
				'caption' => "��������� �������� (��������� ���������)",
				'title' => "��������� �������� (��������� ���������)",
				'keywords' => "������, ��������� �������, ��������� ��������",
				'description' => "���������� ��������� �������� (��������� ���������) ������� ��� ������ ��������"
				),
		'energy-sai' => array(
				'xpath' => "//product[category='��������� �������' and series='ENERGY-SAI']",
				'caption' => "��������� �������� (��������� ���������) ������� ���",
				'title' => "��������� �������� (��������� ���������) ������� ���",
				'keywords' => "������, ��������� �������, ��������� ��������, �������, ������� ���",
				'description' => "���������� ��������� �������� (��������� ���������) ������� ��� - ������� ��� ������ ��������"
				),
		'lux' => array(
				'xpath' => "//product[category='������������ ����������' and phases=1 and series='LUX']",
				'caption' => "������������� � ������� �������� ������� ����",
				'title' => "������������� ���������� �� ���������� ������� �������� ������� ���� | �������� ������� �������������������",
				'keywords' => "�������������, �������, ����",
				'description' => "������������� ���������� ������� ����"
				),
		'units' => array(
				'xpath' => "//product[category='����' or category='������']",
				'caption' => "����� �������� � ������",
				'title' => "����� �������� � ������ | �������� ������� �������������������",
				'keywords' => "����� �������� � ������",
				'description' => "����� �������� � ������",
				),
		'other' => array(
				'xpath' => "//product[category='����' or category='������' or category='������ ��������������']",
				'caption' => "������ � ������",
				'title' => "������ � ������ | �������� ������� �������������������",
				'keywords' => "������ � ������",
				'description' => "������ � ������",
				),
		'rucelf-sd' => array(
				'xpath' => "//product[category='������������ ����������' and trademark='Rucelf' and (series='SDF' or series='SDW' or series='SDV')]",
				'caption' => "������������� Rucelf SDF/SDW/SDV",
				'title' => "������������� Rucelf SDF/SDW/SDV | �������� ������� �������������������",
				'keywords' => "������������� Rucelf SDF/SDW/SDV",
				'description' => "������������� Rucelf SDF/SDW/SDV",
				),
		'rucelf-sr' => array(
				'xpath' => "//product[category='������������ ����������' and trademark='Rucelf' and (series='SRF' or series='SRW')]",
				'caption' => "������������� Rucelf SRF/SRW",
				'title' => "������������� Rucelf SRF/SRW | �������� ������� �������������������",
				'keywords' => "������������� Rucelf SRF/SRW",
				'description' => "������������� Rucelf SRF/SRW",
				),
		'star' => array(
				'xpath' => "//product[category='������������ ����������' and trademark='Rucelf' and series='STAR']",
				'caption' => "������������� Rucelf ����",
				'title' => "������������� Rucelf ���� | �������� ������� �������������������",
				'keywords' => "������������� Rucelf ����",
				'description' => "������������� Rucelf ����",
				),
		'kotel' => array(
				'xpath' => "//product[category='������������ ����������' and trademark='Rucelf' and series='KOTEL']",
				'caption' => "������������� Rucelf ��Ҩ�",
				'title' => "������������� Rucelf ��Ҩ� | �������� ������� �������������������",
				'keywords' => "������������� Rucelf ��Ҩ�",
				'description' => "������������� Rucelf ��Ҩ�",
				),
		'boiler' => array(
				'xpath' => "//product[category='������������ ����������' and phases=1 and (series='APC' or series='KOTEL')]",
				'caption' => "������������������ ������������� ��� ������� ������",
				'title' => "������������������ ������������� ��� ������� | �������� ������� �������������������",
				'keywords' => "�������������, �������, rucelf, ����, apc, ���",
				'description' => "������������������ ������������� ��� ������� ������ ������� ���, Rucelf ��Ҩ�",
				),
		'ibp-pro' => array(
				'xpath' => "//product[category='��������' and series='IBP-PRO']",
				'caption' => "��������� ������� ��� Pro",
				'title' => "��������� (��������������� ����������) ��� Pro | �������� ������� �������������������",
				'keywords' => "��������, ��������������� ����������, �������, ��� Pro",
				'description' => "��������� (��������������� ����������) ������� ��� Pro ��� ���������� ������ �������������� �������"
				),
		'upi' => array(
				'xpath' => "//product[category='��������' and series='UPI']",
				'caption' => "��������� Rucelf UPI",
				'title' => "��������� (��������������� ����������) Rucelf UPI | �������� ������� �������������������",
				'keywords' => "��������, ��������������� ����������, rucelf, UPI",
				'description' => "��������� (��������������� ����������) Rucelf UPI ��� ���������� ������ �������������� �������"
				),
		'generator' => array(
				'xpath' => "//product[category='���������']",
				'caption' => "����������",
				'title' => "���������� | �������� ������� �������������������",
				'keywords' => "���������, ��������������, ���������� ���������, ��������������",
				'description' => ""
				),
		'patriot-srge' => array(
				'xpath' => "//product[category='���������' and series='SRGE']",
				'caption' => "���������� Patriot Max Power SRGE",
				'title' => "���������� Patriot Max Power SRGE | �������� ������� �������������������",
				'keywords' => "���������, patriot, max power, SRGE, ��������������, ���������� ���������, ��������������",
				'description' => ""
				),
		'patriot-gp' => array(
				'xpath' => "//product[category='���������' and series='GP']",
				'caption' => "���������� Patriot GP",
				'title' => "���������� Patriot GP | �������� ������� �������������������",
				'keywords' => "���������, patriot gp, GP, ��������������, ���������� ���������, ��������������",
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