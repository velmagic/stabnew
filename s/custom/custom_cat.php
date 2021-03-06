<?php
$nav = array(
		'p1' => array(
				'xpath' =>"//product[category='������������ ����������' and power>400 and power<1100 and phases=1]",
				'caption' => "������������� ���������� 1��� � �����",
				'title' => "������������� ���������� | �������� ������� �������������������",
				'keywords' => "����������� ����������",
				'description' => "���������������"	
				),
		'p3' => array(
				'xpath' =>"//product[category='������������ ����������' and power>1000 and power<3100]",
				'caption' => "������������� ���������� �� 3���",
				'title' => "������������� ���������� | �������� ������� �������������������",
				'keywords' => "����������� ����������",
				'description' => "���������������"	
				),
		'p5' => array(
				'xpath' =>"//product[category='������������ ����������' and power>3000 and power<5100]",
				'caption' => "������������� ���������� �� 5���",
				'title' => "������������� ���������� | �������� ������� �������������������",
				'keywords' => "����������� ����������",
				'description' => "���������������"	
				),
		'p8' => array(
				'xpath' =>"//product[category='������������ ����������' and power>5000 and power<8100]",
				'caption' => "������������� ���������� �� 8���",
				'title' => "������������� ���������� | �������� ������� �������������������",
				'keywords' => "����������� ����������",
				'description' => "���������������"	
				),
		'p10' => array(
				'xpath' =>"//product[category='������������ ����������' and power>8000 and power<10100]",
				'caption' => "������������� ���������� �� 10���",
				'title' => "������������� ���������� | �������� ������� �������������������",
				'keywords' => "����������� ����������",
				'description' => "���������������"	
				),
		'pbig' => array(
				'xpath' =>"//product[category='������������ ����������' and power>10000 and phases=1]",
				'caption' => "������������� ���������� ����� 10���",
				'title' => "������������� ���������� | �������� ������� �������������������",
				'keywords' => "����������� ����������",
				'description' => "���������������"	
				),
		'winter' => array(
				'xpath' =>"//product[category='������������ ����������' and ltemp<-5 and phases=1]",
				'caption' => "���������������� ������������� ����������",
				'title' => "������������� ���������� | �������� ������� �������������������",
				'keywords' => "����������� ����������",
				'description' => "���������������"	
				),				
		'stab' => array(
				'xpath' => "//product[category='������������ ����������']",
				'caption' => "������������� ����������",
				'title' => "������������� ���������� | �������� ������� �������������������",
				'keywords' => "����������� ����������",
				'description' => ""
				),
		'phase1' => array(
				'xpath' => "//product[category='������������ ����������' and phases=1]",
				'caption' => "���������� ������������� ����������",
				'title' => "���������� ������������� ���������� | �������� ������� �������������������",
				'keywords' => "���������� ����������� ����������",
				'description' => ""
				),
		'phase3' => array(
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
				'caption' => "������������� ���������� UPower ���",
				'title' => "������������� ���������� UPower ��� | �������� ������� �������������������",
				'keywords' => "�������������, �������, upower, ach, ���",
				'description' => "���������� ������������� ���������� UPower ��� �� 220�"
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
            );