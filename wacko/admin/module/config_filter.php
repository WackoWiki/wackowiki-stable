<?php

if (!defined('IN_WACKO'))
{
	exit;
}

########################################################
##   Filter settings                                  ##
########################################################
$_mode = 'config_filter';

$module[$_mode] = [
		'order'	=> 250,
		'cat'	=> 'preferences',
		'status'=> (RECOVERY_MODE ? false : true),
		'mode'	=> $_mode,
		'name'	=> $engine->_t($_mode)['name'],		// Filter
		'title'	=> $engine->_t($_mode)['title'],	// Filter settings
	];

########################################################

function admin_config_filter(&$engine, &$module)
{
	/*
	TODO:
	1) use word table to add row 'set'
	2) add option to choose action: block, replace, moderate
	3) add option to switch between antispam.conf file and word table
	4) add option to select where the filter is applied: edit, tags, registration, referrers
	*/
?>
	<h1><?php echo $module['title']; ?></h1>
	<br />
	<p>
		Words that will be automatically censored on your Wiki.
	</p>
	<br />
<?php
	$file_name = Ut::join_path(CONFIG_DIR, 'antispam.conf');
	// update settings
	if (isset($_POST['action']) && $_POST['action'] == 'update')
	{
		// update secondary config
		$config['spam_filter']					= (string) $_POST['spam_filter'];
		#$config['spam_action']					= (string) $_POST['spam_action'];

		$engine->config->_set($config);

		// write antispam.conf file
		$phrase_list	= (string) $_POST['phrase_list'];
		file_put_contents($file_name, $phrase_list);
		chmod($file_name, 0644);

		$engine->log(1, '!!Updated spam filter settings!!');
		$engine->set_message('Updated spam filter settings', 'success');
		$engine->http->redirect(rawurldecode($engine->href()));
	}

	$phrases = file_get_contents($file_name);

	echo $engine->form_open('filter');
?>
		<input type="hidden" name="action" value="update" />
		<table class="formation">
			<colgroup>
				<col span="1" style="width:50%;">
				<col span="1" style="width:50%;">
			</colgroup>
			<tr>
				<th colspan="2">Word censoring</th>
			</tr>
			<tr class="hl_setting">
				<td class="label">
					<label for="spam_filter"><strong>SPAM Filter:</strong><br />
					<small>Enabling SPAM Filter</small></label>
				</td>
				<td>
					<input type="radio" id="spam_filter_on" name="spam_filter" value="1"<?php echo ( $engine->db->spam_filter == 1 ? ' checked' : '' );?> /><label for="spam_filter_on"><?php echo $engine->_t('Enabled'); ?></label>
					<input type="radio" id="spam_filter_off" name="spam_filter" value="0"<?php echo ( $engine->db->spam_filter == 0 ? ' checked' : '' );?> /><label for="spam_filter_off"><?php echo $engine->_t('Disabled'); ?></label>
				</td>
			</tr>
			<tr class="lined">
				<td colspan="2"></td>
			</tr>
			<tr class="hl_setting">
				<td class="label"><label for="phrase_list"><strong>Word list:</strong><br />
					<small>Word or phrase <code>fragment</code> to be blacklisted (one per line)</small></label></td>
				<td><textarea style="width:400px; height:400px;" id="phrase_list" name="phrase_list"><?php echo htmlspecialchars($phrases, ENT_COMPAT | ENT_HTML401, HTML_ENTITIES_CHARSET);?></textarea></td>
			</tr>

		</table>
		<br />
		<div class="center">
			<input type="submit" id="submit" value="<?php echo $engine->_t('FormSave');?>" />
			<input type="reset" id="button" value="<?php echo $engine->_t('FormReset');?>" />
		</div>
<?php
	echo $engine->form_close();
}

?>