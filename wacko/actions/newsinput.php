<?php

if (!defined('IN_WACKO'))
{
	exit;
}

?>
<!--notypo-->
<?php

if ((isset($_POST['action'])) && $_POST['action'] == 'newsadd')
{
	// checking user input
	if (isset($_POST['title']))
	{
		$name		= trim($_POST['title'], ". \t");
		$namehead	= $name;
		$name		= ucwords($name);
		$name		= preg_replace('/[^- \\w]/', '', $name);
		$name		= str_replace(array(' ', "\t"), '', $name);

		if ($name == '')
		{
			$error = $this->get_translation('NewsNoName');
		}
	}
	else
	{
		$error = $this->get_translation('NewsNoName');
	}

	// if errors were found - return, else continue
	if ($error)
	{
		$this->set_message('<div class="error">'.$error.'</div>');
		$this->redirect($this->href());
	}
	else
	{
		// building news template
		$template	= '';

		// redirecting to the edit form
		$_SESSION['body']	= $template;
		$_SESSION['title']	= $namehead;

		// needs to be numeric for ordering
		// TODO: add this as config option to Admin panel
		// .date('Y/')							- 2011
		// .date('Y/').date('m/')				- 2011/07 (default)
		// .date('Y/').date('m/').date('d/')	- 2011/07/14
		// .date('Y/').date('W/')				- 2011/29
		$news_cluster_structure = date('Y/').date('m/');

		$this->redirect($this->href('edit', $this->config['news_cluster'].'/'.$news_cluster_structure.$name, '', 1));
	}
}

if (!empty($this->config['news_cluster']))
{
	echo $this->form_open();
?>
	<input type="hidden" name="action" value="newsadd" />
	<label for="newstitle"><?php echo $this->get_translation('NewsName'); ?>:</label>
	<input id="newstitle" name="title" size="50" maxlength="100" value="" />
	<input id="submit" type="submit" value="<?php echo $this->get_translation('NewsSubmit'); ?>" />

<?php echo $this->form_close();
}
else
{
	echo $this->get_translation('NewsNoClusterDefined');
}
?>
<!--/notypo-->