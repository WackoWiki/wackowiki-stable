<?php

if (!isset($options['wrapper_width'])) $options['wrapper_width'] = '800';

echo '<div style="width:'.$options['wrapper_width'].'px">'."\n";
echo $text;
echo "</div>\n";

?>