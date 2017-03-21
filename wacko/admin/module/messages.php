<?php

if (!defined('IN_WACKO'))
{
	exit;
}

########################################################
##   Messages settings                                ##
########################################################
$_mode = 'messages';

$module[$_mode] = [
		'order'	=> 710,
		'cat'	=> 'messages',
		'status'=> (RECOVERY_MODE ? false : true),
		'mode'	=> $_mode,
		'name'	=> $engine->_t($_mode)['name'],		// System message
		'title'	=> $engine->_t($_mode)['title'],	// System messages
	];

########################################################

function admin_messages(&$engine, &$module)
{
?>
	<h1><?php echo $module['title']; ?></h1>
	<br />
<?php
	// update settings
	if (isset($_POST['action']) && $_POST['action'] == 'update')
	{
		$config['system_message']			= (string) $_POST['system_message'];
		$config['system_message']			= $engine->format($config['system_message'], 'wiki');
		$config['system_message_type']		= (string) $_POST['system_message_type'];
		$config['enable_system_message']	= (int) $_POST['enable_system_message'];

		$engine->config->_set($config);

		$engine->log(1, 'Updated system message');
		$engine->set_message('Updated system message', 'success');
		$engine->http->redirect(rawurldecode($engine->href()));
	}

	echo $engine->form_open('messages');
?>
		<input type="hidden" name="action" value="update" />
		<table class="formation">
			<colgroup>
				<col span="1" style="width:50%;">
				<col span="1" style="width:50%;">
			</colgroup>
			<tr>
				<th colspan="2">System message</th>
			</tr>
			<tr class="hl_setting">
				<td class="label"><label for="system_message"><strong>System message:</strong><br />
					<small>Your text here</small></label></td>
				<td><textarea style="width:200px; height:100px;" id="system_message" name="system_message"><?php echo htmlspecialchars($engine->db->system_message, ENT_COMPAT | ENT_HTML401, HTML_ENTITIES_CHARSET);?></textarea></td>
			</tr>
			<tr class="lined">
				<td colspan="2"></td>
			</tr>
			<tr class="hl_setting">
				<td class="label"><label for="system_message_type"><strong>Type:</strong><br />
					<small>Message type (CSS).</small></label></td>
				<td>
					<select id="system_message_type" name="system_message_type">
<?php
						$typs = ['info', 'warning', 'marquee'];

						foreach ($typs as $type)
						{
							echo '<option value="' . $type . '" '.($engine->db->system_message_type == $type ? 'selected' : '') . '>' . $type . '</option>';
						}

						// add option: Position (header, footer, ..)
						// add option: allow users to dissmis the message
?>
					</select>
				</td>
			</tr>
			<tr class="lined">
				<td colspan="2"></td>
			</tr>
			<tr class="hl_setting">
				<td class="label"><strong>Enable system message:</strong><br />
					<small>Show system message.</small></td>
				<td>
					<input type="radio" id="sys_message_on" name="enable_system_message" value="1"<?php echo ( $engine->db->enable_system_message == 1 ? ' checked' : '' );?> /><label for="sys_message_on"><?php echo $engine->_t('On');?></label>
					<input type="radio" id="sys_message_off" name="enable_system_message" value="0"<?php echo ( $engine->db->enable_system_message == 0 ? ' checked' : '' );?> /><label for="sys_message_off"><?php echo $engine->_t('Off');?></label>
				</td>
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