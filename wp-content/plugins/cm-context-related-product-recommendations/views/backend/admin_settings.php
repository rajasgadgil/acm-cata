<?php if ( !empty( $messages ) ): ?>
	<div class="updated" style="clear:both"><p><?php echo $messages; ?></p></div>
<?php endif; ?>

<br/>

<br/>

<?php
echo do_shortcode('[cminds_free_activation id="cmcrpr"]');
?>

<div style="height:15px;"></div>

<div class="cminds_settings_description">
      <form method="post">
        <div>
            <div class="cmcrpr_field_help_container">Warning! This option will completely erase all of the data stored by the <?php echo CMCRPR_SHORTNAME ?> in the database: items, options, synonyms etc. <br/> It cannot be reverted.</div>
            <input onclick="return confirm( 'All database items of <?php echo CMCRPR_SHORTNAME ?> (items, options etc.) will be erased. This cannot be reverted.' )" type="submit" name="cmcrpr_options[cmcrpr_pluginCleanup]" value="Cleanup database" class="button cmf-cleanup-button"/>
            <span style="display: inline-block;position: relative;"></span>
        </div>
    </form>

	<?php
// check permalink settings
	if ( get_option( 'permalink_structure' ) == '' ) {
		echo '<span style="color:red">Your WordPress Permalinks needs to be set to allow plugin to work correctly. Please Go to <a href="' . admin_url() . 'options-permalink.php" target="new">Settings->Permalinks</a> to set Permalinks to Post Name.</span><br><br>';
	}
	?>
</div>

<br/>

<div class="clear"></div>

<br/>

<form method="post">
	<?php wp_nonce_field( 'update-options' ); ?>
    <input type="hidden" name="action" value="update" />


    <div id="cmcrpr_tabs" class="itemSettingsTabs">
        <div class="item_loading"></div>

		<?php
		CMCRPR_Base::renderSettingsTabsControls();
		CMCRPR_Base::renderSettingsTabs();
		?>

       <div id="tabs-0">
            <div class="block">
                 <table width="100%"><tbody>
                        <tr>
                            <td> <?php echo do_shortcode( '[cminds_upgrade_box id="cmcrpr"]' ); ?></td>
                        </tr>
                    </tbody></table>
            </div>
       </div>     

        <div id="tabs-1">
            <div class="block">
                <h3>General Settings</h3>
                <table class="floated-form-table form-table">
                    <tr valign="top">
                        <th scope="row">Display items on given post types:</th>
                        <td>
							<?php
							echo CMCRPR_Base::outputCustomPostTypesList();
							?>
                        </td>
                        <td colspan="2" class="cmcrpr_field_help_container">Select the custom post types where you'd like the <?php echo CMCRPR_SHORTNAME ?> Items to be highlighted.</td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Only display items on single posts/pages (not Homepage, authors etc.)?</th>
                        <td>
                            <input type="hidden" name="cmcrpr_options[showOnlyOnSingle]" value="0" />
                            <input type="checkbox" name="cmcrpr_options[showOnlyOnSingle]" <?php checked( true, CMCRPR_Base::_getOptions( 'showOnlyOnSingle' ) ); ?> value="1" />
                        </td>
                        <td colspan="2" class="cmcrpr_field_help_container">Select this option if you wish to only display items when viewing a single page/post.
                            This can be used so items aren't highlighted on your homepage, or author pages and other taxonomy related pages.</td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">First occurance only?</th>
                        <td>
                            <input type="hidden" name="cmcrpr_options[firstOccuranceOnly]" value="0" />
                            <input type="checkbox" name="cmcrpr_options[firstOccuranceOnly]" <?php checked( true, CMCRPR_Base::_getOptions( 'firstOccuranceOnly' ) ); ?> value="1" />
                        </td>
                        <td colspan="2" class="cmcrpr_field_help_container">Select this option if you want to only take into account the first occurance of each item on a page/post.</td>
                    </tr>
					<tr valign="top">
                        <th scope="row">Avoid parsing protected tags?</th>
                        <td>
                            <input type="hidden" name="cmcrpr_options[notParseProtectedTags]" value="0" />
                            <input type="checkbox" name="cmcrpr_options[notParseProtectedTags]" <?php checked( true, CMCRPR_Base::_getOptions( 'notParseProtectedTags' ) ); ?> value="1" />
                        </td>
                        <td colspan="2" class="cmcrpr_field_help_container">Select this option if you want to avoid searching within the content of following tags: Script, A, H1, H2, H3, PRE, Object.</td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Terms case-sensitive?</th>
                        <td>
                            <input type="hidden" name="cmcrpr_options[termsCaseSensitive]" value="0" />
                            <input type="checkbox" name="cmcrpr_options[termsCaseSensitive]" <?php checked( '1', CMCRPR_Base::_getOptions( 'termsCaseSensitive' ) ); ?> value="1" />
                        </td>
                        <td colspan="2" class="cmcrpr_field_help_container">Select this option if you want the parsing to be case-sensitive.</td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Add links to found items?</th>
                        <td>
                            <input type="hidden" name="cmcrpr_options[termsWithLinks]" value="0" />
                            <input type="checkbox" name="cmcrpr_options[termsWithLinks]" <?php checked( '1', CMCRPR_Base::_getOptions( 'termsWithLinks' ) ); ?> value="1" />
                        </td>
                        <td colspan="2" class="cmcrpr_field_help_container">Select this option if you want the items to have links.</td>
                    </tr>
                </table>
                <div class="clear"></div>
            </div>
            <div class="block">
                <h3>Performance &amp; Debug</h3>
                <table class="floated-form-table form-table">
                    <tr valign="top">
                        <th scope="row">Only highlight on "main" WP query?</th>
                        <td>
                            <input type="hidden" name="cmcrpr_options[showOnMainQuery]" value="0" />
                            <input type="checkbox" name="cmcrpr_options[showOnMainQuery]" <?php checked( 1, CMCRPR_Base::_getOptions( 'showOnMainQuery' ) ); ?> value="1" />
                        </td>
                        <td colspan="2" class="cmcrpr_field_help_container">
                            <strong>Warning: Don't change this setting unless you know what you're doing</strong><br/>
                            Select this option if you wish to only highlight items on main query.
                            Unchecking this box may fix problems with highlighting items on some themes which manipulate the WP_Query.</td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Enable the caching mechanisms</th>
                        <td>
                            <input type="hidden" name="cmcrpr_options[enableCaching]" value="0" />
                            <input type="checkbox" name="cmcrpr_options[enableCaching]" <?php checked( true, CMCRPR_Base::_getOptions( 'enableCaching', TRUE ) ); ?> value="1" />
                        </td>
                        <td colspan="2" class="cmcrpr_field_help_container">Select this option if you want to use the internal caching mechanisms.</td>
                    </tr>
                </table>
                <div class="clear"></div>
            </div>
        </div>
        <div id="tabs-2">
            <div class="block">
                <h3>Links</h3>
                <table class="floated-form-table form-table">
					<tr valign="top">
                        <th scope="row">Show HTML "title" attribute for the links?</th>
                        <td>
                            <input type="hidden" name="cmcrpr_options[showTitleAttribute]" value="0" />
                            <input type="checkbox" name="cmcrpr_options[showTitleAttribute]" <?php checked( true, CMCRPR_Base::_getOptions( 'showTitleAttribute' ) ); ?> value="1" />
                        </td>
                        <td colspan="2" class="cmcrpr_field_help_container">Select this option if you want to use item title as HTML "title" for link</td>
                    </tr>
					<tr valign="top">
                        <th scope="row">Open links in new tab?</th>
                        <td>
                            <input type="hidden" name="cmcrpr_options[openInNewTab]" value="0" />
                            <input type="checkbox" name="cmcrpr_options[openInNewTab]" <?php checked( true, CMCRPR_Base::_getOptions( 'openInNewTab' ) ); ?> value="1" />
                        </td>
                        <td colspan="2" class="cmcrpr_field_help_container">Select this option if you want to open the item links in a new tab.</td>
                    </tr>
                </table>
            </div>
            <div class="block">
                <h3>Title & Description</h3>
                <table class="floated-form-table form-table">
					<tr valign="top">
                        <th scope="row">Show Title?</th>
                        <td>
                            <input type="hidden" name="cmcrpr_options[showTitle]" value="0" />
                            <input type="checkbox" name="cmcrpr_options[showTitle]" <?php checked( true, CMCRPR_Base::_getOptions( 'showTitle' ) ); ?> value="1" />
                        </td>
                        <td colspan="2" class="cmcrpr_field_help_container">Select this option if you want to show the description of each item.</td>
                    </tr>
					<tr valign="top">
                        <th scope="row">Show Description?</th>
                        <td>
                            <input type="hidden" name="cmcrpr_options[showDescription]" value="0" />
                            <input type="checkbox" name="cmcrpr_options[showDescription]" <?php checked( true, CMCRPR_Base::_getOptions( 'showDescription' ) ); ?> value="1" />
                        </td>
                        <td colspan="2" class="cmcrpr_field_help_container">Select this option if you want to show the description of each item.</td>
                    </tr>
					<tr valign="top">
                        <th scope="row">Limit Description length?</th>
                        <td>
                            <input type="text" name="cmcrpr_options[limitDescriptionLength]" value="<?php echo CMCRPR_Base::_getOptions( 'limitDescriptionLength', 0 ); ?>" />
                        </td>
                        <td colspan="2" class="cmcrpr_field_help_container">Use this option if you'd like to limit the length of the description. 0 means no limit.</td>
                    </tr>
                </table>
            </div>
		</div>
		<?php
		$additionalTabContent	 = apply_filters( 'cmcrpr_settings_tab_content_after', '' );
		echo $additionalTabContent;
		?>
    
 <div id="tabs-88">
            <div class="block">
                 <table width="100%"><tbody>
                        <tr>
                            <td> <p>
        <strong>Supported Shortcodes:</strong> 
    </p>

    <ul style="list-style-type:disc;margin-left:20px;">
        <li><strong>Exclude from parsing</strong> - [cmcrpr_exclude] text [/<?php echo CMCRPR_Base::POST_TYPE; ?>_exclude]</li>
    </ul>
  </td>
                        </tr>
                    </tbody></table>
            </div>
       </div>     

    <div id="tabs-99">
            <div class="block">
                 <table width="100%"><tbody>
                        <tr>
                            <td> <?php echo do_shortcode( '[cminds_free_guide id="cmcrpr"]' ); ?></td>
                        </tr>
                    </tbody></table>
            </div>
       </div>     

	</div>
	<p class="submit" style="clear:left">
		<input type="submit" class="button-primary" value="<?php CMCRPR_Base::_e( 'Save Changes' ) ?>" name="cmcrpr_optionsSave" />
	</p>
</form>