<?php

if (!defined('IN_WACKO'))
{
	exit;
}

if ($text == '')
{
	return;
}

$this->use_class('typografica', 'formatters/classes/');

$typo = new typografica($this);

// kuso: since dashglued cause rendering bugs in Firefox, this option is now turned off.
$typo->settings['dashglue'] = false;

echo $typo->correct($text, false);

?>