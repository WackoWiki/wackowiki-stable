<?php

if (!defined('IN_WACKO'))
{
	exit;
}

// list -
// nomark -
// path -

$path = $this->config['category_page'];
if (!isset($list))		$list = 0;
if (empty($path))		$path = 'Category';
if (!isset($nomark))	$nomark = '';

$output	= '';
$i		= '';

if ($list)
{
	if (!$nomark)
	{
		echo '<div class="layout-box"><p class="layout-box"><span>'.$this->get_translation('Categories').":</span></p>\n";
	}

	echo '<ol>';
}
else
{
	if (!$nomark)
	{
		echo "<div class=\"layout-box\">\n";
	}
}

if (isset($this->categories))
{
	foreach($this->categories as $id => $category)
	{
		$_category = '<a href="'.$this->href('', $path, 'category='.$id).'" class="tag" rel="tag">'.htmlspecialchars($category, ENT_COMPAT | ENT_HTML401, HTML_ENTITIES_CHARSET).'</a>';

		if ($list)
		{
			$output .= '<li>'.$_category.'</li>';
		}
		else
		{
			if ($i++ > 0)
			{
				$output .= ', ';
			}

			$output .= $_category;
		}
	}
}

echo (!empty($_category) && (!$list) ? $this->get_translation('Categories').': ' : '').$output;

if ($list)
{
	echo '</ol>';

}
if (!$nomark)
{
	echo "</div>\n";
}

# $this->debug_print_r($this->categories);

?>