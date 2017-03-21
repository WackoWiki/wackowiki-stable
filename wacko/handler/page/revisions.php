<?php

if (!defined('IN_WACKO'))
{
	exit;
}

$place_holder	= '&nbsp;&nbsp;&nbsp;';

// redirect to show method if hide_revisions is true
if ($this->hide_revisions)
{
	$this->http->redirect($this->href());
}

$this->ensure_page(true);

// show minor edits
if ($this->db->minor_edit)
{
	$hide_minor_edit = (int) @$_GET['minor_edit'];
}
else
{
	$hide_minor_edit = 0;
}

// show deleted pages
$show_deleted = $this->is_admin();

// get page_id for deleted but stored page
if ($this->page['deleted'])
{
	$this->show_message(
			// $this->_t('DoesNotExists') . " " . ( $this->has_access('create') ?  str_replace('%1', $this->href('edit', '', '', 1), $this->_t('PromptCreate')) : '').
			'BACKUP of deleted page!' // TODO: localize and add description: to restore the page you ...
			);
}

if ($this->has_access('read'))
{
	// load revisions for this page
	if (($revisions = $this->load_revisions($this->page['page_id'], $hide_minor_edit, $show_deleted)))
	{
		$this->context[++$this->current_context] = '';

		echo $this->form_open('diff_versions', ['page_method' => 'diff', 'form_method' => 'get']);
		echo "<p>\n";
		echo '<input type="submit" value="' . $this->_t('ShowDifferencesButton') . '" />';

		$default_mode	= $this->db->default_diff_mode; // TODO: configurable per user
		$diff_modes		= $this->_t('DiffMode');
		$diff_mode_list	= explode(',', $this->db->diff_modes);

		foreach($diff_mode_list as $mode)
		{
			echo $place_holder .
				'<input type="radio" id="' . 'diff_mode_' . $mode . '" name="diffmode" value="' . $mode . '"' .
				($mode == $default_mode? ' checked' : '') . ' />' .
				'<label for="' . 'diff_mode_' . $mode . '">' . $diff_modes[$mode] . '</label>';
		}

		echo $place_holder.
			'<a href="' . $this->href('revisions.xml') . '"><img src="' .
			$this->db->theme_url . 'icon/spacer.png' . '" title="' . $this->_t('RevisionXMLTip') .
			'" alt="XML" class="btn-feed"/></a>';

		if ($this->db->minor_edit)
		{
			// STS: ?!..
			// filter minor edits
			echo '<input name="minor_edit" value="' . $hide_minor_edit . '" type="hidden">' . "\n";
			echo '<br />' . ($hide_minor_edit
					? '<a href="' . $this->href('revisions', '', 'minor_edit=0') . '">' . $this->_t('MinorEditShow') . '</a>'
					: '<a href="' . $this->href('revisions', '', 'minor_edit=1') . '">' . $this->_t('MinorEditHide') . '</a>');
		}

		echo "</p>\n" . '<ul class="revisions">' . "\n";

		if (@$_GET['show'] == 'all')
		{
			$max = 0;
		}
		else if (($user = $this->get_user()))
		{
			$max = $user['list_count'];
		}
		else
		{
			$max = $this->db->list_count;
		}

		$c = 0;
		$revision_count		= count($revisions);
		$diff_class			= '';
		$this->parent_size	= 0;

		// get size diff to parent version
		$r_revisions = array_reverse($revisions);

		foreach ($r_revisions as $r)
		{
			// page_size change
			$size_delta			= $r['page_size'] - $this->parent_size;
			$this->parent_size	= $r['page_size'];

			$this->rev_delta[$r['revision_id']] = $size_delta;
		}

		foreach ($revisions as $num => $page)
		{
			if ($page['edit_note'])
			{
				$edit_note = '<span class="editnote">[' . $page['edit_note'] . ']</span>';
			}
			else
			{
				$edit_note = '';
			}

			// page_size change
			$size_delta = $this->rev_delta[$page['revision_id']];

			echo '<li>';
			echo '<span style="display: inline-block; width:40px;">' . $page['version_id'] . '.</span>';
			echo '<input type="radio" name="a" value="' . (!$c ? '-1' : $page['revision_id']) . '" '.($c == 0 ? 'checked' : '') . ' />';
			echo $place_holder.
						'<input type="radio" name="b" value="' . (!$c ? '-1' : $page['revision_id']) . '" '.($c == 1 ? 'checked' : '') . ' />';
			echo $place_holder . '&nbsp;
						<a href="' . $this->href('show', '', 'revision_id=' . $page['revision_id']) . '">' . $this->get_time_formatted($page['modified']) . '</a>';
			echo '<span style="display: inline-block; width:130px;">' . "&nbsp; � (" . $this->binary_multiples($page['page_size'], false, true, true) . ') ' . $this->delta_formatted($size_delta) . "</span> ";
			echo $place_holder."&nbsp;" . $this->_t('By') . " " .
						$this->user_link($page['user_name'], '', true, false) . ' ';
			echo $edit_note;
			echo ' ' . ($page['minor_edit'] ? 'm' : '');

			// review
			if ($this->db->review)
			{
				if ($page['reviewed'])
				{
					echo '<span class="review">[' . $this->_t('ReviewedBy') . ' ' . $this->user_link($page['reviewer'], '', true, false) . ']</span>';
				}
				else if ($this->is_reviewer())
				{
					if (!$num)
					{
						echo ' <span class="review">[' . $this->_t('Review') . ']</span>';
					}
				}
			}

			echo "</li>\n";

			if (++$c >= $max && $max)
			{
				break;
			}
		}

		echo "</ul>\n<br />\n";

		if ($max && $revision_count > $max)
		{
			echo  '<a href="' . $this->href('revisions', '', 'show=all') . '">' . $this->_t('RevisionsShowAll') . "</a><br /><br />\n";
		}

		echo '<a href="' . $this->href() . '" style="text-decoration: none;">' .
				'<input type="button" value="' . $this->_t('CancelDifferencesButton') . '" />' .
			 '</a>' . "\n";

		echo $this->form_close() . "\n";
	}

	$this->current_context--;
}
else
{
	$this->show_message($this->_t('ReadAccessDenied'), 'error');
}