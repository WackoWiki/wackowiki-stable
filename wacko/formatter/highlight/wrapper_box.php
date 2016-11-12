<?php

if (!isset($options['wrapper_align'])) $options['wrapper_align'] = 'right';
if (!isset($options['wrapper_width'])) $options['wrapper_width'] = 250;

if ($options['wrapper_align'] == 'center')
{
	$align_style = 'margin: 0 auto;';
}
else
{
	$align_style = 'float: ' . $options['wrapper_align'] . ';';
}

echo	'<aside class="action" style="' . $align_style.' width: ' . $options['wrapper_width'] . 'px;">'."\n".
			'<div class="action-content">'."\n".
				$text.
			"</div>\n".
		"</aside>\n";

?>