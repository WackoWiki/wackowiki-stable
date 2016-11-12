<?php

if (!defined('IN_WACKO'))
{
	exit('No direct script access allowed');
}

########################################################
##   Common database backup variables and functions   ##
########################################################

// define db tables
// we really want this up to date
if (!isset($tables, $directories))
{
	$tables	= [
			$engine->db->table_prefix . 'acl' => [
				'name'	=> $engine->db->table_prefix . 'acl',
				'where'	=> 'page_id',
				'order'	=> 'page_id',
				'limit' => 1000
			],
			$engine->db->table_prefix . 'auth_token' => [
					'name'	=> $engine->db->table_prefix . 'auth_token',
					'where'	=> false,
					'order'	=> 'user_id',
					'limit' => 1000
			],
			$engine->db->table_prefix . 'cache' => [
				'name'	=> $engine->db->table_prefix . 'cache',
				'where'	=> false,
				'order'	=> 'cache_id',
				'limit' => 1000
			],
			$engine->db->table_prefix . 'config' => [
				'name'	=> $engine->db->table_prefix . 'config',
				'where'	=> false,
				'order'	=> 'config_id',
				'limit' => 500
			],
			$engine->db->table_prefix . 'category' => [
				'name'	=> $engine->db->table_prefix . 'category',
				'where'	=> false,
				'order'	=> 'category_id',
				'limit' => 1000
			],
			$engine->db->table_prefix . 'category_page' => [
				'name'	=> $engine->db->table_prefix . 'category_page',
				'where'	=> 'page_id',
				'order'	=> 'page_id',
				'limit' => 1000
			],
			$engine->db->table_prefix . 'file_link' => [
					'name'	=> $engine->db->table_prefix . 'file_link',
					'where'	=> 'page_id',
					'order'	=> 'page_id',
					'limit' => 1000
			],
			$engine->db->table_prefix . 'log' => [
				'name'	=> $engine->db->table_prefix . 'log',
				'where'	=> false,
				'order'	=> 'log_time',
				'limit' => 1000
			],
			$engine->db->table_prefix . 'menu' => [
				'name'	=> $engine->db->table_prefix . 'menu',
				'where'	=> false,
				'order'	=> 'menu_id',
				'limit' => 1000
			],
			$engine->db->table_prefix . 'page' => [
				'name'	=> $engine->db->table_prefix . 'page',
				'where'	=> true,
				'order'	=> 'tag',
				'limit' => 500
			],
			$engine->db->table_prefix . 'page_link' => [
				'name'	=> $engine->db->table_prefix . 'page_link',
				'where'	=> 'from_page_id',
				'order'	=> 'from_page_id',
				'limit' => 1000
			],
			$engine->db->table_prefix . 'poll' => [
				'name'	=> $engine->db->table_prefix . 'poll',
				'where'	=> false,
				'order'	=> 'poll_id, v_id',
				'limit' => 1000
			],
			$engine->db->table_prefix . 'rating' => [
				'name'	=> $engine->db->table_prefix . 'rating',
				'where'	=> false,
				'order'	=> 'page_id',
				'limit' => 1000
			],
			$engine->db->table_prefix . 'referrer' => [
				'name'	=> $engine->db->table_prefix . 'referrer',
				'where'	=> 'page_id',
				'order'	=> 'page_id',
				'limit' => 1000
			],
			$engine->db->table_prefix . 'revision' => [
				'name'	=> $engine->db->table_prefix . 'revision',
				'where'	=> 'revision_id',
				'order'	=> 'revision_id',
				'limit' => 500
			],
			$engine->db->table_prefix . 'upload' => [
				'name'	=> $engine->db->table_prefix . 'upload',
				'where'	=> false,
				'order'	=> 'upload_id',
				'limit' => 1000
			],
			$engine->db->table_prefix . 'user' => [
				'name'	=> $engine->db->table_prefix . 'user',
				'where'	=> false,
				'order'	=> 'user_id',
				'limit' => 1000
			],
			$engine->db->table_prefix . 'user_setting' => [
				'name'	=> $engine->db->table_prefix . 'user_setting',
				'where'	=> false,
				'order'	=> 'user_id',
				'limit' => 1000
			],
			$engine->db->table_prefix . 'usergroup' => [
				'name'	=> $engine->db->table_prefix . 'usergroup',
				'where'	=> false,
				'order'	=> 'group_id',
				'limit' => 1000
			],
			$engine->db->table_prefix . 'usergroup_member' => [
				'name'	=> $engine->db->table_prefix . 'usergroup_member',
				'where'	=> false,
				'order'	=> 'group_id',
				'limit' => 1000
			],
			$engine->db->table_prefix . 'watch' => [
				'name'	=> $engine->db->table_prefix . 'watch',
				'where'	=> 'page_id',
				'order'	=> 'page_id',
				'limit' => 1000
			]
		];

	// define files dirs
	$directories = [
			// CACHE_FEED_DIR, // not configurable now!
			// CACHE_PAGE_DIR,
			// CACHE_SQL_DIR,
			UPLOAD_GLOBAL_DIR,
			UPLOAD_PER_PAGE_DIR
		];
}
else
{
	die('Error in admin module "database.php": unable to register '.
		'common variables: already defined.');
}

function ensure_dir($dir)
{
	if (!is_dir($dir))
	{
		mkdir($dir);
	}

	chmod($dir, 0755);
}

// set backup directory
function set_pack_dir(&$engine, $time)
{
	// check dir name and create if not exists
	$pack = date('Ymd_His', $time);
	$dir = Ut::join_path(UPLOAD_BACKUP_DIR, $pack);

	clearstatcache();
	ensure_dir($dir);

	return $dir . '/';
}

// delete backup pack from the server
function remove_pack(&$engine, $pack)
{
	$packdir = Ut::join_path(UPLOAD_BACKUP_DIR, $pack);

	// read log
	$log = file(Ut::join_path($packdir, BACKUP_FILE_LOG), FILE_IGNORE_NEW_LINES);

	// get subdirs list (in reverse order)
	$subdirs = explode(';', @$log[5]);
	rsort($subdirs);

	// remove subdirs contents
	foreach ($subdirs as $subdir)
	{
		if ($subdir)
		{
			$dir = Ut::join_path($packdir, $subdir);
			Ut::purge_directory($dir, 0, GLOB_ALL);
			rmdir($dir);

			// recursively remove subdirs in path
			while (($i = strrpos($subdir, '/')) !== false)
			{
				$subdir = substr($subdir, 0, $i);
				@rmdir(Ut::join_path($packdir, $subdir)); // @ - coz dir can be non-empty
			}
		}
	}

	// remove pack contents and directory
	if (is_dir($packdir))
	{
		Ut::purge_directory($packdir, 0, GLOB_ALL);
		rmdir($packdir);
	}

	return true;
}

// adapted and updated from phpBB 2.x
// construct sql for table restoration
function get_table(&$engine, $table, $drop = true)
{
	/***************************************************************************
	*                             admin_db_utilities.php
	*                              -------------------
	*     begin                : Thu May 31, 2001
	*     copyright            : (C) 2001 The phpBB Group
	*     email                : support@phpbb.com
	*
	*
	****************************************************************************/

	/***************************************************************************
	 *
	 *   This program is free software; you can redistribute it and/or modify
	 *   it under the terms of the GNU General Public License as published by
	 *   the Free Software Foundation; either version 2 of the License, or
	 *   (at your option) any later version.
	 *
	 ***************************************************************************/

	/***************************************************************************
	*	We will attempt to create a file based backup of all of the data in the
	*	users WackoWiki database. The resulting file should be able to be imported by
	*	the db_restore.php function, or by using the mysql command_line
	*
	*	Some functions are adapted from the upgrade_20.php script and others
	*	adapted from the unoficial phpMyAdmin 2.2.0.
	***************************************************************************/

	$index			= [];
	$schema_create	= "";
	$field_query	= "SHOW FIELDS FROM $table";
	$key_query		= "SHOW KEYS FROM $table";

	if ($drop == true)
	{
		$schema_create .= "DROP TABLE IF EXISTS `$table`;\n";
	}

	$schema_create .= "CREATE TABLE IF NOT EXISTS `$table` (\n";

	//
	// Ok lets grab the fields...
	//
	$result = $engine->db->load_all($field_query);

	foreach ($result as $row)
	{
		$schema_create .= '	`' . $row['Field'] . '` ' . $row['Type'];

		// provide timestamp with CURRENT_TIMESTAMP without quotes
		if (!empty($row['Default'])
			&& (($row['Type'] == 'timestamp' && $row['Default'] == 'CURRENT_TIMESTAMP')
				|| ($row['Type'] == 'tinyint' &&  $row['Default'] == 'NULL')))
		{
			$schema_create .= ' DEFAULT ' . $row['Default'] . '';
		}
		else if (isset($row['Default']) && $row['Default'] !== '')
		{
			$schema_create .= ' DEFAULT \'' . $row['Default'] . '\'';
		}

		if ($row['Null'] != 'YES')
		{
			$schema_create .= ' NOT NULL';
		}

		if ($row['Extra'] != '')
		{
			$schema_create .= ' ' . $row['Extra'];
		}

		$schema_create .= ",\n";
	}
	//
	// drop the last ',\n' off ;)
	//
	$schema_create = preg_replace('/,'."\n".'$/', '', $schema_create);

	//
	// get any Indexed fields from the database...
	//
	$result = $engine->db->load_all($key_query);

	foreach ($result as $row)
	{
		$kname = $row['Key_name'];

		if (($kname != 'PRIMARY') && ($row['Non_unique'] == 0))
		{
			$kname = "UNIQUE|$kname";
		}
		else if ($kname != 'PRIMARY' && $row['Index_type'] == 'FULLTEXT')
		{
			$kname = "FULLTEXT|$kname";
		}

		if (!is_array(isset($index[$kname]) ? $index[$kname] : null))
		{
			$index[$kname] = [];
		}

		$index[$kname][] = '`' . $row['Column_name'] . '`'.( $row['Sub_part'] ? '(' . $row['Sub_part'] . ')' : '' );
	}

	while (list($x, $columns) = @each($index))
	{
		$schema_create .= ", \n";

		if ($x == 'PRIMARY')
		{
			$schema_create .= '	PRIMARY KEY (' . implode($columns, ', ') . ')';
		}
		else if (substr($x,0,6) == 'UNIQUE')
		{
			$schema_create .= '	UNIQUE `' . substr($x,7) . '` (' . implode($columns, ', ') . ')';
		}
		else if (substr($x,0,8) == 'FULLTEXT')
		{
			$schema_create .= '	FULLTEXT KEY `' . substr($x,9) . '` (' . implode($columns, ', ') . ')';
		}
		else
		{
			$schema_create .= "	KEY `$x` (" . implode($columns, ', ') . ')';
		}
	}

	$schema_create .= "\n) ENGINE={$engine->db->database_engine} CHARSET={$engine->db->database_charset};"; // TODO: CHARSET per table

	return ($schema_create);
}

// extract and compress table dump into the out file
// $tables var is a tables definition array
function get_data(&$engine, &$tables, $pack, $table, $root = '')
{
	$where = '';
	$tweak = '';
	$result = '';

	// sql clauses
	if ($root == true && $tables[$table]['where'] == true)
	{
		// all cluster related page_id's
		static $cluster_pages;

		// get array with cluster related page_id's
		if (!isset($cluster_pages[$root]))
		{
			$_root = $engine->translit($root);
			$pages = $engine->db->load_all(
				"SELECT page_id ".
				"FROM ".$engine->db->table_prefix."page ".
				"WHERE supertag LIKE " . $engine->db->q($_root . '/%') . " ".
					"OR supertag = " . $engine->db->q($_root) . " ");

			foreach ($pages as $page)
			{
				if ($page != '')
				{
					$result	.= "'" . $page['page_id'] . "', ";

					// we'll need this for backing up the related cluster files
					$engine->cluster_pages[$root][]	= $page['page_id'];
				}
			}

			$result					= substr($result, 0, strlen($result) - 2);
			$cluster_pages[$root]	= $result;
		}

		if ($table != $engine->db->table_prefix.'page')	// not page table
		{
			$where = "WHERE {$tables[$table]['where']} IN (" . $cluster_pages[$root] . ") ";
		}
		else
		{
			$where = "WHERE tag LIKE " . $engine->db->q($root . '/%') . " ".
						"OR tag = " . $engine->db->q($root) . " ".
						"OR comment_on_id IN (" . $cluster_pages[$root] . ") ";
		}
	}

	$order = "ORDER BY {$tables[$table]['order']} ";
	$limit = "LIMIT %1, {$tables[$table]['limit']} ";

	// tweak
	if ($table == $engine->db->table_prefix . 'page')
	{
		$tweak = true;
	}

	// check file existance
	clearstatcache();
	$filename = $pack . $table . BACKUP_FILE_DUMP_SUFFIX;

	if (file_exists($filename) === true)
	{
		unlink($filename);
	}

	// open file with write access
	$file = gzopen($filename, 'ab' . BACKUP_COMPRESSION_RATE);

	// read table data until it's exhausted
	$r = 0;
	$t = 0;

	while (true == $data = $engine->db->load_all(
	"SELECT * FROM $table ".
	( $where ? $where : "" ).
	$order.
	Ut::perc_replace($limit, $r)))
	{
		foreach ($data as $row)
		{
			$r++;	// count rows for LIMIT clause

			// storage optimization tweak: don't save `body_r`
			// and `body_toc` fields for `page` table
			if ($tweak === true)
			{
				$row['body_r']		= '';
				$row['body_toc']	= '';
			}

			// escape divider chars
			$row = str_replace("\\", '\\\\', $row);
			$row = str_replace("\n", '\\n',  $row);
			$row = str_replace("\t", '\\t',  $row);

//			// prepare data
//			foreach ($row as $name => $cell)
//			{
//				$row[$name] = ( $cell == '' ? 'null' : $cell );
//			}

			// construct output
			$contents = implode("\t", $row)."\n";

			// write data to the compressed file
			gzwrite($file, $contents);
			$t++;	// total rows processed
		}
	}

	// save and close file
	gzclose($file);
	chmod($filename, 0644);

	return $t;
}

// store compressed WackoWiki data files into the backup pack
function get_files(&$engine, $pack, $dir, $root)
{
	$cluster	= '';
	$error		= '';
	$matches	= [];

	// set file mask for cluster backup
	if ($root && $dir == UPLOAD_PER_PAGE_DIR)
	{
		$cluster = true;
	}

	// create write (backup) subdir or restore path recursively if needed
	$offset = 0;

	while (($offlen = strpos($dir, '/', $offset)) !== false)
	{
		$offset = $offlen + 1;
		ensure_dir(Ut::join_path($pack, substr($dir, 0, $offlen)));
	}

	ensure_dir(Ut::join_path($pack, $dir));

	// open read (data) dir and run through all files
	$t = 0;

	if ($dh = opendir($dir))
	{
		while (false !== ($filename = readdir($dh)))
		{
			// for cluster backup process only affected cluster files
			if ($root == true && $cluster == true
				&& (preg_match('/@{1}((d*[0-9])+)@{1}/sm', $filename, $matches)
					&& !in_array($matches[1], $engine->cluster_pages[$root]))
			)
			{
				continue;
			}

			// subdirs skipped
			$fullname = Ut::join_path($dir, $filename);

			if (!is_dir($fullname))
			{
				if (is_readable($fullname))
				{
					// open input and output files
					$filep		= fopen($fullname, 'rb');
					$packname	= Ut::join_path($pack, $dir, $filename . BACKUP_FILE_GZIP_SUFFIX);
					$filez		= gzopen($packname, 'ab' . BACKUP_COMPRESSION_RATE);
					$r			= 0; // round number

					// compress and write data
					while (($data = fread($filep, BACKUP_MEMORY_STEP)))
					{
						gzwrite($filez, $data);
						fseek($filep, (++$r) * BACKUP_MEMORY_STEP);
					}

					// close files
					gzclose($filez);
					fclose($filep);
					chmod($packname, 0644);
					$t++;	// total files processed
				}
				else
				{
					// Show warning
					$error .= 'Can\'t read <code>' . $dir.'/' . $filename.'</code>.<br />';
				}
			}
		}

		closedir($dh);

		if ($error)
		{
			$engine->show_message($error, 'error') ;
		}

		return $t;
	}

	return false;
}

// restore tables structure
function put_table(&$engine, $pack)
{
	// read sql data
	$file	= Ut::join_path(UPLOAD_BACKUP_DIR, $pack, BACKUP_FILE_STRUCTURE);
	$sql	= explode(';', file_get_contents($file));

	array_pop($sql);

	// perform
	$t		= 0;

	foreach ($sql as $instruction)
	{
		$engine->db->sql_query($instruction);
		$t++;
	}

	return $t;
}

// insert table dump into the database
// $mode - sql instruction to be used (i.e. INSERT or REPLACE)
function put_data(&$engine, $pack, $table, $mode)
{
	$point		= '';

	// open table dump file with read access
	$filename	= Ut::join_path(UPLOAD_BACKUP_DIR, $pack, $table . BACKUP_FILE_DUMP_SUFFIX);
	$file		= gzopen($filename, 'rb');

	// read and process file in iterations to the end
	$t			= 0;

	while (true == $data = gzread($file, BACKUP_MEMORY_STEP))
	{
		// determine length of the uncut data block
		// and sum it to all bytes read previously
		$clean = strrpos($data, "\n");
		$point += $clean + 1;

		// okay, this is it
		$data	= substr($data, 0, $clean);
		$data	= explode("\n", $data);
		$i		= 0;

		// processing...
		foreach ($data as $row)
		{
			// wipe current data row to stay in low memory boundaries
			$data[$i++] = '';

			$row = explode("\t", $row);

			// unescape divider chars
			foreach($row as &$rstr)
			{
				$rstr = strtr($rstr, [
					"\\\\"	=> "\\",
					'\\n'	=> "\n",
					'\\t'	=> "\t"
					]);
			}

			// prepare data
			$j = 0;

			foreach ($row as $cell)
			{
				// DEFAULT (NULL, '', etc.)
				if ($cell == '')
				{
					$row[$j++] = 'DEFAULT';
				}
				else
				{
					$row[$j++] = $engine->db->q($cell); //( $cell == 'null' ? $cell :  $engine->db->q($cell) );
				}
			}

			// setting the SQL Mode, disable possible Strict SQL Mode
			$engine->db->sql_query("SET SESSION sql_mode = 'NO_ENGINE_SUBSTITUTION';");

			// run and count sql query
			$engine->db->sql_query("$mode INTO $table VALUES ( ".implode(', ', $row)." )");
			$t++;	// rows processed
		}

		// set read pointer to the beginning of the next slack row
		gzseek($file, $point);
	}

	// close file
	gzclose($file);

	return $t;
}

// decompress files and restore them into the filesystem
function put_files(&$engine, $pack, $dir, $keep = false)
{
	$total	= [];

	$packdir = Ut::join_path(UPLOAD_BACKUP_DIR, $pack, $dir);

	// restore files subdir or full path recursively if needed
	$offset	= 0;

	while (($offlen = strpos($dir, '/', $offset)) !== false)
	{
		$offset = $offlen + 1;
		ensure_dir(substr($dir, 0, $offlen));
	}

	ensure_dir($dir);

	// open backup dir and run through all files
	if ($dh = opendir($packdir))
	{
		while (false !== ($filename = readdir($dh)))
		{
			$plainfile = substr($filename, 0, strpos($filename, BACKUP_FILE_GZIP_SUFFIX));

			// skip subdirs
			if (!is_dir(Ut::join_path($packdir, $filename)))
			{
				$fullname = Ut::join_path($dir, $plainfile);

				// handle duplicates in target dir
				if (file_exists($fullname))
				{
					if ($keep)
					{
						// ignore
						$total[1]++;
						continue;
					}
					else
					{
						// replace
						unlink($fullname);
					}
				}

				// open input and output files
				$filez	= gzopen(Ut::join_path($packdir, $filename), 'rb');
				$filep	= fopen($fullname, 'wb');
				$r		= 0; // round number

				// decompress and write data
				while (($data = gzread($filez, BACKUP_MEMORY_STEP)))
				{
					fwrite($filep, $data);
					gzseek($filez, (++$r) * BACKUP_MEMORY_STEP);
				}

				// close files
				fclose($filep);
				gzclose($filez);
				chmod($fullname, 0644);
				$total[0]++;
			}
		}

		closedir($dh);

		return $total;
	}

	return false;
}

// draws a tick or cross next to a result
function output_image(&$engine, $ok)
{
	return '<img src="' . $engine->db->base_url . 'setup/image/spacer.png" width="20" height="20" alt="' . ($ok ? 'OK' : 'Problem') . '" title="' . ($ok ? 'OK' : 'Problem') . '" class="tickcross ' . ($ok ? 'tick' : 'cross') . '" />'.' ';
}
