<div id="page" class="page">
<h3><?php echo $this->GetTranslation("CreateNewPage") ?></h3>
<br />
<?php

// process input
if (isset($_POST['tag']) && $newtag = trim($_POST['tag'], '/ '))
{
	switch ((int)$_POST['option'])
	{
		case 1:
			$prefix = $this->tag.'/';
			break;
		case 2:
			$prefix = substr($this->tag, 0, strrpos($this->tag, '/') + 1);
			break;
		default:
			$prefix = '';
	}

	// check target page existance
	if ($page = $this->LoadPage($prefix.$newtag, '', LOAD_CACHE, LOAD_META))
	{
		$message = $this->GetTranslation("PageAlreadyExists")." &laquo;".$page['tag']."&raquo;. ";

		// check existing page write access
		if ($this->HasAccess('write', $this->GetPageId($prefix.$newtag)))
		{
			$message .= str_replace('%1', "<a href=\"".$this->href('edit', $prefix.$newtag)."\">".$this->GetTranslation("PageAlreadyExistsEdit2")." </a>?", $this->GetTranslation("PageAlreadyExistsEdit"));
		}
		else
		{
			$message .= $this->GetTranslation("PageAlreadyExistsEditDenied");
		}
		$this->SetMessage($message);
	}
	else
	{
		// check new page write access
		if ($this->HasAccess('write', $this->GetPageId($prefix.$newtag)))
		{
			// str_replace: fixed newPage&amp;add=1
			$this->Redirect(str_replace("&amp;", "&", ($this->href("edit", $prefix.$newtag, "", 1))));
		}
		else
		{
			$this->SetMessage($this->GetTranslation("CreatePageDeniedAddress"));
		}
	}
}

// show form

// create a peer page
echo $this->FormOpen('new');
echo "<input type=\"hidden\" name=\"option\" value=\"1\" />";
echo "<label for=\"create_subpage\">".$this->GetTranslation("CreateSubPage").":</label><br />";
if ($this->HasAccess('write', $this->GetPageId($this->tag)))
{
	echo "<tt>".( strlen($this->tag) > 50 ? "...".substr($this->tag, -50) : $this->tag )."/</tt>".
		"<input id=\"create_subpage\" name=\"tag\" value=\"".( isset($_POST['option']) && $_POST['option'] === '1' ? htmlspecialchars($newtag) : "" )."\" size=\"20\" maxlength=\"255\" /> ".
		"<input id=\"submit_subpage\" type=\"submit\" value=\"".$this->GetTranslation("CreatePageButton")."\" />";
}
else
{
	echo "<em>".$this->GetTranslation("CreatePageDenied")."</em>";
}
echo "";
echo $this->FormClose();
echo "<br />";

// create a child page. only inside a cluster
if (substr_count($this->tag, '/') > 0)
{
	$parent = substr($this->tag, 0, strrpos($this->tag, '/'));

	echo $this->FormOpen('new');
	echo "<input type=\"hidden\" name=\"option\" value=\"2\" />";
	echo "<label for=\"create_pageparentcluster\">".$this->GetTranslation("CreatePageParentCluster").":</label><br />";
	if ($this->HasAccess('write', $this->GetPageId($parent)))
	{
		echo "<tt>".( strlen($parent) > 50 ? "...".substr($parent, -50) : $parent )."/</tt>".
			"<input id=\"create_pageparentcluster\" name=\"tag\" value=\"".( isset($_POST['option']) && $_POST['option'] === '2' ? htmlspecialchars($newtag) : "" )."\" size=\"20\" maxlength=\"255\" /> ".
			"<input id=\"submit_pageparentcluster\" type=\"submit\" value=\"".$this->GetTranslation("CreatePageButton")."\" />";
	}
	else
	{
		echo "<em>".$this->GetTranslation("CreatePageDenied")."</em>";
	}
	echo "";
	echo $this->FormClose();
	echo "<br />";
}

//
echo $this->FormOpen('new');
echo "<input type=\"hidden\" name=\"option\" value=\"3\" />";
echo "<label for=\"create_randompage\">".$this->GetTranslation("CreateRandomPage").":</label><br />";
echo "<input id=\"create_randompage\" name=\"tag\" value=\"".( isset($_POST['option']) && $_POST['option'] === '3' ? htmlspecialchars($newtag) : "" )."\" size=\"60\" maxlength=\"255\" /> ".
	"<input id=\"submit_randompage\" type=\"submit\" value=\"".$this->GetTranslation("CreatePageButton")."\" />";
echo "";
echo $this->FormClose();

?>
</div>