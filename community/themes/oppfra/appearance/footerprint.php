<div id="footer">
	&copy; <?php echo date("Y"); ?> &quot;<?php echo $this->config['site_name']; ?>&quot;.
	<?php echo $this->get_translation('TermsOfUse'); ?>: <a href="<?php echo htmlspecialchars($this->href('', $this->config['policy_page']), ENT_COMPAT | ENT_HTML401, $this->charset); ?>"><?php echo $this->config['base_url'].$this->config['policy_page']; ?></a>
</div>
<?php
// Don't place final </body></html> here. Wacko closes HTML automatically.
?>