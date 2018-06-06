<?php

if (!defined('IN_WACKO'))
{
	exit;
}

// {{blog [page=cluster] [mode=latest|week|from] [date=YYYY-MM-DD] [max=Number] [title=1] [noxml=1]}}

if (!isset($page))		$page = $this->unwrap_link($page ?? '');

$error			= '';
$feed_tag		= '';
$blog_cluster	= $page;

if (!empty($blog_cluster))
{
	if (!isset($max))	$max = 10;
	if (isset($_GET['category_id']))
	{
		$mode			= 'category';
		$category_id	= (int) $_GET['category_id'];
	}
	if (!isset($mode))	$mode = 'latest';
	if (!isset($title))	$title = 1;
	if (!isset($noxml))	$noxml = 0;

	$pages				= [];
	$p_mode				= [];
	$prefix				= $this->db->table_prefix;
	#$blog_cluster		= $blog_cluster;
	$blog_levels		= $this->db->news_levels;

	// hide article H1 header
	$this->hide_article_header = true;

	// check privilege
	$access = $this->has_access('create');

	if (@$_POST['_action'] === 'add_topic' && $access)
	{
		// checking user input
		if (isset($_POST['title']))
		{
			$name		= trim($_POST['title'], ". \t");
			$namehead	= $name;
			$name		= ucwords($name);
			$name		= preg_replace('/[^- \\w]/', '', $name);
			$name		= str_replace([' ', "\t"], '', $name);

			if ($name == '')
			{
				$error = $this->_t('NewsNoName');
			}
		}
		else
		{
			$error = $this->_t('NewsNoName');
		}

		// if errors were found - return, else continue
		if ($error)
		{
			$this->set_message($error, 'error');
			$this->http->redirect($this->href());
		}
		else
		{
			// building news template
			$template	= '';

			// redirecting to the edit form
			$this->sess->body	= $template;
			$this->sess->title	= $namehead;

			if ($this->db->enable_feeds)
			{
				#$this->sess->feed	= true;
			}

			// needs to be numeric for ordering
			// TODO: add this as config option to Admin panel
			// .date('Y/')							- 2011
			// .date('Y/').date('m/')				- 2011/07 (default)
			// .date('Y/').date('m/').date('d/')	- 2011/07/14
			// .date('Y/').date('W/')				- 2011/29
			$blog_cluster_structure = date('Y/').date('m/');

			$this->http->redirect($this->href('edit', $blog_cluster . '/' . $blog_cluster_structure . $name, '', 1));
		}
	}

	// collect data
	// heavy lifting here (watch out for REGEXPs!)
	$select_count =
		"SELECT COUNT(page_id) AS n " .
			"FROM {$prefix}page ";

	$select_mode =
		"SELECT p.page_id, p.owner_id, p.user_id, p.tag, p.title, p.created, p.comments, u.user_name AS owner " .
		"FROM {$prefix}page p " .
			"INNER JOIN {$prefix}user u ON (p.owner_id = u.user_id) ";

	$order_by_mode =
		"ORDER BY p.created DESC ";

	if ($mode == 'latest')
	{
		$p_mode = ['mode' => 'latest'];

		$selector =
			"WHERE tag REGEXP '^{$blog_cluster}{$blog_levels}$' " .
				"AND comment_on_id = 0 " .
				"AND deleted <> 1 ";

		$sql_count	=
			$select_count .
			$selector;

		$sql_mode	=
			$select_mode .
			$selector .
			$order_by_mode;
	}
	else if ($mode == 'category')
	{
		$p_mode = ['category' => $category_id];

		$selector =
				"INNER JOIN {$prefix}category_assignment c ON (c.object_id = p.page_id) " .
			"WHERE p.tag REGEXP '^{$blog_cluster}{$blog_levels}$' " .
				"AND c.category_id = " . (int) $category_id . " " .
				"AND c.object_type_id = 1 " .
				"AND p.comment_on_id = 0 " .
				"AND p.deleted <> 1 ";

		$sql_count	=
			$select_count .
			$selector;

		$sql_mode	=
			$select_mode .
			$selector .
			$order_by_mode;

		$category_title	= $this->db->load_single(
			"SELECT category " .
			"FROM {$prefix}category " .
			"WHERE category_id = " . (int) $category_id . " ", false);
	}
	else if ($mode == 'week')
	{
		$p_mode = ['mode' => 'week'];

		$selector =
			"WHERE tag REGEXP '^{$blog_cluster}{$blog_levels}$' " .
				"AND created > DATE_SUB( UTC_TIMESTAMP(), INTERVAL 7 DAY ) " .
				"AND comment_on_id = 0 " .
				"AND deleted <> 1 ";

		$sql_count	=
			$select_count .
			$selector;

		$sql_mode	=
			$select_mode .
			$selector .
			$order_by_mode;
	}
	else if ($mode == 'from' && $date)
	{
		$p_mode = ['mode' => 'week'];
		$date	= $this->db->date($date);

		$selector =
			"WHERE tag REGEXP '^{$blog_cluster}{$blog_levels}$' " .
				"AND created > " . $this->db->q($date) . " " .
				"AND comment_on_id = 0 " .
				"AND deleted <> 1 ";

		$sql_count	=
			$select_count .
			$selector;

		$sql_mode	=
			$select_mode .
			$selector .
			$order_by_mode;
	}

	$count		= $this->db->load_single($sql_count, true);
	$pagination	= $this->pagination($count['n'], $max, 'p', $p_mode);
	$pages		= $this->db->load_all($sql_mode . $pagination['limit'], true);

	// start output
	echo '<section class="news">' . "\n";

	if ($title == 1)
	{
		if (isset($category_title))
		{
			$_category_title = ' ' . $this->_t('For') . ' ' . $this->_t('Category') . ' &laquo;' . $category_title['category'] . '&raquo;';
		}
		else
		{
			$_category_title = '';
		}

		if ($this->page['tag'] == $blog_cluster)
		{
			$_title = $this->_t('News') . $_category_title;
		}
		else
		{
			$_title = $this->compose_link_to_page($blog_cluster, '', $this->_t('News')) . $_category_title;
		}

		echo "<h1>" . $_title . "</h1>";
	}

	// displaying XML icon
	if (!(int) $noxml)
	{
		if ($this->db->news_cluster)
		{
			if (substr($this->tag, 0, strlen($this->db->news_cluster . '/')) == $this->db->news_cluster . '/')
			{
				$feed_tag = 'news';
			}
		}
		else
		{
			$feed_tag = 'blog' . $this->page['page_id'];
		}

		echo '<span class="desc_rss_feed"><a href="' . $this->db->base_url . XML_DIR . '/' . $feed_tag . '_' . preg_replace('/[^a-zA-Z0-9]/', '', strtolower($this->db->site_name)) . '.xml"><img src="' . $this->db->theme_url . 'icon/spacer.png' . '" title="' . $this->_t('NewsXMLTip') . '" alt="XML" class="btn-feed"/></a></span>' . "<br>\n";
	}

	echo '<div style="width:100%;">
			<p>' . ($access? '<strong><small class="cite"><a href="#newtopic">' . $this->_t('ForumNewTopic') . '</a></small></strong>' : '') . '</p>';
	$this->print_pagination($pagination);
	echo '<br style="clear:both;">
		</div>' . "\n";

	// displaying articles
	if ($pages)
	{
		#$this->print_pagination($pagination);

		foreach ($pages as $page)
		{
			$page_ids[]	= $page['page_id'];
			$this->page_id_cache[$page['tag']] = $page['page_id'];
		}

		// cache acls
		$this->preload_acl($page_ids, ['read', 'write']);
		$this->preload_categories($page_ids);
		$this->preload_links($page_ids);

		foreach ($pages as $page)
		{
			$_category = $this->get_categories($page['page_id'], OBJECT_PAGE);
			$_category = !empty($_category) ? $this->_t('Category') . ': ' . $_category . ' | ' : '';

			echo '<article class="newsarticle">';
			echo '<h2 class="newstitle"><a href="' . $this->href('', $page['tag'], '') . '">' . $page['title'] . "</a></h2>\n";
			echo '<div class="newsinfo"><span><time datetime="' . $this->page['created'] . '">' . $this->get_time_formatted($page['created']) . '</time> ' . $this->_t('By') . ' ' . $this->user_link($page['owner'], '', true, false) . "</span></div>\n";
			echo '<div class="newscontent">' . $this->action('include', ['page' => '/' . $page['tag'], 'notoc' => 0, 'nomark' => 1], 1) . "</div>\n";
			echo '<footer class="newsmeta">' . $_category." " . ($this->has_access('write', $page['page_id']) ? $this->compose_link_to_page($page['tag'], 'edit', $this->_t('EditText')) . " | " : "") . "  " .
				'<a href="' . $this->href('', $page['tag'], ['show_comments' => 1]) . '#header-comments" title="' . $this->_t('NewsDiscuss') . ' ' . $page['title'] . '">' . (int) $page['comments'] . " " . $this->_t('Comments') . " &raquo; " . "</a></footer>\n";
			echo "</article>\n";

			unset ($_category);
		}

		// pagination
		$this->print_pagination($pagination);
	}
	else
	{
		echo '<br><br>' . $this->_t('NewsNotAvailable');
	}

	if ($access)
	{
		echo $this->form_open('add_topic');
		?>
		<br><a id="newtopic"></a><br>
				<table class="formation">
			<tr>
				<td class="label"><label for="posttitle"><?php echo $this->_t('ForumTopicName'); ?>:</label></td>
				<td>
					<input type="text" id="posttitle" name="title" size="50" maxlength="250" value="">
					<input type="submit" id="submit" value="<?php echo $this->_t('ForumTopicSubmit'); ?>">
				</td>
			</tr>
		</table>

		<?php #echo $this->_t('NewsName'); ?>
		<?php echo $this->form_close();
	}

	echo "</section>\n";
}
else
{
	echo $this->_t('NewsNoClusterDefined');
}
