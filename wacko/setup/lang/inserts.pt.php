<?php

$lang = 'pt';

// insert these pages only for default language
if ($config['language'] == $lang)
{
	if ($config['is_update'] == false)
	{
		insert_page($config['root_page'], 'Home Page', "((file:wacko_logo.png WackoWiki))\n**Welcome to your ((WackoWiki:Doc/English/WackoWiki WackoWiki)) site!**\n\nClick after you have ((login logged in)) on the \"Edit this page\" link at the bottom to get started.\n\nDocumentation can be found at WackoWiki:Doc/English.\n\nUseful pages: ((WackoWiki:Doc/English/Formatting Formatting)), ((Search)).\n\n", $lang, 'Admins', true, false);
		insert_page($config['users_page'].'/'.$config['admin_name'], $config['admin_name'], "::@::\n\n", $lang, $config['admin_name'], true, false);
	}
	else
	{
		// ...
	}

	insert_page('Category', 'Category', '{{category}}', $lang, 'Admins', false, false);
	insert_page('Permalink', 'Permalink', '{{permalinkproxy}}', $lang, 'Admins', false, false);
	insert_page('Groups', 'Groups', '{{groups}}', $lang, 'Admins', false, false);
	insert_page('Users', 'Users', '{{users}}', $lang, 'Admins', false, false);
}

insert_page('Altera��esRecentes', 'Altera��es Recentes', '{{changes}}', $lang, 'Admins', false, true, 'Altera��es');
insert_page('RecentementeComentadas', 'Recentemente Comentadas', '{{commented}}', $lang, 'Admins', false, true, 'Comentadas');
insert_page('�ndicedeP�ginas', '�ndicede P�ginas', '{{pageindex}}', $lang, 'Admins', false, true, '�ndicede');

insert_page('Registration', 'Registration', '{{registration}}', $lang, 'Admins', false, false);

insert_page('Password', 'Password', '{{changepassword}}', $lang, 'Admins', false, false);
insert_page('Search', 'Search', '{{search}}', $lang, 'Admins', false, false);
insert_page('Entrar', 'Entrar', '{{login}}', $lang, 'Admins', false, false);
insert_page('Settings', 'Settings', '{{usersettings}}', $lang, 'Admins', false, false);

?>