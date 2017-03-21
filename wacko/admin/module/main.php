<?php

if (!defined('IN_WACKO'))
{
	exit;
}

########################################################
##   Welcome screen and site locking                  ##
########################################################
$_mode = 'lock';

$module[$_mode] = [
		'order'	=> 100,
		'cat'	=> 'basics',
		'status'=> true,
		'mode'	=> $_mode,
		'name'	=> $engine->_t($_mode)['name'],		// Main Menu
		'title'	=> $engine->_t($_mode)['title'],	// WackoWiki Administration
		'objs'	=> [&$config],
	];

########################################################

function admin_lock(&$engine, &$module)
{
	// import passed variables and objects
	$config			= & $module['objs'][0];

	// (un)lock website
	if (isset($_POST['action']) && $_POST['action'] == 'lock')
	{
		$engine->config->lock();

		$engine->http->redirect('admin.php');
	}
	// clear cache
	else if (isset($_POST['action']) && $_POST['action'] == 'cache')
	{
		Ut::purge_directory(CACHE_PAGE_DIR);
		$engine->db->sql_query("TRUNCATE " . $engine->db->table_prefix . "cache");

		Ut::purge_directory(CACHE_SQL_DIR);
		Ut::purge_directory(CACHE_CONFIG_DIR);
		Ut::purge_directory(CACHE_FEED_DIR);
		Ut::purge_directory(CACHE_TEMPLATE_DIR);
	}
	// purge sessions
	else if (isset($_POST['action']) && $_POST['action'] == 'purge_sessions')
	{
		# $sql = "TRUNCATE " . $engine->db->table_prefix . "auth_token";
		# $engine->db->sql_query($sql);
		$engine->sess->delete_cookie(AUTH_TOKEN);
		// queries
		# $engine->config->invalidate_sql_cache();

	}
?>
	<h1><?php echo $module['title']; ?></h1>
	<br />
	<p>
		<?php echo $engine->_t('MainNote');?>
	</p>
	<br />
	<table style="max-width:200px" class="formation">
<?php
	echo $engine->form_open('lock', ['tag' => 'admin.php']);
?>
		<input type="hidden" name="action" value="lock" />
			<tr class="hl_setting">
				<td class="label" style="white-space:nowrap"><?php echo ($config->is_locked() === true ? '<span class="red">' . $engine->_t('SiteClosedTip') . '</span>' : '<span class="green">' . $engine->_t('SiteOpenedTip') . '</span>'); ?></td>
				<td style="text-align:center;"><input type="submit" id="submit" value="<?php echo ($config->is_locked() === true ? $engine->_t('SiteOpen') : $engine->_t('SiteClose')); ?>" /></td>
			</tr>
	<br />
<?php
	echo $engine->form_close();
	echo '<br />';
	// $form_name = '', $page_method = '', $form_method = 'post', $form_token = false, $tag = '', $form_more = '', $href_param = ''
	echo $engine->form_open('cache', ['tag' => 'admin.php']);
?>
		<input type="hidden" name="action" value="cache" />
			<tr class="hl_setting">
				<td class="label" style="white-space:nowrap"><?php echo $engine->_t('ClearCache');?></td>
				<td style="text-align:center;"><?php  echo (isset($_POST['action']) && $_POST['action'] == 'cache' ? $engine->_t('CacheCleared') : '<input type="submit" id="submit" value="' . $engine->_t('PurgeSessions') . '" />');?></td>
			</tr>
<?php
	echo $engine->form_close();

	echo $engine->form_open('purge_sessions', ['tag' => 'admin.php']);
?>
		<form action="admin.php" method="post" name="">
		<input type="hidden" name="mode" value="lock" />
		<input type="hidden" name="action" value="purge_sessions" />
			<tr class="hl_setting">
				<td class="label" style="white-space:nowrap"><?php echo $engine->_t('PurgeSessionsTip');?>
				<br /><?php #echo $engine->_t('PurgeSessionsExplain');?></td>
				<td style="text-align:center;"><?php echo (isset($_POST['action']) && $_POST['action'] == 'purge_sessions' ? $engine->_t('PurgeSessionsDone') : '<input type="submit" id="submit" value="' . $engine->_t('PurgeSessions') . '" />');?></td>
			</tr>
		</table>
<?php
	echo $engine->form_close();
	# echo $engine->action('admincache'); // TODO: solve redirect issue

}

?>