<?php

if (!defined('IN_WACKO'))
{
	exit;
}

########################################################
##   Basic settings                                   ##
########################################################

$module['config_basic'] = [
		'order'	=> 200,
		'cat'	=> 'Preferences',
		'status'=> (RECOVERY_MODE ? false : true),
		'mode'	=> 'config_basic',
		'name'	=> 'Basic',
		'title'	=> 'Basic parameters',
	];

########################################################

function admin_config_basic(&$engine, &$module)
{
?>
	<h1><?php echo $module['title']; ?></h1>
	<br />
<?php
	// update settings
	if (isset($_POST['action']) && $_POST['action'] == 'update')
	{
		#$engine->debug_print_r($_POST);
		$config['site_name']				= (string)$_POST['site_name'];
		$config['site_desc']				= (string)$_POST['site_desc'];
		$config['meta_description']			= (string)$_POST['meta_description'];
		$config['meta_keywords']			= (string)$_POST['meta_keywords'];
		$config['theme']					= (string)$_POST['theme'];
		$config['allow_themes']				= (string)$_POST['allow_themes'];
		$config['allow_themes_per_page']	= (string)$_POST['themes_per_page'];
		$config['admin_name']				= (string)$_POST['admin_name'];
		$config['language']					= (string)$_POST['language'];
		$config['multilanguage']			= (int)$_POST['multilanguage'];

		if (is_array($_POST['allowed_languages']))
		{
			$config['allowed_languages'] = (string) implode(',', $_POST['allowed_languages']);
		}
		else
		{
			$config['allowed_languages'] = 0;
		}

		$config['footer_comments']			= (int)$_POST['footer_comments'];
		$config['footer_files']				= (int)$_POST['footer_files'];
		$config['footer_rating']			= (int)$_POST['footer_rating'];
		$config['footer_tags']				= (int)$_POST['footer_tags'];
		$config['hide_revisions']			= (int)$_POST['hide_revisions'];
		$config['hide_toc']					= (int)$_POST['hide_toc'];
		$config['hide_index']				= (int)$_POST['hide_index'];
		$config['tree_level']				= (int)$_POST['tree_level'];
		$config['menu_items']				= (int)$_POST['menu_items'];
		$config['edit_summary']				= (int)$_POST['edit_summary'];
		$config['minor_edit']				= (int)$_POST['minor_edit'];
		$config['review']					= (int)$_POST['review'];
		$config['publish_anonymously']		= (int)$_POST['publish_anonymously'];
		$config['disable_autosubscribe']	= (int)$_POST['disable_autosubscribe'];
		$config['default_rename_redirect']	= (int)$_POST['default_rename_redirect'];
		$config['store_deleted_pages']		= (int)$_POST['store_deleted_pages'];
		$config['keep_deleted_time']		= (string)$_POST['keep_deleted_time'];
		$config['pages_purge_time']			= (string)$_POST['pages_purge_time'];
		$config['referrers_purge_time']		= (string)$_POST['referrers_purge_time'];
		$config['noindex']					= (int)$_POST['noindex'];
		$config['xml_sitemap']				= (int)$_POST['xml_sitemap'];
		$config['xml_sitemap_time']			= (int)$_POST['xml_sitemap_time'];
		$config['enable_feeds']				= (int)$_POST['enable_feeds'];
		$config['enable_referrers']			= (int)$_POST['enable_referrers'];
		$config['enable_comments']			= (int)$_POST['enable_comments'];
		$config['sorting_comments']			= (int)$_POST['sorting_comments'];

		$engine->config->_set($config);

		$engine->log(1, 'Updated basic settings');
		$engine->set_message('Updated basic settings', 'success');
		$engine->http->redirect(rawurldecode($engine->href()));
	}

	echo $engine->form_open('basic');
?>
		<input type="hidden" name="action" value="update" />
		<table class="formation">
			<tr>
				<th colspan="2">Basic parameters</th>
			</tr>
			<tr class="hl_setting">
				<td class="label"><label for="site_name"><strong>Site Name:</strong><br />
					<small>The title that appears on this site, email-notification, etc.</small></label></td>
				<td style="width:40%;"><input type="text" maxlength="255" style="width:200px;" id="site_name" name="site_name" value="<?php echo htmlspecialchars($engine->db->site_name, ENT_COMPAT | ENT_HTML401, HTML_ENTITIES_CHARSET);?>" /></td>
			</tr>
			<tr class="lined">
				<td colspan="2"></td>
			</tr>
			<tr class="hl_setting">
				<td class="label"><label for="site_desc"><strong>Site Description:</strong><br />
					<small>Supplement to the title of the site that appears in the pages header to explain in a few words, what this site is about.</small></label></td>
				<td><input type="text" maxlength="255" style="width:200px;" id="site_desc" name="site_desc" value="<?php echo htmlspecialchars($engine->db->site_desc, ENT_COMPAT | ENT_HTML401, HTML_ENTITIES_CHARSET);?>" /></td>
			</tr>
			<tr class="lined">
				<td colspan="2"></td>
			</tr>
			<tr class="hl_setting">
				<td class="label"  style="vertical-align:top;"><label for="meta_description"><strong>Description of the document by default:</strong><br />
					<small>The text used by default for meta-tags <code>description</code> (maximum of 255 characters).</small></label></td>
				<td><textarea style="font-size:12px; letter-spacing:normal; width:200px; height:100px;" id="meta_description" name="meta_description"><?php echo htmlspecialchars($engine->db->meta_description, ENT_COMPAT | ENT_HTML401, HTML_ENTITIES_CHARSET);?></textarea></td>
			</tr>
			<tr class="lined">
				<td colspan="2"></td>
			</tr>
			<tr class="hl_setting">
				<td class="label"  style="vertical-align:top;"><label for="meta_keywords"><strong>Keywords page default:</strong><br />
					<small>Key words used by default for meta-tags <code>keywords</code> (maximum of 255 characters).</small></label></td>
				<td><textarea style="font-size:12px; letter-spacing:normal; width:200px; height:100px;" id="meta_keywords" name="meta_keywords"><?php echo htmlspecialchars($engine->db->meta_keywords, ENT_COMPAT | ENT_HTML401, HTML_ENTITIES_CHARSET);?></textarea></td>
			</tr>
			<tr class="lined">
				<td colspan="2"></td>
			</tr>
			<tr class="hl_setting">
				<td class="label"><label for="theme"><strong>Theme:</strong><br />
					<small>Template design the site uses by default.</small></label></td>
				<td>
					<select style="width:200px;" id="theme" name="theme">
<?php
						$themes = $engine->available_themes();

						foreach ($themes as $theme)
						{
							echo '<option value="'.$theme.'" '.($engine->db->theme == $theme ? 'selected="selected"' : '').'>'.$theme.'</option>';
						}
?>
					</select>
				</td>
			</tr>
			<tr class="lined">
				<td colspan="2"></td>
			</tr>
			<tr class="hl_setting">
				<td class="label"><label for="allow_themes"><strong>Allowed Themes:</strong><br />
					<small>Allowed themes, which the user can choose: "0" - all available themes are allowed (default), <br />"default,coffee" - here only these both themes are allowed.</small></label></td>
				<td><input type="text" maxlength="25" style="width:200px;" id="allow_themes" name="allow_themes" value="<?php echo htmlspecialchars($engine->db->allow_themes, ENT_COMPAT | ENT_HTML401, HTML_ENTITIES_CHARSET);?>" /></td>
			</tr>
			<tr class="lined">
				<td colspan="2"></td>
			</tr>
			<tr class="hl_setting">
				<td class="label"><strong>Themes per page:</strong><br />
					<small>Allow themes per page, which the page owner can choose via page properties.</small></td>
				<td>
					<input type="radio" id="themes_per_page_on" name="themes_per_page" value="1"<?php echo ( $engine->db->allow_themes_per_page == 1 ? ' checked="checked"' : '' );?> /><label for="themes_per_page_on">On.</label>
					<input type="radio" id="themes_per_page_off" name="themes_per_page" value="0"<?php echo ( $engine->db->allow_themes_per_page == 0 ? ' checked="checked"' : '' );?> /><label for="themes_per_page_off">Off.</label>
				</td>
			</tr>
			<tr class="lined">
				<td colspan="2"></td>
			</tr>
			<tr class="hl_setting">
				<td class="label"><label for="admin_name"><strong>Admin of Site:</strong><br />
					<small>User name, which is responsible for overall support of the site. This name is not used to determine access rights, but it is desirable to conform to the name of the chief administrator of the site.</small></label></td>
				<td><input type="text" maxlength="25" style="width:200px;" id="admin_name" name="admin_name" value="<?php echo htmlspecialchars($engine->db->admin_name, ENT_COMPAT | ENT_HTML401, HTML_ENTITIES_CHARSET);?>" /></td>
			</tr>
			<tr>
				<th colspan="2">
					<br />
					Language settings
				</th>
			</tr>
			<tr class="hl_setting">
				<td class="label"><label for="language"><strong>Default language:</strong><br />
					<small>Specifies the language for mapping unregistered guests, as well as the locale settings and the rules of transliteration of addresses of pages.</small></label></td>
				<td>
					<select style="width:200px;" id="language" name="language">
<?php
						$languages = $engine->_t('LanguageArray');
						$langs = $engine->available_languages();

						foreach ($langs as $lang)
						{
							echo '<option value="'.$lang.'" '.($engine->db->language == $lang ? 'selected="selected"' : '').'>'.$languages[$lang].' ('.$lang.')</option>';
						}
?>
					</select>
				</td>
			</tr>
			<tr class="lined">
				<td colspan="2"></td>
			</tr>
			<tr class="hl_setting">
				<td class="label"><label for="multilanguage"><strong>Multilanguage support:</strong><br />
					<small>Include a choice of language on the page by page basis.</small></label></td>
				<td>
					<input type="checkbox" id="multilanguage" name="multilanguage" value="1"<?php echo ( $engine->db->multilanguage ? ' checked="checked"' : '' );?> />
				</td>
			</tr>
<?php if ($engine->db->multilanguage)
{?>
			<tr class="lined">
				<td colspan="2"></td>
			</tr>
			<tr class="hl_setting">
				<td class="label"><label for=""><strong>Allowed languages:</strong><br />
					<small>It is recomended to select only the set of languages you want to use, other wise all languages are selected.</small></label></td>
				<td>
<?php
					if ($engine->db->multilanguage)
					{
						// subset: false
						$langs = $engine->available_languages(false);
					}
					else
					{
						$langs[] = $engine->db->language;
					}

					if (isset($engine->db->allowed_languages))
					{
						$lang_list = explode(',', $engine->db->allowed_languages);
					}
					else
					{
						$lang_list= [];
					}

					$languages = $engine->_t('LanguageArray');
					$n = 1;

					echo "<table>\n\t<tr>\n";

					foreach ($langs as $lang)
					{
						echo	"\t\t<td>\n\t\t\t".'<input type="checkbox" name="allowed_languages['.$n.']" id="lang_'.$lang.'" value="'.$lang.'" '. (in_array($lang, $lang_list) ? ' checked="checked"' : ''). ' />'."\n\t\t\t".
								'<label for="lang_'.$lang.'">'.$languages[$lang].' ('.$lang.')</label>'."\n\t\t</td>\n";

						// modulus operator: every third loop add a break
						if ($n % 3 == 0)
						{
							echo "\t</tr>\n\t<tr>\n";
						}
;
						$n++;
					}

					echo "\t</tr>\n</table>";
					?>
				</td>
			</tr>
			<?php } ?>
			<tr>
				<th colspan="2">
					<br />
					Comments
				</th>
			</tr>
			<tr class="hl_setting">
				<td class="label"><strong>Allow comments:</strong><br />
					<small>Enable comments for guest or registered users only or disable them on the entire site.</small></td>
				<td>
					<input type="radio" id="enable_comments" name="enable_comments" value="1" <?php echo ( $engine->db->enable_comments == 1 ? ' checked="checked"' : '' );?> /><label for="enable_comments_on">On.</label>
					<input type="radio" id="enable_comments_guest" name="enable_comments" value="2" <?php echo ( $engine->db->enable_comments == 2 ? ' checked="checked"' : '' );?> /><label for="enable_comments_guest">Registered.</label>
					<input type="radio" id="enable_comments_off" name="enable_comments" value="0" <?php echo ( $engine->db->enable_comments == 0 ? ' checked="checked"' : '' );?> /><label for="enable_comments_off">Off.</label>
				</td>
			</tr>
			<tr class="lined">
				<td colspan="2"></td>
			</tr>
			<tr class="hl_setting">
				<td class="label"><strong>Sorting comments:</strong><br />
					<small>Changes the order the page comments are presented, either with the most recent OR the oldest comment at the top.</small></td>
				<td>
					<select id="sorting_comments" name="sorting_comments">
						<option value="0" <?php echo ( $engine->db->sorting_comments  == 0  ? ' selected="selected"' : '' ); ?>><?php echo $engine->_t('SortCommentAsc');?></option>
						<option value="1" <?php echo ( $engine->db->sorting_comments  == 1  ? ' selected="selected"' : '' ); ?>><?php echo $engine->_t('SortCommentDesc');?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th colspan="2">
					<br />
					Toolbar
				</th>
			</tr>
			<tr class="hl_setting">
				<td class="label"><strong>Comments panel:</strong><br />
					<small>The default display of comments in the bottom of the page.</small></td>
				<td>
					<input type="radio" id="footer_comments_on" name="footer_comments" value="1"<?php echo ( $engine->db->footer_comments == 1 ? ' checked="checked"' : '' );?> /><label for="footer_comments_on">On.</label>
					<input type="radio" id="footer_comments_guest" name="footer_comments" value="2"<?php echo ( $engine->db->footer_comments == 2 ? ' checked="checked"' : '' );?> /><label for="footer_comments_guest">Registered.</label>
					<input type="radio" id="footer_comments_off" name="footer_comments" value="0"<?php echo ( $engine->db->footer_comments == 0 ? ' checked="checked"' : '' );?> /><label for="footer_comments_off">Off.</label>
				</td>
			</tr>
			<tr class="lined">
				<td colspan="2"></td>
			</tr>
			<tr class="hl_setting">
				<td class="label"><strong>File panel:</strong><br />
					<small>The default display of attachments in the bottom of the page .</small></td>
				<td>
					<input type="radio" id="footer_files_on" name="footer_files" value="1"<?php echo ( $engine->db->footer_files == 1 ? ' checked="checked"' : '' );?> /><label for="footer_files_on">On.</label>
					<input type="radio" id="footer_files_guest" name="footer_files" value="2"<?php echo ( $engine->db->footer_files == 2 ? ' checked="checked"' : '' );?> /><label for="footer_files_guest">Registered.</label>
					<input type="radio" id="footer_files_off" name="footer_files" value="0"<?php echo ( $engine->db->footer_files == 0 ? ' checked="checked"' : '' );?> /><label for="footer_files_off">Off.</label>
				</td>
			</tr>
			<tr class="lined">
				<td colspan="2"></td>
			</tr>
			<tr class="hl_setting">
				<td class="label"><strong>Rating panel :</strong><br />
					<small>The default display of the rating panel in the bottom of the page.</small></td>
				<td>
					<input type="radio" id="footer_rating_on" name="footer_rating" value="1"<?php echo ( $engine->db->footer_rating == 1 ? ' checked="checked"' : '' );?> /><label for="footer_rating_on">On.</label>
					<input type="radio" id="footer_rating_guest" name="footer_rating" value="2"<?php echo ( $engine->db->footer_rating == 2 ? ' checked="checked"' : '' );?> /><label for="footer_rating_guest">Registered.</label>
					<input type="radio" id="footer_rating_off" name="footer_rating" value="0"<?php echo ( $engine->db->footer_rating == 0 ? ' checked="checked"' : '' );?> /><label for="footer_rating_off">Off.</label>
				</td>
			</tr>
			<tr class="lined">
				<td colspan="2"></td>
			</tr>
			<tr class="hl_setting">
				<td class="label"><strong>Tags panel :</strong><br />
					<small>The default display of the tags panel in the bottom of the page.</small></td>
				<td>
					<input type="radio" id="footer_tags_on" name="footer_tags" value="1"<?php echo ( $engine->db->footer_tags == 1 ? ' checked="checked"' : '' );?> /><label for="footer_tags_on">On.</label>
					<input type="radio" id="footer_tags_guest" name="footer_tags" value="2"<?php echo ( $engine->db->footer_tags == 2 ? ' checked="checked"' : '' );?> /><label for="footer_tags_guest">Registered.</label>
					<input type="radio" id="footer_tags_off" name="footer_tags" value="0"<?php echo ( $engine->db->footer_tags == 0 ? ' checked="checked"' : '' );?> /><label for="footer_tags_off">Off.</label>
				</td>
			</tr>
			<tr class="lined">
				<td colspan="2"></td>
			</tr>
			<tr class="hl_setting">
				<td class="label"><strong>Hide Revisions:</strong><br />
					<small>The default display of revisions of the page.</small></td>
				<td>
					<input type="radio" id="hide_revisions_on" name="hide_revisions" value="2"<?php echo ( $engine->db->hide_revisions == 2 ? ' checked="checked"' : '' );?> /><label for="hide_revisions_on">On.</label>
					<input type="radio" id="hide_revisions_guest" name="hide_revisions" value="1"<?php echo ( $engine->db->hide_revisions == 1 ? ' checked="checked"' : '' );?> /><label for="hide_revisions_guest">Registered.</label>
					<input type="radio" id="hide_revisions_off" name="hide_revisions" value="0"<?php echo ( $engine->db->hide_revisions == 0 ? ' checked="checked"' : '' );?> /><label for="hide_revisions_off">Off.</label>
				</td>
			</tr>
			<tr class="lined">
				<td colspan="2"></td>
			</tr>
			<tr class="hl_setting">
				<td class="label"><strong>Table of contents panel :</strong><br />
					<small>The default display table of contents panel of a page (may need support in the templates).</small></td>
				<td>
					<input type="radio" id="hide_toc_on" name="hide_toc" value="0"<?php echo ( !$engine->db->hide_toc ? ' checked="checked"' : '' );?> /><label for="hide_toc_on">On.</label>
					<input type="radio" id="hide_toc_off" name="hide_toc" value="1"<?php echo ( $engine->db->hide_toc ? ' checked="checked"' : '' );?> /><label for="hide_toc_off">Off.</label>
				</td>
			</tr>
			<tr class="lined">
				<td colspan="2"></td>
			</tr>
			<tr class="hl_setting">
				<td class="label"><strong>Sections panel :</strong><br />
					<small>By default display the panel of adjacent pages (requires support in the templates).</small></td>
				<td>
					<input type="radio" id="hide_index_on" name="hide_index" value="0"<?php echo ( !$engine->db->hide_index ? ' checked="checked"' : '' );?> /><label for="hide_index_on">On.</label>
					<input type="radio" id="hide_index_off" name="hide_index" value="1"<?php echo ( $engine->db->hide_index ? ' checked="checked"' : '' );?> /><label for="hide_index_off">Off.</label>
				</td>
			</tr>
			<tr class="lined">
				<td colspan="2"></td>
			</tr>
			<tr class="hl_setting">
				<td class="label"><strong>Displaying sections:</strong><br />
					<small>When the previous options, whether to display only subpages of page (<em>lower</em>), only neighbor (<em>top</em>) or both, and other (<em>tree</em>).</small></td>
				<td>
					<input type="radio" id="full_index" name="tree_level" value="0"<?php echo ( $engine->db->tree_level == 0 ? ' checked="checked"' : '' );?> /><label for="full_index">Tree</label>
					<input type="radio" id="lower_index" name="tree_level" value="1"<?php echo ( $engine->db->tree_level == 1 ? ' checked="checked"' : '' );?> /><label for="lower_index">Lower</label>
					<input type="radio" id="upper_index" name="tree_level" value="2"<?php echo ( $engine->db->tree_level == 2 ? ' checked="checked"' : '' );?> /><label for="upper_index">Upper</label>
				</td>
			</tr>
			<tr class="lined">
				<td colspan="2"></td>
			</tr>
			<tr class="hl_setting">
				<td class="label"><label for="menu_items"><strong>Menu items:</strong><br />
					<small>Default number of shown menu items (may need support in the templates).</small></label></td>
				<td><input type="number" min="0" max="20" maxlength="4" style="width:200px;" id="menu_items" name="menu_items" value="<?php echo htmlspecialchars($engine->db->menu_items, ENT_COMPAT | ENT_HTML401, HTML_ENTITIES_CHARSET);?>" /></td>
			</tr>
			<tr>
				<th colspan="2">
					<br />
					Feeds
				</th>
			</tr>
			<tr class="hl_setting">
				<td class="label"><label for="enable_feeds"><strong>Enable Feeds:</strong><br />
					<small>Turns on or off RSS feeds for the entire wiki.</small></label></td>
				<td><input type="checkbox" id="enable_feeds" name="enable_feeds" value="1"<?php echo ( $engine->db->enable_feeds ? ' checked="checked"' : '' );?> /></td>
			</tr>
			<tr>
				<th colspan="2">
					<br />
					Miscellaneous
				</th>
			</tr>
			<tr class="hl_setting">
				<td class="label"><strong>Edit summary:</strong><br />
					<small>Shows change summary in the edit mode.</small></td>
				<td>
					<input type="radio" id="edit_summary_on" name="edit_summary" value="1"<?php echo ( $engine->db->edit_summary == 1 ? ' checked="checked"' : '' );?> /><label for="edit_summary_on">On.</label>
					<input type="radio" id="edit_summary_mandatory" name="edit_summary" value="2"<?php echo ( $engine->db->edit_summary == 2 ? ' checked="checked"' : '' );?> /><label for="edit_summary_mandatory">Mandatory.</label>
					<input type="radio" id="edit_summary_off" name="edit_summary" value="0"<?php echo ( !$engine->db->edit_summary ? ' checked="checked"' : '' );?> /><label for="edit_summary_off">Off.</label>
				</td>
			</tr>
			<tr class="lined">
				<td colspan="2"></td>
			</tr>
			<tr class="hl_setting">
				<td class="label"><strong>Minor edit:</strong><br />
					<small>Enables minor edit option in the edit mode.</small></td>
				<td>
					<input type="radio" id="minor_edit_on" name="minor_edit" value="1"<?php echo ( $engine->db->minor_edit ? ' checked="checked"' : '' );?> /><label for="minor_edit_on">On.</label>
					<input type="radio" id="minor_edit_off" name="minor_edit" value="0"<?php echo ( !$engine->db->minor_edit ? ' checked="checked"' : '' );?> /><label for="minor_edit_off">Off.</label>
				</td>
			</tr>
			<tr class="lined">
				<td colspan="2"></td>
			</tr>
			<tr class="hl_setting">
				<td class="label"><strong>Review:</strong><br />
					<small>Enables review option in the edit mode.</small></td>
				<td>
					<input type="radio" id="review_on" name="review" value="1"<?php echo ( $engine->db->review ? ' checked="checked"' : '' );?> /><label for="review_on">On.</label>
					<input type="radio" id="review_off" name="review" value="0"<?php echo ( !$engine->db->review ? ' checked="checked"' : '' );?> /><label for="review_off">Off.</label>
				</td>
			</tr>
			<tr class="lined">
				<td colspan="2"></td>
			</tr>
			<tr class="hl_setting">
				<td class="label"><strong>Autosubscribe:</strong><br />
					<small>Automatically sign a new page in the owner's notice of its changes.</small></td>
				<td>
					<input type="radio" id="disable_autosubscribe_on" name="disable_autosubscribe" value="0"<?php echo ( !$engine->db->disable_autosubscribe ? ' checked="checked"' : '' );?> /><label for="disable_autosubscribe_on">On.</label>
					<input type="radio" id="disable_autosubscribe_off" name="disable_autosubscribe" value="1"<?php echo ( $engine->db->disable_autosubscribe ? ' checked="checked"' : '' );?> /><label for="disable_autosubscribe_off">Off.</label>
				</td>
			</tr>
			<tr class="lined">
				<td colspan="2"></td>
			</tr>
			<tr class="hl_setting">
				<td class="label"><strong>Allow anonymous publishing:</strong><br />
					<small>Allow users to published preferably anonymously (to hide the name).</small></td>
				<td>
					<input type="radio" id="publish_anonymously_on" name="publish_anonymously" value="1"<?php echo ( $engine->db->publish_anonymously ? ' checked="checked"' : '' );?> /><label for="publish_anonymously_on">On.</label>
					<input type="radio" id="publish_anonymously_off" name="publish_anonymously" value="0"<?php echo ( !$engine->db->publish_anonymously ? ' checked="checked"' : '' );?> /><label for="publish_anonymously_off">Off.</label>
				</td>
			</tr>
			<tr class="lined">
				<td colspan="2"></td>
			</tr>
			<tr class="hl_setting">
				<td class="label"><label for="default_rename_redirect"><strong>When renaming put redirection:</strong><br />
					<small>By default, propose to redirect the old address pereimenuemoy page.</small></label></td>
				<td><input type="checkbox" id="default_rename_redirect" name="default_rename_redirect" value="1"<?php echo ( $engine->db->default_rename_redirect ? ' checked="checked"' : '' );?> /></td>
			</tr>
			<tr class="lined">
				<td colspan="2"></td>
			</tr>
			<tr class="hl_setting">
				<td class="label"><label for="store_deleted_pages"><strong>Keep deleted pages:</strong><br />
					<small>When you delete a page (the comment) put her in a special section where she had some time (below) will be available for viewing and recovery.</small></label></td>
				<td><input type="checkbox" id="store_deleted_pages" name="store_deleted_pages" value="1"<?php echo ( $engine->db->store_deleted_pages ? ' checked="checked"' : '' );?> /></td>
			</tr>
			<tr class="lined">
				<td colspan="2"></td>
			</tr>
			<tr class="hl_setting">
				<td class="label"><label for="keep_deleted_time"><strong>Storage time of deleted pages:</strong><br />
					<small>The period in days. It makes sense only if the previous option. Zero indicates the eternal possession (in this case the administrator can clear the "cart" manually).</small></label></td>
				<td><input type="number" min="0" maxlength="4" style="width:200px;" id="keep_deleted_time" name="keep_deleted_time" value="<?php echo htmlspecialchars($engine->db->keep_deleted_time, ENT_COMPAT | ENT_HTML401, HTML_ENTITIES_CHARSET);?>" /></td>
			</tr>
			<tr class="lined">
				<td colspan="2"></td>
			</tr>
			<tr class="hl_setting">
				<td class="label"><label for="pages_purge_time"><strong>Storage time of page revisions:</strong><br />
					<small>Automatically delete the older edition of the number of days. If you enter zero, the old edition will not be removed.</small></label></td>
				<td><input type="number" min="0" maxlength="4" style="width:200px;" id="pages_purge_time" name="pages_purge_time" value="<?php echo htmlspecialchars($engine->db->pages_purge_time, ENT_COMPAT | ENT_HTML401, HTML_ENTITIES_CHARSET);?>" /></td>
			</tr>
			<tr class="lined">
				<td colspan="2"></td>
			</tr>
			<tr class="hl_setting">
				<td class="label"><strong>Enable Referrers:</strong><br />
					<small>Allows to store and show external referrers.</small></td>
				<td>
					<input type="radio" id="enable_referrer_on" name="enable_referrers" value="1"<?php echo ( $engine->db->enable_referrers == 1 ? ' checked="checked"' : '' );?> /><label for="enable_referrer_on">On.</label>
					<input type="radio" id="enable_referrer_admin" name="enable_referrers" value="2"<?php echo ( $engine->db->enable_referrers == 2 ? ' checked="checked"' : '' );?> /><label for="enable_referrer_admin">Admin.</label>
					<input type="radio" id="enable_referrer_off" name="enable_referrers" value="0"<?php echo ( $engine->db->enable_referrers == 0? ' checked="checked"' : '' );?> /><label for="enable_referrer_off">Off.</label>
				</td>
			</tr>
			<tr class="lined">
				<td colspan="2"></td>
			</tr>
			<tr class="hl_setting">
				<td class="label"><label for="referrers_purge_time"><strong>Storage time of referrers:</strong><br />
					<small>Keep history of invoking external pages no more than this number of days. Zero means the perpetual possession, but to actively visit the site this could lead to overcrowding in the database.</small></label></td>
				<td><input type="number" min="0" maxlength="4" style="width:200px;" id="referrers_purge_time" name="referrers_purge_time" value="<?php echo htmlspecialchars($engine->db->referrers_purge_time, ENT_COMPAT | ENT_HTML401, HTML_ENTITIES_CHARSET);?>" /></td>
			</tr>
			<tr class="lined">
				<td colspan="2"></td>
			</tr>
			<tr class="hl_setting">
				<td class="label"><label for="noindex"><strong>Block search engines:</strong><br />
					<small>I would like to block search engines, but allow normal visitors. Overrides page settings.</small></label></td>
				<td><input type="checkbox" id="noindex" name="noindex" value="1"<?php echo ( $engine->db->noindex ? ' checked="checked"' : '' );?> /></td>
			</tr>
			<tr class="lined">
				<td colspan="2"></td>
			</tr>
			<tr class="hl_setting">
				<td class="label"><label for="xml_sitemap"><strong>XML Sitemap:</strong><br />
					<small>Create an XML file called "sitemap-wackowiki.xml" inside the xml folder. Generate a Sitemaps XML format compatible XML file. You might want to change the path to output it in your root folder as that is one of the requirements i.e. that the XML file is in the root folder.</small></label></td>
				<td><input type="checkbox" id="xml_sitemap" name="xml_sitemap" value="1"<?php echo ( $engine->db->xml_sitemap ? ' checked="checked"' : '' );?> /></td>
			</tr>
			<tr class="lined">
				<td colspan="2"></td>
			</tr>
			<tr class="hl_setting">
				<td class="label"><label for="xml_sitemap_time"><strong>XML Sitemap generation time:</strong><br />
					<small>Generate a Sitemaps only once in this number of days, zero means on every page change.</small></label></td>
				<td><input type="number" min="0" maxlength="4" style="width:200px;" id="xml_sitemap_time" name="xml_sitemap_time" value="<?php echo htmlspecialchars($engine->db->xml_sitemap_time, ENT_COMPAT | ENT_HTML401, HTML_ENTITIES_CHARSET);?>" /></td>
			</tr>
		</table>
		<br />
		<div class="center">
			<input type="submit" id="submit" value="save" />
			<input type="reset" id="button" value="reset" />
		</div>
<?php
	echo $engine->form_close();
}

?>