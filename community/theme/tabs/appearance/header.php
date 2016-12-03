<?php
/*
 Tabs theme.
 Common header file.
*/

// TODO: isset($meta_title) ... else ... in common _header.php
#$meta_title = (isset($this->page['title']) ? $this->page['title'] : $this->add_spaces($this->tag)).($this->method != 'show' ? ' (' . $this->method . ')' : '') . " (@".htmlspecialchars($this->db->site_name, ENT_COMPAT | ENT_HTML401, HTML_ENTITIES_CHARSET) . ")";

require (Ut::join_path(THEME_DIR, '_common/_header.php'));

?>
<body>
<div class="Top<?php if (!$this->get_user()) echo "LoggedOut";?>">
	<div class="TopRight">
<?php echo $this->form_open('search', ['form_method' => 'get', 'tag' => $this->_t('TextSearchPage')]); ?>
	<span class="nobr">
<?php

	echo '<div id="menu-user">';
	echo "<ol>\n";

	echo '<li>' . $this->compose_link_to_page($this->db->root_page) . "</li>\n";

	// default menu
	if ($menu = $this->get_default_menu($user['user_lang']))
	{
		foreach ($menu as $menu_item)
		{
			$formatted_menu = $this->format($this->format($menu_item[1]), 'post_wacko');

			if ($this->page['page_id'] == $menu_item[0])
			{
				echo '<li class="active">';
			}
			else
			{
				echo '<li>';
			}

			echo $formatted_menu . "</li>\n";
		}
	}

?>
	</span> <li> <?php echo $this->_t('SearchText') ?>
		<input type="search" name="phrase" size="15" class="ShSearch" /></li>
<?php
	echo $this->form_close();
	echo "\n</ol></div>";
?>
	</div>
	<div class="TopLeft">
		<?php if ($this->get_user()) { ?>
		<img src="<?php echo $this->db->theme_url ?>icon/role.png" width="9" height="15" alt="" /><span class="nobr"><?php echo $this->_t('YouAre') . " " . $this->link($this->db->users_page . '/' . $this->get_user_name(), '', $this->get_user_name()) ?></span> <small>( <span class="nobr Tune">
		<?php
echo $this->compose_link_to_page($this->_t('AccountLink'), "", $this->_t('AccountText'), 0); ?>
		| <a onclick="return confirm('<?php echo $this->_t('LogoutAreYouSure');?>');" href="<?php echo $this->href('', 'Login', 'action=logout&amp;goback=' . $this->slim_url($this->tag));?>"><?php echo $this->_t('LogoutLink'); ?></a></span> )</small>
		<?php } else { ?>
		<table >
			<tr>
				<td>
			<?php echo $this->form_open('login', ['tag' => $this->_t('LoginPage')]); ?>
			<input type="hidden" name="action" value="login" />
			<img src="<?php echo $this->db->theme_url ?>icon/norole.png" width="9" height="15" alt="" /></td>
				<td><strong><?php echo $this->_t('LoginWelcome') ?>:&nbsp;</strong> </td>
				<td><input type="text" name="name" size="18" /></td>
				<td>&nbsp;&nbsp;&nbsp;<?php echo $this->_t('LoginPassword') ?>:&nbsp; </td>
				<td>
					<input type="hidden" name="goback" value="<?php echo $this->slim_url($this->tag);?>" />
					<input type="password" name="password" size="8" />&nbsp;
				</td>
				<td><input type="submit" class="OkBtn_Top" value="&nbsp;&nbsp;&raquo;&nbsp;&nbsp;" /></td>
			</tr>
		<?php echo $this->form_close(); ?>
		</table>
		<?php } ?>
	</div>
	<br clear="all" />
	<img src="<?php echo $this->db->base_url ?>image/spacer.png" width="1" height="1" alt="" /></div>
<div class="TopDiv"><img src="<?php echo $this->db->base_url;?>image/spacer.png" width="1" height="1" alt="" /></div>
<table style="width:100%;">
	<tr>
		<td style="vertical-align:top;" class="Bookmarks">&nbsp;&nbsp;<strong><?php echo $this->_t('Bookmarks') ?>:</strong>&nbsp;&nbsp;</td>
		<td style="width:100%;" class="Bookmarks">
<?php
	echo '<div id="menu-user">';
	echo "<ol>\n";

	// menu
	if ($menu = $this->get_menu())
	{
		foreach ($menu as $menu_item)
		{
			$formatted_menu = $this->format($menu_item[2], 'post_wacko');

			if ($this->page['page_id'] == $menu_item[0])
			{
				echo '<li class="active">';
			}
			else
			{
				echo '<li>';
			}

			echo $formatted_menu . "</li>\n";
		}
	}

	echo "\n</ol></div>";
?>
&nbsp;&nbsp;
		</td>
	</tr>
</table>
<div class="TopDiv2"><img src="<?php echo $this->db->base_url;?>image/spacer.png" width="1" height="1" alt="" /></div>
<div class="Wrapper"
<?php if ($this->method == 'edit') echo "style=\"margin-bottom:0;padding-bottom:0\""?>>
<div class="Print">
<?php if ($this->get_user()) { ?>
<?php echo ($this->is_watched === true ?
			"<a href=\"" . $this->href('watch') . "\">" . $this->_t('RemoveWatch') . "</a>" :
			"<a href=\"" . $this->href('watch') . "\">" . $this->_t('SetWatch') . "</a>" ) ?> ::
	<?php if (!in_array($this->page['page_id'], $this->get_menu_links())) {?>
	<a href="<?php echo $this->href('', '', "addbookmark=yes")?>"><img src="<?php echo $this->db->theme_url ?>icon/bookmark.png" width="12" height="12" alt="<?php echo $this->_t('AddToBookmarks') ?>" /></a> ::
<?php } else { ?>
	<a href="<?php echo $this->href('', '', "removebookmark=yes")?>">
	<img src="<?php echo $this->db->theme_url ?>icon/unbookmark.png" width="12" height="12" alt="<?php echo $this->_t('RemoveFromBookmarks') ?>" /></a> ::
<?php } }
?>
<?php echo"<a href=\"" . $this->href('print') . "\">" ?><img
	src="<?php echo $this->db->theme_url ?>icon/print.png"
	width="21" height="20"
	alt="<?php echo $this->_t('PrintVersion') ?>" /></a> :: <?php echo"<a href=\"" . $this->href('wordprocessor') . "\">" ?><img
	src="<?php echo $this->db->theme_url ?>icon/wordprocessor.png"
	width="16" height="16"
	alt="<?php echo $this->_t('WordprocessorVersion') ?>" /></a></div>
<div class="header">
	<h1><span class="Main"><?php echo $this->db->site_name ?>:</span> <?php echo (isset($this->page['title']) ? $this->page['title'] : $this->get_page_path()); ?> </h1>
<?php if (($this->method != 'edit') || !$this->has_access('write')) { ?>
	<div style="background-image:url(<?php echo $this->db->theme_url ?>icon/shade2.png);" class="Shade"><img
	src="<?php echo $this->db->theme_url ?>icon/shade1.png"
	width="106" height="6" alt="" /></div>
<?php } ?>
</div>
<?php
// here we show messages
$this->output_messages();
?>
