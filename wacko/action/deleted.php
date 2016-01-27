<?php

if (!defined('IN_WACKO'))
{
	exit;
}

if ($this->is_admin())
{
	if (!isset($max) || $max > 1000) $max = 1000;

	$pages = $this->load_deleted($max);

	if ($pages == true)
	{
		$i = 0;

		echo '<ul class="ul_list">'."\n";

		foreach ($pages as $page)
		{
			$i++;

			if ($this->config['hide_locked'])
			{
				$access = $this->has_access('read', $page['page_id']);
			}
			else
			{
				$access = true;
			}

			if ($access === true)
			{
				// tz offset
				$time_tz = $this->get_time_tz( strtotime($page['modified']) );
				$time_tz = date('Y-m-d H:i:s', $time_tz);

				// day header
				list($day, $time) = explode(' ', $time_tz);

				if (!isset($curday)) $curday = '';

				if ($day != $curday)
				{
					if ($curday)
					{
						echo "</ul>\n<br /></li>\n";
					}

					echo "<li><strong>".date($this->config['date_format'], strtotime($day)).":</strong>\n<ul>\n";
					$curday = $day;
				}

				// print entry
				echo "<li>".
						'<span style="text-align:left">'.
							"<small>".date($this->config['time_format_seconds'], strtotime($time))."</small>  &mdash; ".
							#$this->compose_link_to_page($page['tag'], 'revisions', '', 0).
							$this->compose_link_to_page($page['tag'], '', '', 0).
						"</span>".
					"</li>\n";
			}

			if ($i >= $max) break;
		}

		echo "</ul>\n</li>\n</ul>";
	}
	else
	{
		echo $this->get_translation('NoRecentlyDeleted');
	}
}

?>