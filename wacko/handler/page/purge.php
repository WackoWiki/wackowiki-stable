<?php

if (!defined('IN_WACKO'))
{
	exit;
}

echo '<h3>' . $this->_t('PurgePage') . ' ' . $this->compose_link_to_page($this->tag, '', '', 0) . "</h3>\n";

$this->ensure_page();

// TODO: config->owners_can_remove_comments ?
if (!($this->is_owner() || $this->is_admin()))
{
	$this->set_message('<em>' . $this->_t('NotOwnerAndCantPurge') . '</em>', 'error');
	$this->show_must_go_on();
}

if (@$_POST['_action'] === 'purge_data')
{
	// purge page
	$message = "<ol><em>";

	$title = $this->tag . ' ' . $this->page['title'];

	if (isset($_POST['comments']))
	{
		$this->remove_comments($this->tag);
		$this->log(1, Ut::perc_replace($this->_t('LogRemovedAllComments', SYSTEM_LANG), $title));
		$message .= "<li>" . $this->_t('CommentsPurged') . "</li>\n";
	}

	if (isset($_POST['files']))
	{
		$this->remove_files($this->tag);
		$this->log(1, Ut::perc_replace($this->_t('LogRemovedAllFiles', SYSTEM_LANG), $title));
		$message .= "<li>" . $this->_t('FilesPurged') . "</li>\n";
	}

	if (isset($_POST['revisions']) && $this->is_admin())
	{
		$this->remove_revisions($this->tag);
		$this->log(1, Ut::perc_replace($this->_t('LogRemovedAllRevisions', SYSTEM_LANG), $title));
		$message .= "<li>" . $this->_t('RevisionsPurged') . "</li>\n";
	}

	// purge related page cache
	if ($this->http->invalidate_page($this->supertag))
	{
		$message .= '<li>' . $this->_t('PageCachePurged') . "</li>\n";
	}

	$message .= '</em></ol><br />';
	$message .= $this->_t('ThisActionHavenotUndo') . "\n";

	$this->show_message($message, 'success');
}
else
{
	echo '<div class="warning">' . $this->_t('ReallyPurge') . '</div><br />';
	echo $this->form_open('purge_data', ['page_method' => 'purge']);
?>

	<strong><?php echo $this->_t('SelectPurgeOptions') ?></strong><br />
	<input type="checkbox" id="purgecomments" name="comments" />
	<label for="purgecomments"><?php echo $this->_t('PurgeComments') ?></label><br />
	<input type="checkbox" id="purgefiles" name="files" />
	<label for="purgefiles"><?php echo $this->_t('PurgeFiles') ?></label><br />
<?php
	if ($this->is_admin())
	{
?>
		<input type="checkbox" id="purgerevisions" name="revisions" />
		<label for="purgerevisions"><?php echo $this->_t('PurgeRevisions') ?></label><br />
<?php
	}
?>
	<br />
	<input type="submit" id="submit" name="submit" value="<?php echo $this->_t('PurgeButton'); ?>" />
	<a href="<?php echo $this->href('properties');?>" style="text-decoration: none;"><input type="button" id="button" value="<?php echo $this->_t('EditCancelButton'); ?>" /></a>
	<br />

<?php	echo $this->form_close();
}