<?php

if (!defined('IN_WACKO'))
{
	exit;
}

$parser = new bbcode($this);

$text	= preg_replace_callback($parser->template, [&$parser, 'wrapper'], $text);

echo $text;


?>
