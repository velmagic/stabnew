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
				'xpath' => "//product[category='������������ ����������' and phases=1 and (series='CLASSIC' or series='ULTRA')]",
				'caption' => "������������� ���������� Classic � Ultra",
				'title' => "������������� ���������� Classic � Ultra | �������� ������� �������������������",
				'keywords' => "�������������, �������, classic, ultra",
				'description' => "���������� ������������� ���������� Classic � Ultra �� 220�"
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