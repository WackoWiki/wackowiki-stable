<?php

if (!defined('IN_WACKO'))
{
	exit;
}

// settings:
//	root		- where to start counting from (defaults to current tag)
//	list		- make categories clickable links which display pages of a given category (1 (default) or 0)
//	ids			- display pages which belong to these comma-separated categories ids (default none)
//	lang		- categories language if necessary (defaults to current page lang)
//	sort		- order pages alphabetically ('abc', default) or creation date ('date')
//	nomark		- display header and fieldset (1, 2 (no header even in 'categories' mode) or 0 (default))

if (!isset($root))			$root	= '/';
if (!isset($list))			$list	= 1;
if (!isset($ids))			$ids	= '';
if (!isset($lang))			$lang	= $this->page['page_lang'];
if (!isset($sort) || !in_array($sort, ['abc', 'date']))
{
	$sort = 'abc';
}
if (!isset($nomark)) $nomark = '';

$root = $this->unwrap_link($root);

//echo '<br />';

if ($list && ($ids || isset($_GET['category_id'])))
{
	if ($ids)
	{
		$category = preg_replace('/[^\d, ]/', '', $ids);
	}
	else
	{
		$category = (int) $_GET['category_id'];
	}

	if ($_words = $this->db->load_all(
		"SELECT category FROM " . $this->db->table_prefix . "category " .
		"WHERE category_id IN (" . $this->db->q($category) . ")", true));

	if ($nomark != 2)
	{
		if ($_words)
		{
			foreach ($_words as $word)
			{
				$words[] = $word['category'];
			}

			$words = strtolower(implode(', ', $words));
		}

		echo '<div class="layout-box"><p><span>' . $this->_t('PagesCategory') . ($words ? ' &laquo;<strong>' . $words . '</strong>&raquo;' : '' ) . ":</span></p>\n";
	}

	if ($sort == 'abc')
	{
		$order = 'title ASC';
	}
	else if ($sort == 'date')
	{
		$order = 'created DESC';
	}

	if ($pages = $this->db->load_all(
		"SELECT p.page_id, p.tag, p.title, p.created " .
		"FROM " . $this->db->table_prefix . "category_assignment AS k " .
			"INNER JOIN " . $this->db->table_prefix . "page AS p ON (k.object_id = p.page_id) " .
		"WHERE k.category_id IN (" . $this->db->q($category) . ") " .
			"AND k.object_type_id = 1 " .
			"AND p.deleted <> '1' " .
			($root
				? "AND (p.tag = " . $this->db->q($root) . " OR p.tag LIKE " . $this->db->q($root . '/%') . ") "
				: '') .
		"ORDER BY p.{$order} ", true))
	{
		if ($_words = $this->db->load_all(
			"SELECT category FROM " . $this->db->table_prefix . "category " .
			"WHERE category_id IN (" . $this->db->q($category) . ")", true))
		{
			echo '<ol>';

			foreach ($pages as $page)
			{
				// cache page_id for for has_access validation in link function
				$this->page_id_cache[$page['tag']] = $page['page_id'];

				if ($this->has_access('read', $page['page_id']) !== true)
				{
					continue;
				}
				else
				{
					echo '<li>' . ($sort == 'date' ? '<small>('.date('d/m/Y', strtotime($page['created'])) . ')</small> ' : '') . $this->link('/' . $page['tag'], '', $page['title'], '', 0, 1) . "</li>\n";
				}
			}

			echo '</ol>';
		}
		else
		{
			echo '<em>' . $this->_t('CategoryNotExists') . '</em><br />';
		}
	}
	else
	{
		echo '<em>' . $this->_t('CategoryEmpty') . '</em><br />';
	}

	if ($nomark != 2)
	{
		echo '</div><br />';
	}
}

if (!$ids)
{
	// header
	if (!$nomark)
	{
		echo '<div class="layout-box"><p><span>' . $this->_t('Categories') . ($root ? " of cluster " . $this->link('/' . $root, '', '', '', 0) : '') . ":</span></p>\n";
	}

	// categories list
	if ($categories = $this->get_categories_list($lang, true, $root))
	{
		$total	= ceil(count($categories) / 4);
		$n		= 1;

		echo '<table class="category_browser">' . "\n\t<tr>\n" . "\t\t<td>\n";
		echo '<ul class="ul_list lined">' . "\n";

		foreach ($categories as $category_id => $word)
		{
			$spacer = '&nbsp;&nbsp;&nbsp;';

			echo '<li> ' . ($list ? '<a href="' . $this->href('', '', ['category_id' => $category_id]) . '" rel="tag" class="tag">' : '') . htmlspecialchars($word['category'], ENT_COMPAT | ENT_HTML401, HTML_ENTITIES_CHARSET) . ($list ? '</a>' . '<span class="item-multiplier-x"> &times; </span><span class="item-multiplier-count">' . (int) $word['n'] . '</span>' : '');

			if (isset($word['child']) && $word['child'] == true)
			{
				echo "\n<ul>\n";

				foreach ($word['child'] as $category_id => $word)
				{
					echo '<li> ' . ($list ? '<a href="' . $this->href('', '', ['category_id' => $category_id]) . '" rel="tag" class="tag">' : '') . htmlspecialchars($word['category'], ENT_COMPAT | ENT_HTML401, HTML_ENTITIES_CHARSET) . ($list ? '</a>' . '<span class="item-multiplier-x"> &times; </span><span class="item-multiplier-count">' . (int) $word['n'] . '</span>' : '') . "</li>\n";
				}

				echo "</ul>\n</li>\n";
			}
			else
			{
				echo "</li>\n";
			}

			// modulus operator: every n loop add a break
			if ($n % $total == 0)
			{
				echo "</ul>\n";
				echo "\t\t</td>\n\t\t<td>\n";
				echo '<ul class="ul_list lined">' . "\n";
			}

			$n++;
		}

		echo "</ul>\n";
		echo "\t\t</td>\n\t</tr>\n</table>\n";
	}
	else
	{
		echo '<em>' . $this->_t('NoCategoriesForThisLang') . '</em>';
	}

	if (!$nomark)
	{
		echo "</div>\n";
	}
}

?>