<?php

if (!defined('IN_WACKO'))
{
	exit;
}

$parser = new bbcode($this);

$text	= preg_replace_callback($parser->template, array(&$parser, 'wrapper'), $text);

echo $text;


?>
