<div class="pageBefore">&nbsp;</div>
<div class="page">
  <?php

if ($this->UserIsOwner())
{
	if ($_POST)
	{
		$pages = $this->LoadAll("select ".$this->pages_meta." from ".
		$this->config["table_prefix"]."pages where (supertag = '".quote($this->dblink, $this->tag)."'".
            " OR supertag like '".quote($this->dblink, $this->tag."/%")."'".
            " OR comment_on = '".quote($this->dblink, $this->tag)."'".
            " OR comment_on like '".quote($this->dblink, $this->tag."/%")."'".
            ") AND owner='".quote($this->dblink, $this->GetUserName())."'");

		foreach ($pages as $num=>$page)
		{
			// store lists
			$this->SaveAcl($page["tag"], "read", $_POST["read_acl"]);
			$this->SaveAcl($page["tag"], "write", $page["comment_on"] == '' ? $_POST["write_acl"] : '');
			$this->SaveAcl($page["tag"], "comment", $page["comment_on"] == '' ? $_POST["comment_acl"] : '');
			// change owner?
			if ($newowner = $_POST["newowner"])
			$this->SetPageOwner($page["tag"], $newowner);
		}
		$message = $this->GetResourceValue("ACLUpdated");
		if ($newowner = $_POST["newowner"])
		$message .= $this->GetResourceValue("ACLGaveOwnership").$newowner;

		// redirect back to page
		$this->SetMessage($message."!");
		$this->Redirect($this->Href());
	}
	else
	{
		// load acls
		$readACL = $this->LoadAcl($this->GetPageTag(), "read");
		$writeACL = $this->LoadAcl($this->GetPageTag(), "write");
		$commentACL = $this->LoadAcl($this->GetPageTag(), "comment");

		// show form
		?>
  <h3><?php echo str_replace("%1",$this->Link("/".$this->GetPageTag()),$this->GetResourceValue("ACLForCluster")); ?></h3>
  <br />
  <?php echo $this->FormOpen("massacls") ?>
  <div class="cssform">
    <p><label<?php echo $this->GetResourceValue("ACLRead"); ?>
      </label>
      <textarea name="read_acl" rows="4" cols="20"><?php echo $readACL["list"] ?></textarea>
    </p>
    <p>
      <label><?php echo $this->GetResourceValue("ACLWrite"); ?></label>
      <textarea name="write_acl" rows="4" cols="20"><?php echo $writeACL["list"] ?></textarea>
    </p>
    <p>
      <label><?php echo $this->GetResourceValue("ACLComment"); ?></label>
      <textarea name="comment_acl" rows="4" cols="20"><?php echo $commentACL["list"] ?></textarea>
    </p>
    <p>
      <label><?php echo $this->GetResourceValue("SetOwner"); ?></label>
      <select name="newowner">
        <option value=""><?php echo $this->GetResourceValue("OwnerDon'tChange"); ?></option>
        <?php
			if ($users = $this->LoadUsers())
			{
				foreach($users as $user)
				{
					print("<option value=\"".htmlspecialchars($user["name"])."\">".$user["name"]."</option>\n");
				}
			}
			?>
      </select>
    </p>
    <p>
      <input class="OkBtn" onmouseover='this.className=&quot;OkBtn_&quot;;'
			onmouseout='this.className=&quot;OkBtn&quot;;'
			onclick='return confirm(&quot;<?php echo $this->GetResourceValue("ACLAreYouSure"); ?>&quot;)'
			type="submit" align="top"
			value="<?php echo $this->GetResourceValue("ACLStoreButton"); ?>"
			style="width: 120px" />
      <input class="CancelBtn"
			onmouseover='this.className=&quot;CancelBtn_&quot;;'
			onmouseout='this.className=&quot;CancelBtn&quot;;' type="button" align="top"
			value="<?php echo $this->GetResourceValue("ACLCancelButton"); ?>"
			onclick="document.location='<?php echo addslashes($this->href(""))?>';"
			style="width: 120px" />
    </p>
  </div>
  <?php
			print($this->FormClose());
}
}
else
{
	print($this->GetResourceValue("ACLAccessDenied"));
}
?>
  <br />
  [<a	href="<?php echo $this->href("acls" );?>"><?php echo $this->GetResourceValue("SettingsAcls" ); ?></a>] </div>
