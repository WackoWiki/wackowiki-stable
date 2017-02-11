<?php

if (!defined('IN_WACKO'))
{
	exit;
}

$ap_translation = [
	'MainNote'				=> 'Note: Before the administration of technical activities strongly are encouraged to block access to the site!',

	'CategoryArray'		=> [
		'basics'		=> 'Basic functions',
		'preferences'	=> 'Preferences',
		'content'		=> 'Content',
		'users'			=> 'Users',
		'maintenance'	=> 'Maintenance',
		'messages'		=> 'Messages',
		'extension'		=> 'Extension',
		'database'		=> 'Database',
	],

	// Admin panel
	'Authorization'			=> 'Autorizaci�n',
	'AuthorizationTip'		=> 'Por favor ingrese la contrase�a del administrador (aseg�rese que su navegador permita cookies).',
	'NoRecoceryPassword'	=> 'Contrase�a administrativa no especificada!',
	'NoRecoceryPasswordTip'	=> 'Observaci�n: La falta de una contrase�a administrativa es un riesgo de seguridad! Ingrese la contrase�a en el archivo de configuraci�n y vuelva a ejecutar el programa.',

	'ErrorLoadingModule'		=> 'Error loading admin module %1: does not exists.',

	'FormSave'					=> 'Save',
	'FormReset'					=> 'Reset',
	'FormUpdate'				=> 'Update',

	'ApHomePage'				=> 'Home Page',
	'ApHomePageTip'				=> 'open the home page, you do not quit administration',
	'ApLogOut'					=> 'Log out',
	'ApLogOutTip'				=> 'quit system administration',

	'TimeLeft'					=> 'Time left:  %1 minutes',
	'ApVersion'					=> 'version',

	'SiteOpen'					=> 'open',
	'SiteOpened'				=> 'site opened',
	'SiteOpenedTip'				=> 'The site is open',
	'SiteClose'					=> 'close',
	'SiteClosed'				=> 'site closed',
	'SiteClosedTip'				=> 'The site is closed',

	// Generic
	'Enabled'					=> 'Enabled',
	'Disabled'					=> 'Disabled',
	'On'						=> 'on',
	'Off'						=> 'off',
	'Mandatory'					=> 'Mandatory',
	'Admin'						=> 'Admin',

	// MENU
	// Config Basic module
	'config_basic'		=> [
		'name'		=> 'Basic',
		'title'		=> 'Basic parameters',
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
		'name'		=> 'Pages',
		'title'		=> 'Pages and site parameters',
	],

	// Config Permissions module
	'config_permissions'		=> [
		'name'		=> 'Permissions',
		'title'		=> 'Permissions settings',
	],

	// Config Security module
	'config_security'		=> [
		'name'		=> 'Security',
		'title'		=> 'Security subsystems settings',
	],

	// Config System module
	'config_system'		=> [
		'name'		=> 'System',
		'title'		=> 'System options',
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
		'name'		=> 'Deleted',
		'title'		=> 'Newly deleted content',
	],

	// Files module
	'content_files'		=> [
		'name'		=> 'Files',
		'title'		=> 'Manage uploaded files',
	],

	// Menu module
	'content_menu'		=> [
		'name'		=> 'Menu',
		'title'		=> 'Add, edit or remove default menu items',
	],

	// Pages module
	'content_pages'		=> [
		'name'		=> 'Pages',
		'title'		=> 'Manage pages',
	],

	// Polls module
	'content_polls'		=> [
		'name'		=> 'Polls',
		'title'		=> 'Editing, start and stop polls',
	],

	// DB Backup module
	'db_backup'		=> [
		'name'		=> 'Backup',
		'title'		=> 'Backing up data',
	],

	// DB Convert module
	'db_convert'		=> [
		'name'		=> 'Convert',
		'title'		=> 'Converting Tables or Columns',
	],

	// DB Repair module
	'db_repair'		=> [
		'name'		=> 'Repair',
		'title'		=> 'Repair and Optimize Database',
	],

	// DB Restore module
	'db_restore'		=> [
		'name'		=> 'Restore',
		'title'		=> 'Restoring backup data',
	],

	// Dashboard module
	'lock'		=> [
		'name'		=> 'Main Menu',
		'title'		=> 'WackoWiki Administration',
	],

	// Inconsistencies module
	'maint_inconsistencies'		=> [
		'name'		=> 'Inconsistencies',
		'title'		=> 'Fixing Data Inconsistencies',
	],

	// Data Synchronization module
	'maint_resync'		=> [
		'name'		=> 'Data Synchronization',
		'title'		=> 'Synchronizing data',
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
		'name'		=> 'System log',
		'title'		=> 'Log of system events',
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
		'name'		=> 'Groups',
		'title'		=> 'Group management',
	],

	// User module
	'user_users'		=> [
		'name'		=> 'Users',
		'title'		=> 'User management',
	],

	'LogFilterTip'			=> 'Filtrar eventos por criterios',
	'LogLevel'				=> 'Nivel',
	'LogLevelNotLower'		=> 'no menos que',
	'LogLevelNotHigher'		=> 'no m�s que',
	'LogLevelEqual'			=> 'igual',
	'LogNoMatch'			=> 'No hay coincidencias',
	'LogDate'				=> 'Fecha',
	'LogEvent'				=> 'Evento',
	'LogUsername'			=> 'Nombre de usuario',

	'PurgeSessions'				=> 'purge',
	'PurgeSessionsTip'			=> 'Purge all sessions',
	'PurgeSessionsConfirm'		=> 'Are you sure you wish to purge all sessions? This will log out all users.',
	'PurgeSessionsExplain'		=> 'Purge all sessions. This will log out all users by truncating the auth_token table.',
	'PurgeSessionsDone'			=> 'Sessions successfully purged.',

	// Email settings
	'EmaiSettingsInfo'			=> 'Esta informaci�n se usa cuando el Sitio env�a emails a sus usuarios. Por favor verifique que la direcci�n de email ingresada sea v�lida, cualquier rebote se reenviar� a esa direcci�n. Si su host no provee un servicio de email nativo (utilizable por PHP), entonces use directamente SMTP. Esto requiere la direcci�n de un servidor apropiado (preg�ntele a su ISP de ser necesario). Si (si, y solo si) el servidor requiere autentificaci�n complete el usuario y contrase�a. Por favor observe que solo se ofrece autentificaci�n b�sica, otro tipo de implementaci�n no es posible actualmente.',

	'EmailFunctionName'			=> 'Email function name',
	'EmailFunctionNameInfo'		=> 'The email function used to send mails through PHP.',
	'UseSmtpInfo'				=> 'Select <code>SMTP</code> if you want or have to send email via a named server instead of the local mail function.',

	'EnableEmail'				=> 'Enable emails',
	'EnableEmailInfo'			=> 'Enabling emails',

	'SendTestEmail'				=> 'Send a test email',
	'SendTestEmailInfo'			=> 'This will send a test email to the address defined in your account.',
	'TestEmailSubject'			=> 'WackoWiki is correctly configured to send emails',
	'TestEmailBody'				=> 'If you received this email, WackoWiki is correctly configured to send emails.',
	'TestEmailMessage'			=> 'The test email has been sent.<br />If you don\'t receive it, please check your emails configuration.',

	'SmtpAutoTls'				=> 'Opportunistic TLS',
	'SmtpAutoTlsInfo'			=> 'Enables encryption automatically, if it sees that the server is advertising TLS encryption (after you have connected to the server), even if you have not set the connection mode for <code>SMTPSecure</code>.',
	'SmtpConnectionMode'		=> 'Connection mode for SMTP',
	'SmtpConnectionModeInfo'	=> 'Only used if a username/password is set, ask your provider if you are unsure which method to use.',
	'SmtpPassword'				=> 'SMTP password',
	'SmtpPasswordInfo'			=> 'Only enter a password if your SMTP server requires it.<br /><em><strong>Warning:</strong> This password will be stored as plain text in the database, visible to everybody who can access your database or who can view this configuration page.</em>',
	'SmtpPort'					=> 'SMTP server port',
	'SmtpPortInfo'				=> 'Only change this if you know your SMTP server is on a different port. <br />(default: <code>tls</code> on port 587 (or possibly 25) and <code>ssl</code> on port 465)',
	'SmtpServer'				=> 'SMTP server address',
	'SmtpServerInfo'			=> 'Note that you have to provide the protocol that your server uses. If you are using SSL, this has to be "ssl://mail.example.com"',
	'SmtpSettings'				=> 'SMTP settings',
	'SmtpUsername'				=> 'SMTP username',
	'SmtpUsernameInfo'			=> 'Only enter a username if your SMTP server requires it.',

	// log
	'LogLevel1'					=> 'cr�tico',
	'LogLevel2'					=> 'm�s alto',
	'LogLevel3'					=> 'alto',
	'LogLevel4'					=> 'medio',
	'LogLevel5'					=> 'bajo',
	'LogLevel6'					=> 'm�s bajo',
	'LogLevel7'					=> 'depuraci�n',

	// Massemail
	'SendToGroup'				=> 'Send to group',
	'SendToUser'				=> 'Send to user',

	// User approval module
	'UserApproveInfo'			=> 'Approve new users before they are able to login to the site.',
	'Approve'					=> 'Approve',
	'Deny'						=> 'Deny',
	'Pending'					=> 'Pending',
	'Approved'					=> 'Approved',
	'Denied'					=> 'Denied',

	// DB Backup module
	'BackupStructure'			=> 'Structure',
	'BackupData'				=> 'Data',
	'BackupFolder'				=> 'Folder',
	'BackupTable'				=> 'Table',
	'BackupCluster'				=> 'Cluster',
	'BackupFiles'				=> 'Files',
	'BackupSettings'			=> 'Specify the desired scheme of Backup.<br />' .
									'The root cluster does not affect the global files backup and cache files backup (being chosen they are always saved in full).<br />' .
									'<br />' .
									'<span class="underline">Attention</span>: To avoid loss of information from the database when specifying the root cluster the tables from this backup will not be restructured, '.
									'same when backing up only table structure without saving the data. '.
									'To make a complete conversion of the tables to the backup format you must make the <em> full database backup (structure and data) without specifying the cluster</em>.',
	'BackupCompleted'			=> 'Backing up and archiving completed.<br />' .
									'Backup package files stored in the "(date)YYYYMMDD_(time)HHMMSS" named sub-directory of <code>files/backup</code> directory.<br />' .
									'To download it use FTP (maintain the directory structure and file names when copying).<br />' .
									'To restore a backup copy or remove a package, go to <a href="?mode=db_restore">Restore database</a>.',
	'LogSavedBackup'			=> 'Saved backup database ##%1##',

	// DB Restore module
	'RestoreInfo'				=> 'You can restore any of the backup packages found or remove it from the server.',
	'ConfirmDbRestore'			=> 'Do you want to restore backup',
	'ConfirmDbRestoreInfo'		=> 'Please wait this can take some minutes.',
	'RestoreWrongVersion'		=> 'Wrong WackoWiki version!',
	'BackupDelete'				=> 'Are you sure you want to remove backup',
	'BackupDeleteInfo'			=> '',
	'RestoreOptions'			=> 'Additional restore options',
	'RestoreOptionsInfo'		=> '* Before restoring the <span class="underline">cluster backup</span>, '.
									'the target tables are not destroyed (to prevent loss of information from the clusters that have not been backed up). '.
									'Thus, during the recovery process duplicate records will occur. '.
									'In normal mode, all of them will be replaced by the records form backup (using SQL-instruction <code>REPLACE</code>), '.
									'but if this checkbox is checked, all duplicates are skipped (the current values of records will be kept), '.
									'and only the records with new keys are added to the table (SQL-instruction <code>INSERT IGNORE</code>).<br />' .
									'<span class="underline">Notice</span>: When restore complete backup of the site, this option has no value.<br />' .
									'<br />' .
									'** If the backup contains the user files (global and perpage, cache files, etc.), '.
									'in normal mode they replace the existing files with the same names and are placed in the same directory when being restored. '.
									'This option allows you to save the current copies of the files and restore from a backup only new files (missing on the server).',
	'IgnoreDuplicatedKeys'		=> 'Ignore duplicated table keys (not replace)',
	'IgnoreSameFiles'			=> 'Ignore the same files (not overwrite)',
	'NoBackupsAvailable'		=> 'No backups available.',
	'BackupEntireSite'			=> 'Entire site',
	'BackupRestored'			=> 'The backup is restored, a summary report is attached below. To delete this backup package, click',
	'BackupRemoved'				=> 'The selected backup has been successfully removed.',
	'LogRemovedBackup'			=> 'Removed database backup ##%1##',

	// User module
	'UsersAdded'				=> 'User added',
	'UsersDeleteInfo'			=> '[User delete Info here..]',
	'UserEditButton'			=> 'Edit',
	'UserEnabled'				=> 'Habilitado',
	'UsersAddNew'				=> 'Nuevo usuario',
	'UsersDelete'				=> 'Est� seguro que desea eliminar al usuario ',
	'UsersDeleted'				=> 'Usuario eliminado de la base de datos.',
	'UsersRename'				=> 'Renombrar usuario',
	'UsersRenameInfo'			=> '* Observaci�n: El cambio afectar� a todas las p�ginas asignadas a este usuario.',
	'UsersUpdated'				=> 'Usuario actualizado satisfactoriamente.',

	'UserName'					=> 'Username',
	'UserRealname'				=> 'Realname',
	'UserEmail'					=> 'Email',
	'UserIP'					=> 'IP',
	'UserLanguage'				=> 'Language',
	'UserSignuptime'			=> 'Signuptime',
	'UserActions'				=> 'Actions',
	'NoMatchingUser'			=> 'No users that meet the criteria',

	// Groups module
	'GroupsMembersFor'			=> 'Miembros del grupo',
	'GroupsDescription'			=> 'Descripci�n',
	'GroupsModerator'			=> 'Moderador',
	'GroupsOpen'				=> 'Abierto',
	'GroupsActive'				=> 'Activo',
	'GroupsTip'					=> 'Presione para editar el grupo',
	'GroupsUpdated'				=> 'Grupos actualizados',
	'GroupsAlreadyExists'		=> 'El grupo ya existe.',
	'GroupsAdded'				=> 'Grupo agregado con �xito.',
	'GroupsRenamed'				=> 'Grupo renombrado exitosamente.',
	'GroupsDeleted'				=> 'Grupo eliminado de la base de datos y de todas las p�ginas.',
	'GroupsAdd'					=> 'Agregar grupo nuevo',
	'GroupsRename'				=> 'Renombrar el grupo',
	'GroupsRenameInfo'			=> '* Observaci�n: El cambio afectar� a todas las p�ginas con el grupo asignado.',
	'GroupsDelete'				=> 'Est� seguro que desea eliminar el grupo ',
	'GroupsDeleteInfo'			=> '* Observaci�n: El cambio afectar� a todas los miembros del grupo.',
	'GroupsStoreButton'			=> 'Guardar Grupos',
	'GroupsSaveButton'			=> 'GUardar',
	'GroupsCancelButton'		=> 'Cancelar',
	'GroupsAddButton'			=> 'Agregar',
	'GroupsEditButton'			=> 'Editar',
	'GroupsRemoveButton'		=> 'Remover',
	'GroupsEditInfo'			=> 'Marque el bot�n de radio para editar la lista de grupos.',

	'MembersAddNew'				=> 'Miembro nuevo',
	'MembersAdded'				=> 'Added new member to the group successfully.',
	'MembersRemove'				=> 'Est� seguro que desea remover el miembro ',
	'MembersRemoved'			=> 'Miembro eliminado del grupo.',
	'MembersDeleteInfo'			=> '* Observaci�n: El cambio afectar� a todos los miembros asignados al grupo.',

];

?>