<?php

if (!defined('IN_WACKO'))
{
	exit;
}

/*
	{{import}}
	http://example.com/somecluster/import --> {{import}}, to = "Test".
	Will be imported at: http://example.com/Test/*

	i.e. no relative addressing
*/

// TODO: add a step for warning / confirmation (do you want overwrite? / Will add Import under ... [submit] [cancel])
// add better description
// finally localize all new message sets

$t = '';

if ($this->is_admin())
{
	if (!isset($_POST['_to']) || empty($_POST['_to']))
	{
		if (isset($_POST['_to']))
		{
			echo 'Pls. provide an cluster you want to import to, no relative addressing.<br /><br />';
		}
		else
		{
			echo 'Attention: overwrites the same pages in the cluster<br /><br />';
		}
		// show FORM
		// STS rawurldecode!? good ganja! why?
		echo rawurldecode($this->form_open('import_xml', ['form_more' => ' enctype="multipart/form-data" ']));
		?>
		<div class="cssform">
			<p>
				<label for="importto"><?php echo $this->_t('ImportTo'); ?>:</label>
				<input type="text" id="importto" name="_to" size="40" value="" />
			</p>
			<p>
				<label for="importwhat"><?php echo $this->_t('ImportWhat'); ?>:</label>
				<input type="file" id="importwhat" name="_import" />
			</p>
			<p>
				<input type="submit" value="<?php echo $this->_t('ImportButtonText'); ?>" />
			</p>
		</div>
		<?php
		echo $this->form_close();
	}
	if (!empty($_POST['_to']))
	{
		if ($_FILES['_import']['error'] == 0)
		{
			$fd = fopen($_FILES['_import']['tmp_name'], 'r');

			if (!$fd)
			{
				echo '<pre>';
				print_r($_FILES);
				print_r($_POST);
				die('</pre><br />IMPORT failed');
			}

			// check for false and empty strings
			if (($contents = fread($fd, filesize($_FILES['_import']['tmp_name']))) === '')
			{
				return false;
			}

			fclose($fd);

			$items = explode('<item>', $contents);

			array_shift($items);

			foreach ($items as $item)
			{
				$root_tag	= trim($_POST['_to'], '/ ');
				$rel_tag	= trim(Ut::untag($item, 'guid'), '/ ');
				$tag		= $root_tag.( $root_tag && $rel_tag ? '/' : '' ).$rel_tag;
				$page_id	= $this->get_page_id($tag);
				$owner		= Ut::untag($item, 'author');
				$owner_id	= $this->get_user_id($owner);
				$body		= str_replace(']]&gt;', ']]>', Ut::untag($item, 'description'));
				$title		= html_entity_decode(Ut::untag($item, 'title'), ENT_COMPAT | ENT_HTML401, HTML_ENTITIES_CHARSET);

				$body_r = $this->save_page($tag, $title, $body, '');
				$this->set_page_owner($page_id, $owner_id);
				// now we render it internally in the context of imported
				// page so we can write the updated link table
				$this->context[++$this->current_context] = $tag;
				$this->update_link_table($page_id, $body_r);
				$this->current_context--;

				// log import
				$this->log(4, Ut::perc_replace($this->_t('LogPageImported', SYSTEM_LANG), $tag));

				// count page
				$t++;
				$pages[] = $tag;
			}

			echo '<em>'.Ut::perc_replace($this->_t('ImportSuccess'), $t).'</em><br />';

			foreach ($pages as $page)
			{
				echo $this->link('/'.$page, '', '', '', 0).'<br />';
			}
		}
		else
		{
			echo '<pre>';
			print_r($_FILES);
			print_r($_POST);
			die('</pre><br />IMPORT failed');
		}
	}
}