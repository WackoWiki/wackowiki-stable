<?php

if (!defined('IN_WACKO'))
{
	exit;
}

// {{menu system=[0|1] redirect=''}}

if (!function_exists('menu_sorting'))
{
	function menu_sorting ($a, $b)
	{
		if ($a['menu_position'] == $b['menu_position'])
		{
			return 0;
		}

		return ($a['menu_position'] < $b['menu_position'])
			? -1
			: 1;
	}
}

if (!function_exists('load_user_menu'))
{
	function load_user_menu(&$engine, $user_id, $lang = '')
	{
		$_menu = $engine->load_all(
			"SELECT p.tag, p.title, m.menu_id, m.user_id, m.menu_title, m.menu_lang, m.menu_position ".
			"FROM ".$engine->db->table_prefix."menu m ".
				"LEFT JOIN ".$engine->db->table_prefix."page p ON (m.page_id = p.page_id) ".
			"WHERE m.user_id = '" . (int) $user_id . "' ".
				($lang
					? "AND m.menu_lang =  '".$lang."' "
					: "").
			"ORDER BY m.menu_position", false);

		return $_menu;
	}
}

if (!isset($redirect)) $redirect = 0; // required for usersettings action

if (!isset($system))
{
	$system = 0;
}

$message		= '';
$user			= '';
$default_menu	= '';
$menu_lang		= '';

// get default menu items
if ($this->is_admin() && $system == true)
{
	$_user_id		= $this->get_user_id('System');
	$default_menu	= true;

	$menu_lang = ($this->db->multilanguage? @$_REQUEST['menu_lang'] : '');
	if (!$this->known_language($menu_lang))
	{
		//language doesn't have any language files so use the admin set language instead
		$menu_lang = $this->db->language;
	}

	#$this->set_menu(MENU_DEFAULT);
}
else
{
	$user		= $this->get_user();
	$_user_id	= $user['user_id'];
}

/// Processing of our special form
if (isset($_POST['_user_menu']))
{
	$_menu		= load_user_menu($this, $_user_id, $menu_lang);
	$a			= $_menu;
	$b			= [];

	foreach ($a as $k => $v)
	{
		$b[$k]['user_id']		= $v['user_id'];
		$b[$k]['menu_id']		= $v['menu_id'];
		$b[$k]['menu_position']	= $v['menu_position'];
		$b[$k]['menu_title']	= $v['menu_title'];
		$b[$k]['tag']			= $v['tag'];
	}

	$object->data['user_menu'] = &$b;

	if (isset($_POST['update_menu']))
	{
		// reposition
		$data = [];

		foreach ($object->data['user_menu'] as $k => $item)
		{
			$data[] = [
				'menu_id'		=> $item['menu_id'],
				'menu_position'	=> 1 * $_POST['pos_' . $item['menu_id']]
			];
		}

		usort ($data, "menu_sorting");

		foreach ($data as $k => $item)
		{
			$data[$k]['menu_position'] = $k + 1;
		}

		// save
		foreach ($data as $item)
		{
			$this->db->sql_query(
				"UPDATE " . $this->db->table_prefix . "menu SET ".
					"menu_position	= '".$item['menu_position']."', ".
					"menu_title		= ".$this->db->q(substr(trim($_POST['title_' . $item['menu_id']]), 0, 250))." ".
				"WHERE menu_id		= '".$item['menu_id']."' ".
				"LIMIT 1");
		}
	}
	else if (isset($_POST['add_menu_item']))
	{
		// process input
		if (!empty($_POST['tag']))
		{
			$new_tag = trim($_POST['tag'], '/ ');

			// check target page existance
			if ($page = $this->load_page($new_tag, 0, '', LOAD_CACHE, LOAD_META))
			{
				$_page_id		= $this->get_page_id($new_tag);
				$_user_lang		= (isset($_POST['lang_new']) ? $_POST['lang_new'] : $user['user_lang']);

				// check existing page write access
				if ($this->has_access('write', $_page_id)) // TODO: why we need write access here?
				{
					// check if menu item already exists
					if ($this->db->load_single(
						"SELECT menu_id ".
						"FROM " . $this->db->table_prefix . "menu ".
						"WHERE user_id = '".(int) $_user_id."' ".
							($default_menu === true
									? "AND menu_lang = '".$_user_lang."' "
									: "").
							"AND page_id = '".(int) $_page_id."' ".
						"LIMIT 1"))
					{
						$message .= $this->_t('BookmarkAlreadyExists');
					}
					else
					{
						// writing new menu item
						$_menu_position = $this->db->load_all(
							"SELECT menu_id ".
							"FROM " . $this->db->table_prefix . "menu ".
							"WHERE user_id = '".(int) $_user_id."' ".
								($default_menu === true
									? "AND menu_lang = '".$_user_lang."' "
									: "")
								, false);

						$_menu_item_count = count($_menu_position);

						$this->db->sql_query(
							"INSERT INTO " . $this->db->table_prefix . "menu SET ".
							"user_id			= '".(int) $_user_id."', ".
							"page_id			= '".(int) $_page_id."', ".
							"menu_lang			= ".$this->db->q((($_user_lang != $page['page_lang']) && $default_menu === false ? $page['page_lang'] : $_user_lang)).", ".
							"menu_position		= '".(int)($_menu_item_count + 1)."'");

						#$message .= $this->_t('MenuItemAdded'); // TODO: msg set
					}
				}
				else
				{
					// no access rights
					$message .= $this->_t('ReadAccessDenied');
				}
			}
			else
			{
				// page does not exits
				$message .= $this->_t('DoesNotExists');
			}
		}
		else
		{
			// no page given
			#$message .= $this->_t('PageAlreadyExistsEditDenied');
		}

		$this->set_message($message);
	}
	else if (isset($_POST['delete_menu_item']))
	{
		$deletion = '';

		foreach ($object->data['user_menu'] as $item)
		{
			if (isset($_POST['delete_' . $item['menu_id']]))
			{
				if ($deletion != '')
				{
					$deletion .= ', ';
				}

				$deletion .= $item['menu_id'];
			}

			if ($deletion != '')
			{
				$this->db->sql_query(
					"DELETE ".
					"FROM " . $this->db->table_prefix . "menu ".
					"WHERE menu_id IN (".$deletion.")");
			}
		}
	}

	// purge SQL queries cache
	$this->config->invalidate_sql_cache();

	// update user menu
	$this->set_menu(MENU_USER, 1);

}

if ($_user_id)
{
	$_menu = load_user_menu($this, $_user_id, $menu_lang);

	if ($_menu)
	{
		// echo "<h4>".$this->_t('YourBookmarks')."</h4>";

		// user is logged in; display config form
		echo $this->form_open('edit_bookmarks');

		echo '<input type="hidden" name="_user_menu" value="yes" />';

		if ($default_menu === true)
		{
			echo '<label for="menu_lang">' . $this->_t('YourLanguage').' </label>';
			// FIXME: add a common function for this?
			echo '<select id="menu_lang" name="menu_lang">';

			$languages = $this->_t('LanguageArray');

			if ($this->db->multilanguage)
			{
				$langs = $this->available_languages();
			}
			else
			{
				$langs = [$this->db->language];
			}

			foreach ($langs as $lang)
			{
				echo '<option value="' . $lang . '" '.($menu_lang == $lang ? 'selected="selected" ' : '').'>' . $languages[$lang] . ' (' . $lang.")</option>\n";
			}

			echo "</select>\n";

			echo '<input type="submit" name="update" id="submit" value="update" />';
			echo '<br /><br />';
		}

		echo '<table>';
		echo '<tr><th>' . $this->_t('BookmarkNumber').'</th><th>' . $this->_t('BookmarkTitle').'</th><th>' . $this->_t('BookmarkPage').'</th><th>' . $this->_t('BookmarkMark').'</th><!--<th>Display</th>-->';

		if ($system)
		{
			echo '<th>Lang</th>';
		}

		echo '</tr>';

		foreach ($_menu as $menu_item)
		{
			echo '<tr class="lined">
			<td class="">
				<input type="number" min="0" name="pos_' . $menu_item['menu_id'] . '" size="2" style="width: 40px;" value="' . $menu_item['menu_position'] . '" />
			</td>
			<td>
				<input type="text" maxlength="100" name="title_' . $menu_item['menu_id'] . '" size="40" value="' . $menu_item['menu_title'] . '" />
			</td>
			<td>
				<!--<input type="radio" id="menu_item' . $menu_item['menu_id'] . '" name="change" value="' . $menu_item['menu_id'] . '" /> -->
				<label for="menu_item' . $menu_item['menu_id'] . '" title="' . $menu_item['title'] . '">&raquo; ' . $menu_item['tag'] . '</label>
			</td>
			<td style="text-align:center;" >
				<input type="checkbox" id="menu_item' . $menu_item['menu_id'] . '" name="delete_' . $menu_item['menu_id'] . '" />
			</td>
			<!--<td>

			'.(!empty($menu_item['menu_title']) ? $menu_item['menu_title'] : $menu_item['title']).'
			</td>-->';

			if ($system)
			{
				echo '<td>'.(!empty($menu_item['menu_lang']) ? $menu_item['menu_lang'] : '')."</td>\n";
			}

			echo "</tr>\n";
		}

		echo '<tfoot>';
		echo "<tr>\n".'<td colspan="3">'."\n";
		echo '<input type="submit" name="update_menu" value="' . $this->_t('BookmarkSaveChanges') . '" />';
		echo '</td><td>';
		echo '<input type="submit" name="delete_menu_item" value="' . $this->_t('BookmarkDeleteSelected') . '" />';
		echo "</td>\n</tr>\n";
		echo '</tfoot>';
		echo '</table>';
	}
	else
	{
		echo $this->_t('BookmarkNone');
	}

	echo $this->form_open('add_bookmark');
	echo '<input type="hidden" name="_user_menu" value="yes" />';
	echo '<br /><br />';
	echo '<label for="add_menu_item">' . $this->_t('BookmarksAddPage').':</label><br />'.
		 '<input type="text" id="add_menu_item" name="tag" value="" size="60" maxlength="255" /> ';

	if ($default_menu === true)
	{
		// FIXME: add a common function for this?
		echo '<select id="lang_new" name="lang_new">';

		$languages = $this->_t('LanguageArray');

		if ($this->db->multilanguage)
		{
			$langs = $this->available_languages();
		}
		else
		{
			$langs = [$this->db->language];
		}

		foreach ($langs as $lang)
		{
			echo '<option value="' . $lang . '" '.($menu_lang == $lang ? 'selected="selected" ' : '').'>' . $languages[$lang] . ' (' . $lang.")</option>\n";
		}

		echo "</select>\n";
	}

	echo  '<input type="submit" name="add_menu_item" value="' . $this->_t('CreatePageButton') . '" />';

	echo $this->form_close();
}
