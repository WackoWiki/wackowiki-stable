<?php

$lng = "es";

// insert these pages only for default language
if ($config['language'] == $lng)
{
	if ($config['is_update'] == false)
	{
		insert_page($config['root_page'], '', "((file:wacko_logo.png WackoWiki))\n**Bienvenida a tu ((WackoWiki:Doc/English/WackoWiki WackoWiki))!**\n\nDa click en el enlace \"Editar esta pagina\" abajo en la pagina para empezar.\n\nLa documentación se puede encontrar en WackoWiki:Doc/English.\n\nPaginas útiles: ((WackoWiki:Doc/English/Formatting Formatting)), ((Buscar)).\n\n", $lng, 'Admins', true, false);
		insert_page($config['users_page'].'/'.$config['admin_name'], $config['admin_name'], "::+::\n\n", $lng, $config['admin_name'], true, false);
	}
	else
	{
		insert_page($config['users_page'].'/'.$config['admin_name'].'/MigrateDataToR50', 'Migrate Data to R5.0', "{{adminupdate}}\n\n", $lng, $config['admin_name'], true, false);
	}

	#insert_page('PaginasBuscadas', 'Paginas Buscadas', '{{wanted}}', $lng, 'Admins', true, false);
	#insert_page('PaginasOrfelinas', 'Paginas Orfelinas', '{{orphaned}}', $lng, 'Admins', true, false);
	#insert_page('MisPaginas', 'Mis Paginas', '{{mypages}}', $lng, 'Admins', true, false);
	#insert_page('MisCambios', 'Mis Cambios', '{{mychanges}}', $lng, 'Admins', true, false);

	insert_page('Category', 'Category', '{{category}}', $lng, 'Admins', false, false);
	insert_page('Permalink', 'Permalink', '{{permalinkproxy}}', $lng, 'Admins', false, false);
	insert_page('Groups', 'Groups', '{{usergroups}}', $lng, 'Admins', false, false);
	insert_page('Users', 'Usuarios', '{{users}}', $lng, 'Admins', false, false);
}

insert_page('UltimasModificaciones', 'Ultimas Modificaciones', '{{changes}}', $lng, 'Admins', false, true, 'Modificaciones');
insert_page('UltimosCommentarios', 'Ultimos Commentarios', '{{commented}}', $lng, 'Admins', false, true, 'Commentarios');
insert_page('IndiceDePaginas', 'Indice De Paginas', '{{pageindex}}', $lng, 'Admins', false, true, 'Indice');

insert_page('Registrarse', 'Registrarse', '{{registration}}', $lng, 'Admins', false, false);

insert_page('Password', 'Password', '{{changepassword}}', $lng, 'Admins', false, false);
insert_page('Buscar', 'Buscar', '{{search}}', $lng, 'Admins', false, false);
insert_page('Conectar', 'Conectar', '{{login}}', $lng, 'Admins', false, false);
insert_page('Preferencias', 'Preferencias', '{{usersettings}}', $lng, 'Admins', false, false);

?>