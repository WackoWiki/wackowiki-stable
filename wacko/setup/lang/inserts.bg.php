<?php

$page_lang = 'bg';

// insert these pages only for default language
if ($config['language'] == $page_lang)
{
	if ($config['is_update'] == false)
	{
		insert_page($config['root_page'], '', "((file:wacko_logo.png WackoWiki))\n**����� ����� ��� ��������� ���� �� ((WackoWiki:Doc/English/WackoWiki WackoWiki)).**\n\n�������� �� ����� ����, �� �� ����������� ���� �������� (����� ���� � ���� � ������ ������� �� ������� ������ �� ����������).\n\n������������ (�� ���������) ��� �� WackoWiki:Doc/Bulgarian.\n\n������� ����: ((WackoWiki:Doc/English/Formatting Formatting)), ((Search)).\n\n", $page_lang, 'Admins', true, false, null, 0);
		insert_page($config['users_page'].'/'.$config['admin_name'], $config['admin_name'], "::@::\n\n", $page_lang, $config['admin_name'], true, false, null, 0);
	}
	else
	{
		// ...
	}

	insert_page('Category', 'Category', '{{category}}', $page_lang, 'Admins', false, false);
	insert_page('Permalink', 'Permalink', '{{permalinkproxy}}', $page_lang, 'Admins', false, false);
	insert_page('Groups', 'Groups', '{{groups}}', $page_lang, 'Admins', false, false);
	insert_page('Users', 'Users', '{{users}}', $page_lang, 'Admins', false, false);
}

insert_page('�������', '�������', '{{pageindex}}', $page_lang, 'Admins', false, true, '�������');
insert_page('���������������', '�������� �������', '{{changes}}', $page_lang, 'Admins', false, true, '�������');
insert_page('�������������', '���� ���������', '{{commented}}', $page_lang, 'Admins', false, true, '���������');

insert_page('�����������', '�����������', '{{registration}}', $page_lang, 'Admins', false, false);

insert_page('Password', 'Password', '{{changepassword}}', $page_lang, 'Admins', false, false);
insert_page('Search', 'Search', '{{search}}', $page_lang, 'Admins', false, false);
insert_page('�������', '�������', '{{login}}', $page_lang, 'Admins', false, false);
insert_page('Settings', 'Settings', '{{usersettings}}', $page_lang, 'Admins', false, false);

?>