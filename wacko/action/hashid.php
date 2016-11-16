<?php

if (!defined('IN_WACKO'))
{
	exit;
}

// {{hashid}}

$hashids = new Hashids($this->db->hashid_seed);

if (isset($this->page['version_id']))
{
	$version_id = $this->page['version_id'];
}
else
{
	$_old_version = $this->db->load_single(
		"SELECT version_id " .
		"FROM {$this->db->table_prefix}revision " .
		"WHERE page_id = '" . $this->page['page_id'] . "' " .
		"ORDER BY version_id DESC " .
		"LIMIT 1");
	$version_id = $_old_version['version_id'] + 1;
}

$ids = [$this->page['page_id'], $version_id];
sscanf(hash('sha1', $ids[0] . $this->db->hashid_seed . $ids[1]), '%7x', $ids[2]);

$id = $hashids->encode($ids);

// dbg('hashiding', $ids, '=>', $id);

$tpl->url = $this->href('', $id);
