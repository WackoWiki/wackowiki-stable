<?php

if (!defined('IN_WACKO'))
{
	exit;
}

$ap_translation = [
	'MainNote'					=> '��������: ����� ����������� ����������� ���������������� ����������� <span class="underline">������������</span> ������������� ������� ������ � �����!',

	'CategoryArray'		=> [
		'basics'		=> '������� �������',
		'preferences'	=> '���������',
		'content'		=> '�������',
		'users'			=> '�������������',
		'maintenance'	=> '������������',
		'messages'		=> 'Messages',
		'extension'		=> 'Extension',
		'database'		=> '���� ������',
	],

	// Admin panel
	'Authorization'				=> '�����������',
	'AuthorizationTip'			=> '����������, ������� ���������������� ������ (��������� �����, ��� cookies � ����� �������� ���������).',
	'NoRecoceryPassword'		=> '���������������� ������ �� �����!',
	'NoRecoceryPasswordTip'		=> '��������: ���������� ����������������� ������ ������������ ������ ��� ������������! ������� ������ � ����� �������� � ��������� ��������� ��������.',

	'ErrorLoadingModule'		=> 'Error loading admin module %1: does not exists.',

	'ApHomePage'				=> '������� �������� �����',
	'ApHomePageTip'				=> '������� ������� �������� �����, �� �������� ����� �����������������',
	'ApLogOut'					=> '�����',
	'ApLogOutTip'				=> '��������� ����������������� �������',

	'TimeLeft'					=> '�������� �������:  %1 �����',
	'ApVersion'					=> '������',

	'SiteOpen'					=> '�������',
	'SiteOpened'				=> '���� ������',
	'SiteOpenedTip'				=> '���� ������',
	'SiteClose'					=> '�������',
	'SiteClosed'				=> '���� ������',
	'SiteClosedTip'				=> '���� ������',

	// MENU
	// Config Basic module
	'config_basic'		=> [
		'name'		=> '�������',
		'title'		=> '�������� ���������',
	],

	// Config Email module
	'config_email'		=> [
		'name'		=> 'Email',
		'title'		=> 'Email settings',
	],

	// Config Filter module
	'config_filter'		=> [
		'name'		=> 'Filter',
		'title'		=> 'Filter settings',
	],

	// Config Formatter module
	'config_formatter'		=> [
		'name'		=> 'Formatter',
		'title'		=> 'Formatting options',
	],

	// Config Notifications module
	'config_notifications'		=> [
		'name'		=> 'Notifications',
		'title'		=> 'Notifications settings',
	],

	// Config Pages module
	'config_pages'		=> [
		'name'		=> '��������',
		'title'		=> '������ � ��������� ��������� �������',
	],

	// Config Permissions module
	'config_permissions'		=> [
		'name'		=> 'Permissions',
		'title'		=> 'Permissions settings',
	],

	// Config Security module
	'config_security'		=> [
		'name'		=> '������������',
		'title'		=> '��������� ��������� ������������',
	],

	// Config System module
	'config_system'		=> [
		'name'		=> '���������',
		'title'		=> '��������� ���������',
	],

	// Config Upload module
	'config_upload'		=> [
		'name'		=> 'Upload',
		'title'		=> 'Attachment settings',
	],

	// Categories module
	'content_categories'		=> [
		'name'		=> 'Categories',
		'title'		=> 'Manage categories',
	],

	// Comments module
	'content_comments'		=> [
		'name'		=> 'Comments',
		'title'		=> 'Manage comments',
	],

	// Deleted module
	'content_deleted'		=> [
		'name'		=> '��������� ���������',
		'title'		=> '���������� ������� ������� ��������� ����������',
	],

	// Files module
	'content_files'		=> [
		'name'		=> '���������� �����',
		'title'		=> '���������� ����������� �������',
	],

	// Menu module
	'content_menu'		=> [
		'name'		=> '����',
		'title'		=> 'Add, edit or remove default menu items',
	],

	// Pages module
	'content_pages'		=> [
		'name'		=> '��������',
		'title'		=> 'Manage pages',
	],

	// Polls module
	'content_polls'		=> [
		'name'		=> '���������� ��������',
		'title'		=> '��������������, ������ � ��������� �������',
	],

	// DB Backup module
	'db_backup'		=> [
		'name'		=> '��������� �����������',
		'title'		=> '��������� ����������� ������',
	],

	// DB Convert module
	'db_convert'		=> [
		'name'		=> 'Convert',
		'title'		=> 'Converting Tables or Columns',
	],

	// DB optimize module
	'db_optimize'		=> [
		'name'		=> '����������� ����',
		'title'		=> '����������� ���� ������',
	],

	// DB Repair module
	'db_repair'		=> [
		'name'		=> 'Repair',
		'title'		=> 'Repair and Optimize Database',
	],

	// DB Restore module
	'db_restore'		=> [
		'name'		=> '�������������� ����',
		'title'		=> '�������������� ��������� ������',
	],

	// Dashboard module
	'lock'		=> [
		'name'		=> '������� ����',
		'title'		=> '���������� WackoWiki',
	],

	// Inconsistencies module
	'maint_inconsistencies'		=> [
		'name'		=> 'Inconsistencies',
		'title'		=> 'Fixing Data Inconsistencies',
	],

	// Data Synchronization module
	'maint_resync'		=> [
		'name'		=> '������������� ������',
		'title'		=> '������������� ���� ������',
	],

	// Transliterate module
	'maint_transliterate'		=> [
		'name'		=> 'Transliterate',
		'title'		=> 'Update the supertag in the database records',
	],

	// Mass email module
	'massemail'		=> [
		'name'		=> 'Mass email',
		'title'		=> 'Mass email',
	],

	// System message module
	'messages'		=> [
		'name'		=> 'System message',
		'title'		=> 'System messages',
	],

	// System Info module
	'system_info'		=> [
		'name'		=> 'System Info',
		'title'		=> 'System Informations',
	],

	// System log module
	'system_log'		=> [
		'name'		=> '��������� ������',
		'title'		=> '������ ��������� �������',
	],

	// Statistics module
	'system_statistics'		=> [
		'name'		=> 'Statistics',
		'title'		=> 'Show statistics',
	],

	// Bad Behavior module
	'badbehavior'		=> [
		'name'		=> 'Bad Behavior',
		'title'		=> 'Bad Behavior',
	],

	// Registration Approval module
	'user_approve'		=> [
		'name'		=> 'Approve',
		'title'		=> 'User registration approval',
	],

	// Groups module
	'user_groups'		=> [
		'name'		=> '�����',
		'title'		=> 'Group management',
	],

	// User module
	'user_users'		=> [
		'name'		=> '�������������',
		'title'		=> 'User management',
	],

	'LogFilterTip'				=> '������������� ������� �� ���������',
	'LogLevel'					=> '�������',
	'LogLevelNotLower'			=> '�� ����, ���',
	'LogLevelNotHigher'			=> '�� ����, ���',
	'LogLevelEqual'				=> '�������������',
	'LogNoMatch'				=> '��� �������, ��������������� ���������',
	'LogDate'					=> '����',
	'LogEvent'					=> '�������',
	'LogUsername'				=> '������������',

	'PurgeSessions'				=> '��������',
	'PurgeSessionsTip'			=> '���������� ��� ������',
	'PurgeSessionsConfirm'		=> '�������� � ��������� ������? ��� ��������� ������ ���� ������� �������������.',
	'PurgeSessionsExplain'		=> 'Purge all sessions. This will log out all users by truncating the auth_token table.',
	'PurgeSessionsDone'			=> '������ ���������.',

	// log
	'LogLevel1'					=> '�����������',
	'LogLevel2'					=> '���������',
	'LogLevel3'					=> '�������',
	'LogLevel4'					=> '�������',
	'LogLevel5'					=> '������',
	'LogLevel6'					=> '�����������',
	'LogLevel7'					=> '����������',

	// Massemail
	'SendToGroup'				=> 'Send to group',
	'SendToUser'				=> 'Send to user',

	// User approval module
	'UserApproveInfo'			=> '��������������� ����� �������������.',
	'Approve'					=> '���������',
	'Deny'						=> '��������',
	'Pending'					=> '������� �������',
	'Approved'					=> '��������',
	'Denied'					=> '��������',

	// DB Backup module
	'BackupStructure'			=> '���������',
	'BackupData'				=> '������',
	'BackupFolder'				=> '�������',
	'BackupTable'				=> '�������',
	'BackupCluster'				=> '�������',
	'BackupFiles'				=> '�����',
	'BackupSettings'			=> '������� ��������� ����� ��������������. '.
									'�������� ������� �� ������ �� ��������� ����������� ���������� ������ � ������ ���� (��� �� ������ ��� ������ ����������� ���������).<br /> '.
									'<br /> '.
									'<span class="underline">��������</span>: �� ��������� ������ ���������� �� ���� ������ WackoWiki, ��� �������� ����� ��������, ������� �� ������ ��������� ����� �� ����� �������������������, '.
									'����������, ��� �������������� ������ ��������� ������� ��� ���������� ������. '.
									'��� ������ ����������� ������ � ������ ��������� ����� ���������� ���������� <em>������ �������������� ���� ���� ������ (��������� � ����������) ��� �������� ��������</em>.',
	'BackupCompleted'			=> '��������� ����������� � ��������� ���������.<br />'.
									'����� ��������� ����� �������� � ����� � ��������� "(����)��������_(�����)������" � ����� files/backup.<br />'.
									'��� ��� ��������� ����������� FTP (�� �������� ��� ����������� ��������� ��������� ��������� � ����� ������ � ����������).<br />'.
									'������������ ��������� ����� ��� ������� ����� ����� � ������� <a href="?mode=db_restore">��������������</a>.',
	'LogSavedBackup'			=> 'Saved backup database ##%1##',

	// DB Restore module
	'RestoreInfo'				=> '�� ������ ������������ ����� �� ��������� ��������� �������, ���� ������� ��� � �������.',
	'ConfirmDbRestore'			=> 'Do you want to restore backup',
	'ConfirmDbRestoreInfo'		=> 'Please wait this can take some minutes.',
	'RestoreWrongVersion'		=> 'Wrong WackoWiki version!',
	'BackupDelete'				=> 'Are you sure you want to remove backup',
	'BackupDeleteInfo'			=> '',
	'RestoreOptions'			=> '�������������� ����� ��� ��������������',
	'RestoreOptionsInfo'		=> '* ����� ��������������� ��������� ����� <span class="underline">��������</span> WackoWiki, '.
									'������� ������� �� ������������ (���� ������������� ������ ���������� �� ������������������� ���������). '.
									'����� �������, � �������� �������������� ����� ����������� ������������� ������. '.
									'� ������� ������ ��� ��� ����� �������� �������� �� ��������� ����� (� ������� SQL-���������� <code>REPLACE</code>), <br />'.
									'�� ���� ���� ������ ����������, ��� ��������� ����� ��������� (����� ��������� ������� �������� �������), <br />'.
									'� ��������� � ������� ������ ������ � ������ ������� (SQL-����������� <code>INSERT IGNORE</code>).<br />'.
									'<span class="underline">��������</span>: ��� �������������� ������ ��������� ����� ����� ��� ����� �� ����� ��������.<br /> '.
									'<br /> '.
									'** ���� ��������� ����� �������� ���������������� ����� (���������� � ������������, ����� ���� � ��.), �� � ������� ������ ��� �������������� ��� ������� ����������� �����, ����������� � ��� �� ���������. '.
									'��� ����� ��������� ��������� ������� ����� ������, � ������������ �� ��������� ����� ������ ����� (������������� �� �������) �����. ',
	'IgnoreDuplicatedKeys'		=> '������������ ����������� ����� ������� (�� ��������)',
	'IgnoreSameFiles'			=> '������������ ����������� ����� (�� ��������������)',
	'NoBackupsAvailable'		=> 'No backups available.',
	'BackupEntireSite'			=> '���� ����',
	'BackupRestored'			=> '��������� ����� �������������, ����� ���������� �������� ����. ����� ������� ������ ��������� �����, ������� �����',
	'BackupRemoved'				=> '��������� ��������� ����� ������� �������.',
	'LogRemovedBackup'			=> 'Removed database backup ##%1##',

	// User module
	'UsersAdded'				=> '������������ ��������',
	'UsersDeleteInfo'			=> '[User delete Info here..]',
	'UserEditButton'			=> 'Edit',
	'UserEnabled'				=> '��������',
	'UsersAddNew'				=> '�������� ������ ������������',
	'UsersDelete'				=> 'Are you sure you want to remove user ',
	'UsersDeleted'				=> 'The user was deleted from the database.',
	'UsersRename'				=> 'Rename the user',
	'UsersRenameInfo'			=> '* Note: Change will affect all pages that are assigned to that user.',
	'UsersUpdated'				=> 'User successfully updated.',

	'UserName'					=> 'Username',
	'UserRealname'				=> 'Realname',
	'UserEmail'					=> 'Email',
	'UserIP'					=> 'IP',
	'UserLanguage'				=> 'Language',
	'UserSignuptime'			=> 'Signuptime',
	'UserActions'				=> 'Actions',
	'NoMatchingUser'			=> 'No users that meet the criteria',

	// Groups module
	'GroupsMembersFor'			=> '��������� ������',
	'GroupsDescription'			=> '��������',
	'GroupsModerator'			=> '���������',
	'GroupsOpen'				=> 'Open',
	'GroupsActive'				=> 'Active',
	'GroupsTip'					=> '������������� ������',
	'GroupsUpdated'				=> '������ ���������',
	'GroupsAlreadyExists'		=> '��� ������ ��� c���������.',
	'GroupsAdded'				=> '������ ���������.',
	'GroupsRenamed'				=> '������ �������������.',
	'GroupsDeleted'				=> '������ ������� �� ���� ������ � ���� �������.',
	'GroupsAdd'					=> '�������� ����� ������',
	'GroupsRename'				=> '������� ������',
	'GroupsRenameInfo'			=> '* Note: Change will affect all pages that are assigned to that group.',
	'GroupsDelete'				=> 'Are you sure you want to remove group ',
	'GroupsDeleteInfo'			=> '* Note: Change will affect all members that are assigned to that group.',
	'GroupsStoreButton'			=> '��������� ������',
	'GroupsSaveButton'			=> 'Submit',
	'GroupsCancelButton'		=> '��������',
	'GroupsAddButton'			=> '��������',
	'GroupsEditButton'			=> '��������',
	'GroupsRemoveButton'		=> '�������',
	'GroupsEditInfo'			=> '��� �������������� ������ ����� �������� �����-������.',

	'MembersAddNew'				=> '�������� ������ ���������',
	'MembersAdded'				=> '�������� �������� � ������.',
	'MembersRemove'				=> '����� ����� �� �������� ��������� ',
	'MembersRemoved'			=> '�������� �� ������ ������.',
	'MembersDeleteInfo'			=> '* Note: Change will affect all members that are assigned to that group.',

];

?>
