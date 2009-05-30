<?php
/*
 Wacko Wiki MySQL Table Creation Script
 */

$table_pages = "CREATE TABLE ".$config["table_prefix"]."pages (".
					"id INT(10) UNSIGNED NOT NULL auto_increment,".
					"tag VARCHAR(250) NOT NULL DEFAULT '',".
					"supertag VARCHAR(250) NOT NULL DEFAULT '',".
					"created DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',".
					"time DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',".
					"body MEDIUMTEXT NOT NULL,".
					"body_r MEDIUMTEXT NOT NULL,".
					"body_toc TEXT NOT NULL,".
					"owner VARCHAR(50) NOT NULL DEFAULT '',".
					"owner_id INT(10) UNSIGNED NOT NULL DEFAULT '0',".
					"user VARCHAR(50) NOT NULL DEFAULT '',".
					"user_id INT(10) UNSIGNED NOT NULL DEFAULT '0',".
					"edit_note VARCHAR(100) NOT NULL DEFAULT '',".
					"latest ENUM('Y','N') NOT NULL DEFAULT 'Y',".
					"handler VARCHAR(30) NOT NULL DEFAULT 'page',".
					"comment_on VARCHAR(250) binary NOT NULL DEFAULT '',".
					"super_comment_on VARCHAR(250) NOT NULL DEFAULT '',".
					"hits INT(11) UNSIGNED NOT NULL DEFAULT '0',".
					"lang VARCHAR(2) NOT NULL DEFAULT '',".
					"title VARCHAR(100) NOT NULL DEFAULT '',".
					"description VARCHAR(250) NOT NULL DEFAULT '',".
					"keywords VARCHAR(250) binary NOT NULL DEFAULT '',".
					"PRIMARY KEY (id),".
					"FULLTEXT KEY body (body),".
					"UNIQUE KEY idx_tag (tag),".
					"KEY idx_supertag (supertag),".
					"KEY idx_created (created),".
					"KEY idx_time (time),".
					"KEY idx_comment_on (comment_on),".
					"KEY idx_super_comment_on (super_comment_on),".
					"KEY idx_title (title)".
				") TYPE=MyISAM;";

$table_revisions = "CREATE TABLE ".$config["table_prefix"]."revisions (".
						"id INT(10) UNSIGNED NOT NULL auto_increment,".
						"tag VARCHAR(250) binary NOT NULL DEFAULT '',".
						"supertag VARCHAR(250) binary NOT NULL DEFAULT '',".
						"created DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',".
						"time DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',".
						"body MEDIUMTEXT NOT NULL,".
						"body_r MEDIUMTEXT NOT NULL,".
						"owner VARCHAR(50) NOT NULL DEFAULT '',".
						"owner_id INT(10) UNSIGNED NOT NULL DEFAULT '0',".
						"user VARCHAR(50) NOT NULL DEFAULT '',".
						"user_id INT(10) UNSIGNED NOT NULL DEFAULT '0',".
						"edit_note VARCHAR(100) NOT NULL DEFAULT '',".
						"latest ENUM('Y','N') NOT NULL DEFAULT 'N',".
						"handler VARCHAR(30) NOT NULL DEFAULT 'page',".
						"comment_on VARCHAR(250) binary NOT NULL DEFAULT '',".
						"super_comment_on VARCHAR(250) NOT NULL DEFAULT '',".
						"lang VARCHAR(2) NOT NULL DEFAULT '',".
						"title VARCHAR(100) NOT NULL DEFAULT '',".
						"description VARCHAR(250) NOT NULL DEFAULT '',".
						"keywords VARCHAR(250) binary NOT NULL DEFAULT '',".
						"PRIMARY KEY (id),".
						"KEY idx_tag (tag),".
						"KEY idx_supertag (supertag),".
						"KEY idx_time (time),".
						"KEY idx_comment_on (comment_on)".
					") TYPE=MyISAM;";

$table_acls = "CREATE TABLE ".$config["table_prefix"]."acls (".
					"page_tag VARCHAR(250) binary NOT NULL DEFAULT '',".
					"page_id INT(10) UNSIGNED NOT NULL DEFAULT '0',".
					"supertag VARCHAR(250) NOT NULL DEFAULT '',".
					"privilege VARCHAR(10) NOT NULL DEFAULT '',".
					"list TEXT NOT NULL,".
					"PRIMARY KEY  (page_tag,privilege),".
					"KEY supertag (supertag)".
				") TYPE=MyISAM";

$table_links = "CREATE TABLE ".$config["table_prefix"]."links (".
					"id INT(10) UNSIGNED NOT NULL auto_increment,".
					"from_tag VARCHAR(250) binary NOT NULL DEFAULT '',".
					"from_page_id INT(10) UNSIGNED NOT NULL DEFAULT '0',".
					"to_tag VARCHAR(250) binary NOT NULL DEFAULT '',".
					"to_page_id INT(10) UNSIGNED NOT NULL DEFAULT '0',".
					"to_supertag VARCHAR(250) NOT NULL,".
					"KEY from_tag (from_tag,to_tag(78)),".
					"KEY idx_from (from_tag),".
					"KEY idx_to (to_tag)".
				") TYPE=MyISAM";

$table_referrers = "CREATE TABLE ".$config["table_prefix"]."referrers (".
						"page_id INT(10) UNSIGNED NOT NULL DEFAULT '0',".
						"referrer CHAR(150) NOT NULL DEFAULT '',".
						"time DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',".
						"KEY idx_page_id (page_id),".
						"KEY idx_time (time)".
					") TYPE=MyISAM";

$table_users = "CREATE TABLE ".$config["table_prefix"]."users (".
					"id INT(10) UNSIGNED NOT NULL auto_increment,".
					"name VARCHAR(80) NOT NULL DEFAULT '',".
					"password VARCHAR(32) NOT NULL DEFAULT '',".
					"email VARCHAR(50) NOT NULL DEFAULT '',".
					"motto TEXT NOT NULL,".
					"revisioncount INT(10) UNSIGNED NOT NULL DEFAULT '20',".
					"changescount INT(10) UNSIGNED NOT NULL DEFAULT '50',".
					"doubleclickedit ENUM('Y','N') NOT NULL DEFAULT 'Y',".
					"signuptime DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',".
					"show_comments ENUM('Y','N') NOT NULL DEFAULT 'N',".
					"bookmarks TEXT NOT NULL,".
					"lang VARCHAR(2) NOT NULL DEFAULT '',".
					"show_spaces ENUM('Y','N') NOT NULL DEFAULT 'Y',".
					"showdatetime ENUM('Y','N') NOT NULL DEFAULT 'Y',".
					"typografica ENUM('Y','N') NOT NULL DEFAULT 'Y',".
					"more TEXT NOT NULL,".
					"changepassword VARCHAR(100) NOT NULL,".
					"email_confirm VARCHAR(40) NOT NULL DEFAULT '',".
					"PRIMARY KEY (id),".
					"KEY idx_name (name),".
					"KEY idx_signuptime (signuptime)".
				") TYPE=MyISAM";

$table_pagewatches = "CREATE TABLE ".$config["table_prefix"]."pagewatches (".
						"id INT(10) UNSIGNED NOT NULL auto_increment,".
						"user VARCHAR(80) NOT NULL DEFAULT '',".
						"user_id INT(10) UNSIGNED NOT NULL DEFAULT '0',".
						"tag VARCHAR(250) binary NOT NULL DEFAULT '',".
						"page_id INT(10) UNSIGNED NOT NULL DEFAULT '0',".
						"time TIMESTAMP NOT NULL,".
						"PRIMARY KEY (id)".
					") TYPE=MyISAM";

$table_upload = "CREATE TABLE ".$config["table_prefix"]."upload (".
					"id INT(10) UNSIGNED NOT NULL auto_increment,".
					"page_id INT(10) UNSIGNED NOT NULL DEFAULT '0',".
					"user_id INT(10) UNSIGNED NOT NULL DEFAULT '0',".
					"filename VARCHAR(250) NOT NULL DEFAULT '',".
					"description VARCHAR(250) NOT NULL DEFAULT '',".
					"uploaded_dt DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',".
					"filesize INT(11) UNSIGNED NOT NULL DEFAULT '0',".
					"picture_w INT(11) UNSIGNED NOT NULL DEFAULT '0',".
					"picture_h INT(11) UNSIGNED NOT NULL DEFAULT '0',".
					"file_ext VARCHAR(10) NOT NULL DEFAULT '',".
					"PRIMARY KEY (id),".
					"KEY page_id (page_id,filename),".
					"KEY page_id_2 (page_id,uploaded_dt),".
					"KEY idx_user_id (user_id)".
				") TYPE=MyISAM";

$table_cache = "CREATE TABLE ".$config["table_prefix"]."cache (".
					"name VARCHAR(32) NOT NULL,".
					"method VARCHAR(20) NOT NULL,".
					"query VARCHAR(100) NOT NULL,".
					"time TIMESTAMP NOT NULL,".
					"INDEX (name),".
					"KEY timestamp (time)".
				") TYPE=MyISAM";

/*
 Wacko Wiki MySQL Table Deletion Script
*/

$table_pages_drop = "DROP TABLE ".$config["table_prefix"]."pages";
$table_revisions_drop = "DROP TABLE ".$config["table_prefix"]."revisions";
$table_acls_drop = "DROP TABLE ".$config["table_prefix"]."acls";
$table_links_drop = "DROP TABLE ".$config["table_prefix"]."links";
$table_referrers_drop = "DROP TABLE ".$config["table_prefix"]."referrers";
$table_users_drop = "DROP TABLE ".$config["table_prefix"]."users";
$table_pagewatches_drop = "DROP TABLE ".$config["table_prefix"]."pagewatches";
$table_upload_drop = "DROP TABLE ".$config["table_prefix"]."upload";
$table_cache_drop = "DROP TABLE ".$config["table_prefix"]."cache";

?>