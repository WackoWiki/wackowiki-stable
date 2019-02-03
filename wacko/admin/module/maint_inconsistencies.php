<?php

if (!defined('IN_WACKO'))
{
	exit;
}

##########################################################
##	Data Inconsistencies								##
##########################################################
$_mode = 'maint_inconsistencies';

$module[$_mode] = [
		'order'	=> 600,
		'cat'	=> 'maintenance',
		'status'=> (RECOVERY_MODE ? false : true),
		'mode'	=> $_mode,
		'name'	=> $engine->_t($_mode)['name'],		// Inconsistencies
		'title'	=> $engine->_t($_mode)['title'],	// Fixing Data Inconsistencies
	];

##########################################################

function admin_maint_inconsistencies(&$engine, &$module)
{
	$prefix		= $engine->db->table_prefix;
?>
	<h1><?php echo $module['title']; ?></h1>
	<br>
	<p><?php echo $engine->_t('InconsistenciesInfo');?></p>
	<br>
<?php

	/////////////////////////////////////////////
	// A. check for inconsistencies
	/////////////////////////////////////////////
	if (isset($_POST['check']))
	{
		if ($_REQUEST['action'] == 'check_inconsistencies')
		{
			echo '<table class="formation" style="max-width:600px; border-spacing: 1px; border-collapse: separate; padding: 4px;">';
			?>
			<tr>
				<th style="width:250px;"><?php echo $engine->_t('Inconsistencies');?></th>
				<th class="t-left"></th>
				<th class="t-left"><?php echo $engine->_t('Records');?></th>
			</tr>
			<?php
			// 1.1 usergroup_member without user
			$usergroup_member = $engine->db->load_all(
				"SELECT
					gm.*
				FROM
					" . $prefix . "usergroup_member gm
					LEFT JOIN " . $prefix . "user u ON (gm.user_id = u.user_id)
				WHERE
					u.user_id IS NULL");

			$inconsistencies['1.1'] = ['usergroup_member without user', count($usergroup_member)];
			// -> DELETE

			// 1.2. menu without user
			$menu = $engine->db->load_all(
				"SELECT
					m.menu_id
				FROM
					" . $prefix . "menu m
					LEFT JOIN " . $prefix . "user u ON (m.user_id = u.user_id)
				WHERE
					u.user_id IS NULL");

			$inconsistencies['1.2'] = ['menu without user', count($menu)];
				// -> DELETE

			// 1.3. upload without user
			$upload = $engine->db->load_all(
				"SELECT
					u.user_id
				FROM
					" . $prefix . "file f
					LEFT JOIN " . $prefix . "user u ON (f.user_id = u.user_id)
				WHERE
					u.user_id IS NULL");

			$inconsistencies['1.3'] = ['upload without user', count($upload)];
				// -> UPDATE / assign to new user

			// 1.4. user_settings without user
			$user_settings = $engine->db->load_all(
				"SELECT
					us.setting_id
				FROM
					" . $prefix . "user_setting us
					LEFT JOIN " . $prefix . "user u ON (us.user_id = u.user_id)
				WHERE
					u.user_id IS NULL");

			$inconsistencies['1.4'] = ['user_settings without user', count($user_settings)];
				// -> DELETE

			// 1.5. watches without user
			$watches = $engine->db->load_all(
				"SELECT
					w.watch_id
				FROM
					" . $prefix . "watch w
					LEFT JOIN " . $prefix . "user u ON (w.user_id = u.user_id)
				WHERE
					u.user_id is NULL");

			$inconsistencies['1.5'] = ['watches without user', count($watches)];
				// -> DELETE

			// 2. without page
			// 2.1. acl without page
			$acl = $engine->db->load_all(
				"SELECT
					a.*
				FROM
					" . $prefix . "acl a
					LEFT JOIN " . $prefix . "page p ON (a.page_id = p.page_id)
				WHERE
					p.page_id IS NULL");

			$inconsistencies['2.1'] = ['acl without page', count($acl)];
				// -> DELETE

			// 2.2. category_assignment without page
			$category_assignment = $engine->db->load_all(
				"SELECT
					ca.*
				FROM
					" . $prefix . "category_assignment ca
					LEFT JOIN " . $prefix . "page p ON (ca.object_id = p.page_id)
				WHERE
					ca.object_type_id = 1 AND
					p.page_id IS NULL");

			$inconsistencies['2.2'] = ['category_assignment without page', count($category_assignment)];
				// -> DELETE

			// 2.3. page_link without page
			$page_link = $engine->db->load_all(
				"SELECT
					l.link_id
				FROM
					" . $prefix . "page_link l
					LEFT JOIN " . $prefix . "page p ON (l.from_page_id = p.page_id)
				WHERE
					p.page_id IS NULL");

			$inconsistencies['2.3'] = ['page_link without page', count($page_link)];
				// -> DELETE

			// 2.4. menu without page
			$menu2 = $engine->db->load_all(
				"SELECT
					m.menu_id
				FROM
					" . $prefix . "menu m
					LEFT JOIN " . $prefix . "page p ON (m.page_id = p.page_id)
				WHERE
					p.page_id IS NULL");

			$inconsistencies['2.4'] = ['menu without page', count($menu2)];
				// -> DELETE

			// 2.5. rating without page
			$rating = $engine->db->load_all(
				"SELECT
					r.*
				FROM
					" . $prefix . "rating r
					LEFT JOIN " . $prefix . "page p ON (r.page_id = p.page_id)
				WHERE
					p.page_id IS NULL");

			$inconsistencies['2.5'] = ['rating without page', count($rating)];
				// -> DELETE

			// 2.6. referrer without page
			$referrer = $engine->db->load_all(
				"SELECT
					r.*
				FROM
					" . $prefix . "referrer r
					LEFT JOIN " . $prefix . "page p ON (r.page_id = p.page_id)
				WHERE
					p.page_id IS NULL");

			$inconsistencies['2.6'] = ['referrer without page', count($referrer)];
				// -> DELETE

			// 2.7. upload without page and not global
			$upload2 = $engine->db->load_all(
				"SELECT
					f.file_id
				FROM
					" . $prefix . "file f
					LEFT JOIN " . $prefix . "page p ON (f.page_id = p.page_id)
				WHERE
					p.page_id IS NULL AND
					f.page_id NOT LIKE 0");

			$inconsistencies['2.7'] = ['upload without page and not global', count($upload2)];
				// -> DELETE

			// 2.8. watch without page
			$watch2 = $engine->db->load_all(
				"SELECT
					w.watch_id
				FROM
					" . $prefix . "watch w
					LEFT JOIN " . $prefix . "page p ON (w.page_id = p.page_id)
				WHERE
					p.page_id IS NULL");

			$inconsistencies['2.8'] = ['watch without page', count($watch2)];
				// -> DELETE

			// 2.9. revision without page
			$revision = $engine->db->load_all(
				"SELECT
					r.revision_id
				FROM
					" . $prefix . "revision r
					LEFT JOIN " . $prefix . "page p ON (r.page_id = p.page_id)
				WHERE
					p.page_id IS NULL");

			$inconsistencies['2.9'] = ['revision without page', count($revision)];
				// -> DELETE

			// 2.10. external_link without page
			$external_link = $engine->db->load_all(
				"SELECT
					l.link_id
				FROM
					" . $prefix . "external_link l
					LEFT JOIN " . $prefix . "page p ON (l.page_id = p.page_id)
				WHERE
					p.page_id IS NULL");

			$inconsistencies['2.10'] = ['external_link without page', count($external_link)];
				// -> DELETE

			// 2.11. file_link without page
			$file_link = $engine->db->load_all(
				"SELECT
					l.file_link_id
				FROM
					" . $prefix . "file_link l
					LEFT JOIN " . $prefix . "page p ON (l.page_id = p.page_id)
				WHERE
					p.page_id IS NULL");

			$inconsistencies['2.11'] = ['file_link without page', count($file_link)];
				// -> DELETE

			// 3.1. usergroup_member without group
			$usergroup_member2 = $engine->db->load_all(
				"SELECT
					gm.*
				FROM
					" . $prefix . "usergroup_member gm
					LEFT JOIN " . $prefix . "usergroup g ON (gm.group_id = g.group_id)
				WHERE
					g.group_id IS NULL");

			$inconsistencies['3.1'] = ['usergroup_member without usergroup', count($usergroup_member2)];
				// -> DELETE

			// 3.2. page without valid user_id (e.g. deleted user)
			$page_user = $engine->db->load_all(
				"SELECT
					p.page_id
				FROM
					" . $prefix . "page p
					LEFT JOIN " . $prefix . "user u ON (p.user_id = u.user_id)
				WHERE
					p.user_id <> 0 AND
					u.user_id IS NULL");

			$inconsistencies['3.2'] = ['page without valid user_id', count($page_user)];
				// -> UPDATE / assign to new user

			// 3.3. page without valid owner_id (e.g. deleted user)
			$page_user = $engine->db->load_all(
				"SELECT
					p.page_id
				FROM
					" . $prefix . "page p
					LEFT JOIN " . $prefix . "user u ON (p.owner_id = u.user_id)
				WHERE
					p.owner_id <> 0 AND
					u.user_id IS NULL");

			$inconsistencies['3.3'] = ['page without valid owner_id', count($page_user)];
				// -> UPDATE / assign to new user

			// 3.4. revision without valid user_id (e.g. deleted user)
			$page_user = $engine->db->load_all(
				"SELECT
					r.page_id
				FROM
					" . $prefix . "revision r
					LEFT JOIN " . $prefix . "user u ON (r.user_id = u.user_id)
				WHERE
					r.user_id <> 0 AND
					u.user_id IS NULL");

			$inconsistencies['3.4'] = ['revision without valid user_id', count($page_user)];
				// -> UPDATE / assign to new user

			// 3.5. revision without valid owner_id (e.g. deleted user)
			$page_user = $engine->db->load_all(
				"SELECT
					r.page_id
				FROM
					" . $prefix . "revision r
					LEFT JOIN " . $prefix . "user u ON (r.owner_id = u.user_id)
				WHERE
					r.owner_id <> 0 AND
					u.user_id IS NULL");

			$inconsistencies['3.5'] = ['revision without valid owner_id', count($page_user)];
				// -> UPDATE / assign to new user

			// TODO: check for abandoned files, files with no reference left in the upload table

			$matches = 0;
			// check summary
			foreach ($inconsistencies as $param => $value)
			{
				if ($value[1] >= 1)
				{
					echo '<tr class="hl-setting">' .
						'<td class="label">' .
							($value[1] >= 1
								? '<strong>' . $value[0] . '</strong>'
								: '<em class="grey">' . $value[0] . '</em>') .
							'</td>' .
						'<td> </td>' .
						'<td>' .
							($value[1] >= 1
								? '<strong>' . $value[1] . '</strong>'
								: '<em class="grey">' . $value[1] . '</em>') .
						'</td>' .
						'<tr class="lined"><td colspan="5"></td></tr>' . "\n";

					$matches++;
				}
			}


			echo '</table>';

			if (!$matches)
			{
				$message = $engine->_t('InconsistenciesNone');
				$engine->show_message($message, 'info');
			}
?>
			<br>
<?php
		}
	}

	/////////////////////////////////////////////
	// B. solve inconsistencies
	/////////////////////////////////////////////
	if (isset($_POST['solve']))
	{
		if ($_REQUEST['action'] == 'check_inconsistencies')
		{
			echo '<table class="formation" style="max-width:600px; border-spacing: 1px; border-collapse: separate; padding: 4px;">';
			?>
			<tr>
				<th style="width:250px;"><?php echo $engine->_t('Inconsistencies');?></th>
				<th class="t-left"></th>
				<th class="t-left"><?php echo $engine->_t('Records');?></th>
			</tr>
			<?php
			// 1.1 usergroup_member without user
			$usergroup_member = $engine->db->sql_query(
				"DELETE
					gm.*
				FROM
					" . $prefix . "usergroup_member gm
					LEFT JOIN " . $prefix . "user u ON (gm.user_id = u.user_id)
				WHERE
					u.user_id IS NULL");

			$_solved['1.1'] = ['usergroup_member without user', $engine->config->affected_rows];

			// 1.2. menu without user
			$menu = $engine->db->sql_query(
				"DELETE
					m.*
				FROM
					" . $prefix . "menu m
					LEFT JOIN " . $prefix . "user u ON (m.user_id = u.user_id)
				WHERE
					u.user_id IS NULL");

			$_solved['1.2'] = ['menu without user', $engine->config->affected_rows];

			// 1.3. upload without user
			$admin_id = $engine->db->load_single(
				"SELECT
					user_id
				FROM
					" . $prefix . "user
				WHERE
					user_name = " . $engine->db->q($engine->db->admin_name) . "
				LIMIT 1");

			$upload = $engine->db->sql_query(
				"UPDATE
					" . $prefix . "file f " .
					"LEFT JOIN " . $prefix . "user u ON (f.user_id = u.user_id) " .
				"SET
					f.user_id = " . (int) $admin_id['user_id'] . " " .
				"WHERE
					u.user_id IS NULL");

			$_solved['1.3'] = ['upload without user', $engine->config->affected_rows];

			// 1.4. user_settings without user
			$user_settings = $engine->db->sql_query(
				"DELETE
					us.*
				FROM
					" . $prefix . "user_setting us
					LEFT JOIN " . $prefix . "user u ON (us.user_id = u.user_id)
				WHERE
					u.user_id IS NULL");

			$_solved['1.4'] = ['user_settings without user', $engine->config->affected_rows];

			// 1.5. watches without user
			$watches = $engine->db->sql_query(
				"DELETE
					w.*
				FROM
					" . $prefix . "watch w
					LEFT JOIN " . $prefix . "user u ON (w.user_id = u.user_id)
				WHERE
					u.user_id is NULL");

			$_solved['1.5'] = ['watches without user', $engine->config->affected_rows];

			// 2. without page
			// 2.1. acl without page
			$acl = $engine->db->sql_query(
				"DELETE
					a.*
				FROM
					" . $prefix . "acl a
					LEFT JOIN " . $prefix . "page p ON (a.page_id = p.page_id)
				WHERE
					p.page_id IS NULL");

			$_solved['2.1'] = ['acl without page', $engine->config->affected_rows];

			// 2.2. category_assignment without page
			$category_assignment = $engine->db->sql_query(
				"DELETE
					ca.*
				FROM
					" . $prefix . "category_assignment ca
					LEFT JOIN " . $prefix . "page p ON (ca.object_id = p.page_id)
				WHERE
					ca.object_type_id = 1 AND
					p.page_id IS NULL");

			$_solved['2.2'] = ['category_assignment without page', $engine->config->affected_rows];

			// 2.3. page_link without page
			$page_link = $engine->db->sql_query(
				"DELETE
					l.*
				FROM
					" . $prefix . "page_link l
					LEFT JOIN " . $prefix . "page p ON (l.from_page_id = p.page_id)
				WHERE
					p.page_id IS NULL");

			$_solved['2.3'] = ['page_link without page', $engine->config->affected_rows];

			// 2.4. menu without page
			$menu2 = $engine->db->sql_query(
				"DELETE
					m.*
				FROM
					" . $prefix . "menu m
					LEFT JOIN " . $prefix . "page p ON (m.page_id = p.page_id)
				WHERE
					p.page_id IS NULL");

			$_solved['2.4'] = ['menu without page', $engine->config->affected_rows];

			// 2.5. rating without page
			$rating = $engine->db->sql_query(
				"DELETE
					r.*
				FROM
					" . $prefix . "rating r
					LEFT JOIN " . $prefix . "page p ON (r.page_id = p.page_id)
				WHERE
					p.page_id IS NULL");

			$_solved['2.5'] = ['rating without page', $engine->config->affected_rows];

			// 2.6. referrer without page
			$referrer = $engine->db->sql_query(
				"DELETE
					r.*
				FROM
					" . $prefix . "referrer r
					LEFT JOIN " . $prefix . "page p ON (r.page_id = p.page_id)
				WHERE
					p.page_id IS NULL");

			$_solved['2.6'] = ['referrer without page', $engine->config->affected_rows];

			// 2.7. upload without page and not global
			$upload2 = $engine->db->sql_query(
				"DELETE
					f.*
				FROM
					" . $prefix . "file f
					LEFT JOIN " . $prefix . "page p ON (f.page_id = p.page_id)
				WHERE
					p.page_id IS NULL AND
					f.page_id NOT LIKE 0");

			$_solved['2.7']	= ['upload without page and not global', $engine->config->affected_rows];

			// 2.8. watch without page
			$watch2 = $engine->db->sql_query(
				"DELETE
					w.*
				FROM
					" . $prefix . "watch w
					LEFT JOIN " . $prefix . "page p ON (w.page_id = p.page_id)
				WHERE
					p.page_id IS NULL");

			$_solved['2.8'] = ['watch without page', $engine->config->affected_rows];

			// 2.9. revision without page
			$revision = $engine->db->sql_query(
				"DELETE
					r.*
				FROM
					" . $prefix . "revision r
					LEFT JOIN " . $prefix . "page p ON (r.page_id = p.page_id)
				WHERE
					p.page_id IS NULL");

			$_solved['2.9'] = ['revision without page', $engine->config->affected_rows];

			// 2.10. external_link without page
			$external_link = $engine->db->sql_query(
				"DELETE
					l.*
				FROM
					" . $prefix . "external_link l
					LEFT JOIN " . $prefix . "page p ON (l.page_id = p.page_id)
				WHERE
					p.page_id IS NULL");

			$_solved['2.10'] = ['external_link without page', $engine->config->affected_rows];

			// 2.11. file_link without page
			$file_link = $engine->db->sql_query(
				"DELETE
					l.*
				FROM
					" . $prefix . "file_link l
					LEFT JOIN " . $prefix . "page p ON (l.page_id = p.page_id)
				WHERE
					p.page_id IS NULL");

			$_solved['2.11'] = ['file_link without page', $engine->config->affected_rows];

			// 3.1. usergroup_member without group
			$usergroup_member2 = $engine->db->sql_query(
				"DELETE
					gm.*
				FROM
					" . $prefix . "usergroup_member gm
					LEFT JOIN " . $prefix . "usergroup g ON (gm.group_id = g.group_id)
				WHERE
					g.group_id IS NULL");

			$_solved['3.1'] = ['usergroup_member without usergroup', $engine->config->affected_rows];

			// 3.2. page without valid user_id (e.g. deleted user)
			$sys_user_id = $engine->db->load_single(
				"SELECT
					user_id
				FROM
					" . $prefix . "user
				WHERE
					user_name = " . $engine->db->q('Deleted') . "
					AND account_type = 1
				LIMIT 1");

			$page_user = $engine->db->sql_query(
				"UPDATE
					" . $prefix . "page p
					LEFT JOIN " . $prefix . "user u ON (p.user_id = u.user_id)
				SET
					p.user_id = " . (int) $sys_user_id['user_id'] . "
				WHERE
					p.user_id <> 0 AND
					u.user_id IS NULL");

			$_solved['3.2'] = ['page without valid user_id', $engine->config->affected_rows];

			// 3.3. page without valid ower_id (e.g. deleted user)
			$page_owner = $engine->db->sql_query(
				"UPDATE
					" . $prefix . "page p
					LEFT JOIN " . $prefix . "user u ON (p.owner_id = u.user_id)
				SET
					p.owner_id = " . (int) $sys_user_id['user_id'] . "
				WHERE
					p.owner_id <> 0 AND
					u.user_id IS NULL");

			$_solved['3.3'] = ['page without valid owner_id', $engine->config->affected_rows];

			// 3.4. revision without valid user_id (e.g. deleted user)
			$page_owner = $engine->db->sql_query(
				"UPDATE
					" . $prefix . "revision r
					LEFT JOIN " . $prefix . "user u ON (r.user_id = u.user_id)
				SET
					r.user_id = " . (int) $sys_user_id['user_id'] . "
				WHERE
					r.user_id <> 0 AND
					u.user_id IS NULL");

			$_solved['3.4'] = ['revision without valid user_id', $engine->config->affected_rows];

			// 3.5. revision without valid owner_id (e.g. deleted user)
			$page_owner = $engine->db->sql_query(
				"UPDATE
					" . $prefix . "revision r
					LEFT JOIN " . $prefix . "user u ON (r.owner_id = u.user_id)
				SET
					r.owner_id = " . (int) $sys_user_id['user_id'] . "
				WHERE
					r.owner_id <> 0 AND
					u.user_id IS NULL");

			$_solved['3.5'] = ['revision without valid owner_id', $engine->config->affected_rows];

			// TODO:


			$matches = 0;
			// execution summery
			foreach ($_solved as $param => $value)
			{
				if ($value[1] >= 1)
				{
					echo '<tr class="hl-setting">' .
							'<td class="label">' .
								($value[1] >= 1
									? '<strong>' . $value[0] . '</strong>'
									: '<em class="grey">' . $value[0] . '</em>') .
							'</td>' .
							'<td> </td>' .
							'<td>' .
								($value[1] >= 1
									? '<strong>' . $value[1] . '</strong>'
									: '<em class="grey">' . $value[1] . '</em>') .
							'</td>' .
						'<tr class="lined"><td colspan="5"></td></tr>' . "\n";

					$matches++;
				}
			}

			echo '</table>';

			if ($matches)
			{
				$engine->log(1, $engine->_t('InconsistenciesRemoved'));

				$message = $engine->_t('InconsistenciesDone');
				$engine->show_message($message, 'success');
			}
			else
			{
				$message = $engine->_t('InconsistenciesNone');
				$engine->show_message($message, 'info');
			}
		}
	}
?>
	<br>
<?php
	echo $engine->form_open('usersupdate');
?>
		<input type="hidden" name="action" value="check_inconsistencies">
		<input type="submit" name="check" id="submit" value="<?php echo $engine->_t('Check');?>">
		<input type="submit" name="solve" id="submit" value="<?php echo $engine->_t('Solve');?>">
<?php
	echo $engine->form_close();
}

?>
