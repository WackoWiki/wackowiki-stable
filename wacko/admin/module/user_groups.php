<?php

if (!defined('IN_WACKO'))
{
	exit;
}

########################################################
##   User Groups                                      ##
########################################################

$module['user_groups'] = array(
		'order'	=> 420,
		'cat'	=> 'Users',
		'status'=> (RECOVERY_MODE ? false : true),
		'mode'	=> 'user_groups',
		'name'	=> 'Groups',
		'title'	=> 'Group management',
	);

########################################################

function admin_user_groups(&$engine, &$module)
{
	$where			= '';
	$order			= '';
	$usergroup		= '';
	$orderuser		= '';

/*	TODO:
 * Pre-defined groups
 * Pre-defined groups are special groups, they cannot be deleted or directly modified. However you can still add users and alter basic settings.
 *
 * User defined groups
 * These are groups created by you or another admin on this board. You can manage memberships as well as edit group properties or even delete the group.
*/
?>
	<h1><?php echo $module['title']; ?></h1>
	<br />
	<p>
		From this panel you can administrate all your usergroups. You can delete, create and edit existing groups. Furthermore, you may choose group leaders, toggle open/hidden/closed group status and set the group name and description.
	</p>
	<br />
<?php

	/////////////////////////////////////////////
	//   list change/update
	/////////////////////////////////////////////

	// update groups list
	if (isset($_POST['save']))
	{
		$this->log(4, "Updated usergroup members ");
		$this->set_message($this->get_translation('GroupsUpdated'));
	}
	else
	{
		// get group
		if (isset($_GET['group_id']) || isset($_POST['add_member'])|| isset($_POST['remove_member']))
		{
			$group_id = (isset($_GET['group_id']) ? $_GET['group_id'] : $_POST['group_id']);
			$usergroup = $engine->load_single(
				"SELECT group_name ".
				"FROM {$engine->config['table_prefix']}usergroup ".
				"WHERE group_id = '".(int)$group_id."' ".
				"LIMIT 1");

			// add member into group
			if (isset($_POST['add_member']) && isset($_POST['new_member_id']))
			{
				$engine->sql_query(
					"INSERT INTO {$engine->config['table_prefix']}usergroup_member SET ".
						"group_id	= '".(int)$_POST['group_id']."', ".
						"user_id	= '".(int)$_POST['new_member_id']."'");

					$engine->config->invalidate_config_cache();
					$engine->show_message($engine->get_translation('MembersAdded'), 'success');
					$engine->log(4, "Added member //'{$_POST['new_member_id']}'// into group //'{$usergroup['group_name']}'// ");
					unset($_POST['add_member']);
			}

			// remove member from group
			else if (isset($_POST['remove_member']) && isset($_POST['member_id']) && isset($_POST['group_id']))
			{
				$engine->sql_query(
					"DELETE FROM {$engine->config['table_prefix']}usergroup_member ".
					"WHERE group_id = '".(int)$_POST['group_id']."' ".
						"AND user_id = '".(int)$_POST['member_id']."'");

				$engine->config->invalidate_config_cache();
				$engine->show_message($engine->get_translation('MembersRemoved'), 'success');
				$engine->log(4, "Removed member //'{$_POST['member_id']}'// from group //'{$usergroup['group_name']}'// (//'{$_POST['group_id']}'//)");
			}

			/////////////////////////////////////////////
			//   edit forms
			/////////////////////////////////////////////

			// add member into group
			if (isset($_POST['add_member']))
			{
				$subqery_members = "SELECT m.user_id ".
					"FROM {$engine->config['table_prefix']}usergroup g ".
						"INNER JOIN {$engine->config['table_prefix']}usergroup_member m ON (g.group_id = m.group_id) ".
						"INNER JOIN {$engine->config['table_prefix']}user u ON (m.user_id = u.user_id) ".
					"WHERE g.group_id = '".(int)$group_id."' ";

				$available_users = $engine->load_all(
					"SELECT user_id, user_name ".
					"FROM ".$engine->config['user_table']." ".
					"WHERE user_id NOT IN ( ".$subqery_members." ) ".
						"AND account_type = '0' ".
					"ORDER BY BINARY user_name");

				#$engine->debug_print_r($available_users);

				echo $engine->form_open('add_group_member', '', 'post', true, '', '');

				echo '<input type="hidden" name="group_id" value="'.$group_id.'" />'.
				'<table class="formation">'.
					'<tr>
						<td>
							<label for="new_member_id">'.$engine->get_translation('MembersAddNew').'</label>
						</td>'.
						'<td>
							<select id="new_member_id" name="new_member_id">
								<option value=""></option>';

						if ($available_users)
						{
							foreach($available_users as $user)
							{
								echo '<option value="'.$user['user_id'].'">'.htmlspecialchars($user['user_name'], ENT_COMPAT | ENT_HTML401, HTML_ENTITIES_CHARSET)."</option>\n";
							}
						}

						echo '</select>
						</td>
					</tr>'.
					'<tr>
						<td>
							<br />
							<input type="submit" id="submit" name="add_member" value="'.$engine->get_translation('GroupsSaveButton').'" /> '.
							'<a href="'.$engine->href().'" style="text-decoration: none;"><input type="button" id="button" value="'.$engine->get_translation('GroupsCancelButton').'" /></a>'.
						'</td>
					</tr>'.
				'</table>
				<br />';

				echo $engine->form_close();
			}

			// remove member from group
			if (isset($_POST['remove_member']) && isset($_POST['change_member']))
			{
				if ($member = $engine->load_single(
					"SELECT user_name ".
					"FROM {$engine->config['table_prefix']}user ".
					"WHERE user_id = '".(int)$_POST['change_member']."' ".
					"LIMIT 1"))
				{
					echo $engine->form_open('remove_group_member', '', 'post', true, '', '');

					echo '<input type="hidden" name="group_id" value="'.$group_id.'" />'.
						'<input type="hidden" name="member_id" value="'.htmlspecialchars($_POST['change_member'], ENT_COMPAT | ENT_HTML401, HTML_ENTITIES_CHARSET).'" />'."\n".
					'<table class="formation">'.
						'<tr>
							<td>
								<label for="">'.$engine->get_translation('MembersRemove').' \'<code>'.htmlspecialchars($member['user_name'], ENT_COMPAT | ENT_HTML401, HTML_ENTITIES_CHARSET).'</code>\'?</label> '.
								'<input type="submit" id="submit" name="remove_member" value="yes" style="width:40px;" /> '.
								'<a href="'.$engine->href().'" style="text-decoration: none;"><input type="button" id="button" value="no" style="width: 40px;" /></a>'.
								'<br /><small>'.$engine->get_translation('MembersDeleteInfo').'</small>'.
							'</td>
						</tr>'.
					'</table><br />';

					echo $engine->form_close();
				}
			}

		}

		// add group
		if (isset($_POST['create']) && isset($_POST['new_group_name']))
		{
			// do we have identical names?
			if ($engine->load_single(
			"SELECT group_id FROM {$engine->config['table_prefix']}usergroup ".
			"WHERE group_name = '".quote($engine->dblink, $_POST['new_group_name'])."' ".
			"LIMIT 1"))
			{
				$engine->show_message($engine->get_translation('GroupsAlreadyExists'));
				$_POST['change'] = $_POST['group_id'];
				$_POST['create'] = 1;
			}
			else
			{
				$engine->sql_query(
					"INSERT INTO {$engine->config['table_prefix']}usergroup SET ".
						"created		= UTC_TIMESTAMP(), ".
						"description	= '".quote($engine->dblink, $_POST['description'])."', ".
						"moderator_id		= '".(int)$_POST['moderator_id']."', ".
						"group_name		= '".quote($engine->dblink, $_POST['new_group_name'])."', ".
						"open			= '".(int)$_POST['open']."', ".
						"active			= '".(int)$_POST['active']."'");

				$engine->config->invalidate_config_cache();
				$engine->show_message($engine->get_translation('GroupsAdded'), 'success');
				$engine->log(4, "Created a new group //'{$_POST['new_group_name']}'//");
				unset($_POST['create']);
			}
		}
		// edit group
		else if (isset($_POST['edit']) && isset($_POST['group_id']) && (isset($_POST['new_group_name']) || isset($_POST['moderator_id'])))
		{
			// do we have identical names?
			if ($engine->load_single(
			"SELECT group_id FROM {$engine->config['table_prefix']}usergroup ".
			"WHERE group_name = '".quote($engine->dblink, $_POST['new_group_name'])."' AND group_id <> '".(int)$_POST['group_id']."' ".
			"LIMIT 1"))
			{
				$engine->set_message($engine->get_translation('GroupsAlreadyExists'));
				$_POST['change'] = $_POST['group_id'];
				$_POST['edit'] = 1;
			}
			else
			{
				$engine->sql_query(
					"UPDATE {$engine->config['table_prefix']}usergroup SET ".
						"group_name		= '".quote($engine->dblink, $_POST['new_group_name'])."', ".
						"description	= '".quote($engine->dblink, $_POST['new_description'])."', ".
						"moderator_id	= '".(int)$_POST['moderator_id']."', ".
						"open			= '".(int)$_POST['open']."', ".
						"active			= '".(int)$_POST['active']."' ".
					"WHERE group_id = '".(int)$_POST['group_id']."' ".
					"LIMIT 1");

				$engine->show_message($engine->get_translation('GroupsRenamed'));
				$engine->log(4, "Group //'{$usergroup['group_name']}'// (//'{$_POST['group_id']}'//) renamed //'{$_POST['new_group_name']}'//");
			}
		}
		// delete group
		else if (isset($_POST['delete']) && isset($_POST['group_id']))
		{
			$usergroup = $engine->load_single(
				"SELECT group_name
				FROM {$engine->config['table_prefix']}usergroup
				WHERE group_id = '".(int)$_POST['group_id']."'
				LIMIT 1");

			$engine->sql_query(
				"DELETE FROM {$engine->config['table_prefix']}usergroup ".
				"WHERE group_id = '".(int)$_POST['group_id']."'");
			$engine->sql_query(
				"DELETE FROM {$engine->config['table_prefix']}usergroup_member ".
				"WHERE group_id = '".(int)$_POST['group_id']."'");

			$engine->config->invalidate_config_cache();
			$engine->show_message($engine->get_translation('GroupsDeleted'), 'success');
			$engine->log(4, "Removed group //'{$usergroup['group_name']}'//");

			unset($_GET['group_id']);
			unset($_POST['group_id']);
		}
	}

	/////////////////////////////////////////////
	//   edit forms
	/////////////////////////////////////////////

		// add new group
		if (isset($_POST['create']))
		{
			echo $engine->form_open('add_group', '', 'post', true, '', '');

			echo '<table class="formation">'.
					'<tr>
						<td>
							<label for="new_group_name">'.$engine->get_translation('GroupsAdd').'</label>
						</td>'.
						'<td>
							<input type="text" id="new_group_name" name="new_group_name" value="'.( isset($_POST['new_group_name']) ? htmlspecialchars($_POST['new_group_name'], ENT_COMPAT | ENT_HTML401, HTML_ENTITIES_CHARSET) : '' ).'" size="20" maxlength="100" />
						</td>
					</tr>'.
					'<tr>
						<td>
							<label for="description">'.$engine->get_translation('GroupsDescription').'</label>
						</td>'.
						'<td>
							<input type="text" id="description" name="description" value="'.( isset($_POST['description']) ? htmlspecialchars($_POST['description'], ENT_COMPAT | ENT_HTML401, HTML_ENTITIES_CHARSET) : '' ).'" size="50" maxlength="100" />
						</td>
					</tr>'.
					'<tr>
						<td>
							<label for="moderator_id">'.$engine->get_translation('GroupsModerator').'</label>
						</td>'.
						'<td>
							<select id="moderator_id" name="moderator_id">
								<option value=""></option>';

					if ($users = $engine->load_users())
					{
						foreach($users as $user)
						{
							echo '<option value="'.$user['user_id'].'">'.htmlspecialchars($user['user_name'], ENT_COMPAT | ENT_HTML401, HTML_ENTITIES_CHARSET)."</option>\n";
						}
					}

						echo '</select>
						</td>
					</tr>'.
					'<tr>
						<td>
							<label for="open">'.$engine->get_translation('GroupsOpen').'</label>
						</td>'.
						'<td>
							<input type="checkbox" id="open" name="open" value="1" '. ( !isset($_POST['open']) ? ' checked="checked"' : '' ).' />
						</td>
					</tr>'.
					'<tr>
						<td>
							<label for="active">'.$engine->get_translation('GroupsActive').'</label>
		 				</td>'.
						'<td>
		 					<input type="checkbox" id="active" name="active" value="1" '. ( !isset($_POST['active']) ? ' checked="checked"' : '' ).' />
		 				</td>
					</tr>'.
					'<tr>
						<td>
		 					<br />
		 					<input type="submit" id="submit" name="create" value="'.$engine->get_translation('GroupsSaveButton').'" /> '.
							'<a href="'.$engine->href().'" style="text-decoration: none;"><input type="button" id="button" value="'.$engine->get_translation('GroupsCancelButton').'" /></a>'.
						'</td>
					</tr>'.
				'</table>
				<br />';

			echo $engine->form_close();
		}
		// edit group
		else if (isset($_POST['edit']) && isset($_POST['change']))
		{
			if ($usergroup = $engine->load_single(
				"SELECT group_name, description, moderator_id, open, active
				FROM {$engine->config['table_prefix']}usergroup
				WHERE group_id = '".(int)$_POST['change']."'
				LIMIT 1"))
			{
				echo $engine->form_open('edit_group', '', 'post', true, '', '');

				echo '<input type="hidden" name="group_id" value="'.htmlspecialchars($_POST['change'], ENT_COMPAT | ENT_HTML401, HTML_ENTITIES_CHARSET).'" />'."\n".
					'<table class="formation">'.
					'<tr><td>
						<label for="new_group_name">'.$engine->get_translation('GroupsRename').' \'<code>'.htmlspecialchars($usergroup['group_name'], ENT_COMPAT | ENT_HTML401, HTML_ENTITIES_CHARSET).'</code>\' in</label>
					</td>'.
					'<td>
						<input type="text" id="new_group_name" name="new_group_name" value="'.( isset($_POST['new_group_name']) ? htmlspecialchars($_POST['new_group_name'], ENT_COMPAT | ENT_HTML401, HTML_ENTITIES_CHARSET) : htmlspecialchars($usergroup['group_name'], ENT_COMPAT | ENT_HTML401, HTML_ENTITIES_CHARSET) ).'" size="20" maxlength="100" />
					</td></tr>'.
					'<tr><td>
							<label for="new_description">'.$engine->get_translation('GroupsDescription').'</label>
					</td>'.
					'<td>
						<input type="text" id="new_description" name="new_description" value="'.( isset($_POST['new_description']) ? htmlspecialchars($_POST['new_description'], ENT_COMPAT | ENT_HTML401, HTML_ENTITIES_CHARSET) : htmlspecialchars($usergroup['description'], ENT_COMPAT | ENT_HTML401, HTML_ENTITIES_CHARSET) ).'" size="50" maxlength="100" />
					</td></tr>'.
					'<tr><td>
						<label for="moderator_id">'.$engine->get_translation('GroupsModerator').'</label>
					</td>'.
					'<td>
						<select id="moderator_id" name="moderator_id">'.
					'<option value=""></option> ';

					if ($users = $engine->load_users())
					{
						foreach($users as $user)
						{
							echo '<option value="'.$user['user_id'].'" '.($usergroup['moderator_id'] == $user['user_id'] ? ' selected="selected"' : '').'>'.$user['user_name']."</option>\n";
						}
					}

				echo '</select>
					</td></tr>'.
					'<tr><td>
							<label for="open">'.$engine->get_translation('GroupsOpen').'</label>
					</td>'.
					'<td>
							<input type="checkbox" id="open" name="open" value="1" '. ( isset($_POST['open']) || $usergroup['open'] == 1 ? ' checked="checked"' : '' ).' />
					</td></tr>'.
					'<tr><td>
							<label for="active">'.$engine->get_translation('GroupsActive').'</label>
					</td>'.
					'<td>
							<input type="checkbox" id="active" name="active" value="1" '. ( isset($_POST['active']) || $usergroup['active'] == 1 ? ' checked="checked"' : '' ).' />
					</td></tr>'.
					'<tr><td>
						<br />
						<input type="submit" id="submit" name="edit" value="'.$engine->get_translation('GroupsSaveButton').'" /> '.
						'<a href="'.$engine->href().'" style="text-decoration: none;"><input type="button" id="button" value="'.$engine->get_translation('GroupsCancelButton').'" /></a>'.
						'<br /><small>'.$engine->get_translation('GroupsRenameInfo').'</small>'.
					'</td></tr>'.
					'</table><br />';

				echo $engine->form_close();
			}
		}
		// delete group
		if (isset($_POST['delete']) && isset($_POST['change']))
		{
			if ($usergroup = $engine->load_single(
				"SELECT group_name
				FROM {$engine->config['table_prefix']}usergroup
				WHERE group_id = '".(int)$_POST['change']."'
				LIMIT 1"))
			{
				echo $engine->form_open('delete_group', '', 'post', true, '', '');

				echo '<input type="hidden" name="group_id" value="'.htmlspecialchars($_POST['change'], ENT_COMPAT | ENT_HTML401, HTML_ENTITIES_CHARSET).'" />'."\n".
					'<table class="formation">'.
						'<tr>
							<td>
								<label for="">'.$engine->get_translation('GroupsDelete').' \'<code>'.htmlspecialchars($usergroup['group_name'], ENT_COMPAT | ENT_HTML401, HTML_ENTITIES_CHARSET).'</code>\'?</label> '.
								'<input type="submit" id="submit" name="delete" value="yes" style="width:40px;" /> '.
								'<a href="'.$engine->href().'" style="text-decoration: none;"><input type="button" id="button" value="no" style="width:40px;" /></a>'.
								'<br /><small>'.$engine->get_translation('GroupsDeleteInfo').'</small>'.
							'</td>
						</tr>'.
					'</table>
					<br />';

				echo $engine->form_close();
			}

			echo "<!-- end trying to delete group -->";
		}
?>
<?php

	/////////////////////////////////////////////
	//   building lists
	/////////////////////////////////////////////

	// get group
	if (isset($_GET['group_id']) || isset($_POST['group_id']))
	{
		$group_id = (isset($_GET['group_id']) ? $_GET['group_id'] : $_POST['group_id']);
		$usergroup = $engine->load_single(
			"SELECT group_id, moderator_id, group_name ".
			"FROM {$engine->config['table_prefix']}usergroup ".
			"WHERE group_id = '".(int)$group_id."' ".
			"LIMIT 1");

		echo "<h2>".$engine->get_translation('GroupsMembersFor').": ".$usergroup['group_name']."</h2>";

		$members = $engine->load_all(
			"SELECT m.user_id, user_name ".
			"FROM {$engine->config['table_prefix']}usergroup g ".
				"INNER JOIN {$engine->config['table_prefix']}usergroup_member m ON (g.group_id = m.group_id) ".
				"INNER JOIN {$engine->config['table_prefix']}user u ON (m.user_id = u.user_id) ".
			"WHERE g.group_id = '".(int)$group_id."' ");

		echo $engine->form_open('get_group', '', 'post', true, '', '');
?>
		<input type="hidden" name="group_id" value="<?php echo $group_id; ?>" />

		<table style="padding: 3px;" class="formation listcenter">
			<tr>
				<th style="width:5px;"></th>
				<th style="width:5px;">ID</th>
				<th style="width:20px;"><a href="?mode=groups&order=<?php echo $orderuser; ?>">Username</a></th>
			</tr>
<?php
		foreach ($members as $member)
		{
			echo '<tr class="lined">'."\n".
					'<td>
						<input type="radio" name="change_member" value="'.$member['user_id'].'" /></td>'.
					'<td>'.$member['user_id'].'</td>'.
					'<td style="padding-left:5px; padding-right:5px;"><strong><a href="?mode=users&user_id='.$member['user_id'].'">'.$member['user_name'].'</a></strong></td>'.
				'</tr>';
		}
			?>
		</table>
		<?php

		/////////////////////////////////////////////
		//   control buttons
		/////////////////////////////////////////////

		echo '<br /><input type="submit" id="button" name="add_member" value="'.$engine->get_translation('GroupsAddButton').'" /> ';
		echo '<input type="submit" id="button" name="remove_member" value="'.$engine->get_translation('GroupsRemoveButton').'" /> ';
		echo '<a href="'.$engine->href().'" style="text-decoration: none;"><input type="button" value="'.$engine->get_translation('GroupsCancelButton').'" /></a>';

		echo $engine->form_close();
	}
	else
	{
		// set created ordering
		if (isset($_GET['order']) && $_GET['order'] == 'created_asc')
		{
			$order		= 'ORDER BY g.created ASC ';
			$created	= 'created_desc';
		}
		else if (isset($_GET['order']) && $_GET['order'] == 'created_desc')
		{
			$order		= 'ORDER BY g.created DESC ';
			$created	= 'created_asc';
		}
		else
		{
			$created	= 'created_asc';
		}

		// set usergroup ordering
		if (isset($_GET['order']) && $_GET['order'] == 'group_asc')
		{
			$order		= 'ORDER BY g.group_name DESC ';
			$ordergroup	= 'user_desc';
		}
		else if (isset($_GET['order']) && $_GET['order'] == 'group_desc')
		{
			$order		= 'ORDER BY g.group_name ASC ';
			$ordergroup	= 'group_asc';
		}
		else
		{
			$ordergroup	= 'group_desc';
		}

		// set members ordering
		if (isset($_GET['order']) && $_GET['order'] == 'members_asc')
		{
			$order			= 'ORDER BY members DESC ';
			$ordermembers	= 'user_desc';
		}
		else if (isset($_GET['order']) && $_GET['order'] == 'members_desc')
		{
			$order			= 'ORDER BY members ASC ';
			$ordermembers	= 'members_asc';
		}
		else
		{
			$ordermembers	= 'members_desc';
		}

		// filter by lang
		if (isset($_GET['moderator_id']))
		{
			$where = "WHERE g.moderator_id = '".(int)$_GET['moderator_id']."' ";
		}

		// entries to display
		$limit = 100;

		// collecting data
		$count = $engine->load_single(
			"SELECT COUNT(group_name) AS n ".
			"FROM {$engine->config['table_prefix']}usergroup ".
			( $where ? $where : '' )
			);

		$order_pagination	= isset($_GET['order']) ? $_GET['order'] : '';
		$pagination			= $engine->pagination($count['n'], $limit, 'p', 'mode=groups'.(!empty($order_pagination) ? '&order='.htmlspecialchars($order_pagination, ENT_COMPAT | ENT_HTML401, HTML_ENTITIES_CHARSET) : ''), '', 'admin.php');

		$groups = $engine->load_all(
			"SELECT g.*, u.user_name, COUNT(m.user_id) AS members ".
			"FROM {$engine->config['table_prefix']}usergroup g ".
				"LEFT JOIN {$engine->config['table_prefix']}user u ON (g.moderator_id = u.user_id) ".
				"LEFT JOIN ".$engine->config['table_prefix']."usergroup_member m ON (m.group_id = g.group_id) ".
			( $where ? $where : '' ).
			"GROUP BY g.group_id ".
			( $order ? $order : 'ORDER BY group_id DESC ' ).
			"LIMIT {$pagination['offset']}, $limit");

	/////////////////////////////////////////////
	//   print list
	/////////////////////////////////////////////

		echo $engine->form_open('groups', '', 'post', true, '', '');

			/////////////////////////////////////////////
			//   control buttons
			/////////////////////////////////////////////

			$control_buttons =	'<br /><input type="submit" id="button" name="create" value="'.$engine->get_translation('GroupsAddButton').'" /> '.
								'<input type="submit" id="button" name="edit" value="'.$engine->get_translation('GroupsEditButton').'" /> '.
								'<input type="submit" id="button" name="delete" value="'.$engine->get_translation('GroupsRemoveButton').'" /> ';

			echo $control_buttons;

			$engine->print_pagination($pagination);
?>
			<table style="padding: 3px;" class="formation listcenter">
				<tr>
					<th style="width:5px;"></th>
					<th style="width:5px;">ID</th>
					<th style="width:20px;"><a href="<?php echo $engine->href().'&amp;order='.$ordergroup; ?>">Group</a></th>
					<th>Description</th>
					<th style="width:20px;">Moderator</th>
					<th style="width:20px;"><a href="<?php echo $engine->href().'&amp;order='.$ordermembers; ?>">Members</a></th>
					<th style="width:20px;">Open</th>
					<th style="width:20px;">Active</th>
					<th style="width:20px;"><a href="<?php echo $engine->href().'&amp;order='.$created; ?>">Created</a></th>
				</tr>
<?php
		if ($groups)
		{
			foreach ($groups as $row)
			{
				echo '<tr class="lined">'."\n".
						'<td>
							<input type="radio" name="change" value="'.$row['group_id'].'" /></td>'.
						'<td>'.$row['group_id'].'</td>'.
						'<td style="text-align:left; padding-left:5px; padding-right:5px;"><strong><a href="'.$engine->href().'&amp;group_id='.$row['group_id'].'">'.$row['group_name'].'</a></strong></td>'.
						'<td>'.$row['description'].'</td>'.
						'<td><small><a href="'.$engine->href().'&amp;moderator='.$row['moderator_id'].'">'.$row['user_name'].'</a></small></td>'.
						'<td>'.$row['members'].'</td>'.
						'<td>'.$row['open'].'</td>'.
						'<td>'.$row['active'].'</td>'.
						'<td><small>'.date($engine->config['date_precise_format'], strtotime($row['created'])).'</small></td>'.
					'</tr>';
			}
		}
		else
		{
			echo '<tr><td colspan="5"><br /><em>No groups that meet the criteria</em></td></tr>';
		}
?>
			</table>
<?php
		$engine->print_pagination($pagination);

		/////////////////////////////////////////////
		//   control buttons
		/////////////////////////////////////////////

		echo $control_buttons;
		echo $engine->form_close();
	}
}

?>
