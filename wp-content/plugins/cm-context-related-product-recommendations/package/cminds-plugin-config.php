<?php

$cminds_plugin_config = array(
	'plugin-is-pro'					 => false,
	'plugin-version'				 => '1.0.14',
	'plugin-abbrev'					 => 'cmcrpr',
	'plugin-short-slug'				 => 'product-recommendations',
	'plugin-parent-short-slug'		 => '',
	'plugin-affiliate'				 => '',
	'plugin-redirect-after-install'	 => admin_url( 'admin.php?page=cm_product_recommend&page=cmcrpr_settings' ),
	'plugin-show-guide'				 => TRUE,
	'plugin-guide-text'				 => '    <div style="display:block">
        <ol>
            <li>Go to <strong>"Add new Product"</strong>.</li>
            <li>Product Title should be the <strong>term you would like to search</strong> in the content of your post or pages when displaying the Product Recommendation .</li>
            <li><strong>URL</strong> allows you choose where the Product Recommendation points to.</li>
            <li>Last thing you can set is the <strong>Featured Image</strong> which will be displayed in the Product Recommendation box.</li>
            <li>Save the new product you have added</li>
            <li>Now the Product Recommendation will be displayed on the bottom of posts/pages when the product "Title" is found in the content of the page.</li>
            <li>In the plugin settings  you can modify the behavior of the plugin</li>
            <li>If you have problems try disabling the option "Enable Caching Mechanisms"</li>
          </ol>
    </div>',
	'plugin-guide-video-height'		 => 240,
	'plugin-guide-videos'			 => array(
		array( 'title' => 'Installation tutorial', 'video_id' => '159221614' ),
	),

         'plugin-upgrade-text'           => 'Good Reasons to Upgrade to Pro',
    'plugin-upgrade-text-list'      => array(
        array( 'title' => 'Introduction to Product recommendations ', 'video_time' => '0:00' ),
        array( 'title' => 'Multiple product for each term', 'video_time' => 'More' ),
        array( 'title' => 'Synonyms Support ', 'video_time' => 'More' ),
        array( 'title' => 'Categories Support', 'video_time' => 'More' ),
        array( 'title' => 'Product Weight', 'video_time' => 'More' ),
        array( 'title' => 'Support Custom Post Types', 'video_time' => 'More' ),
        array( 'title' => 'Reports and Statistics', 'video_time' => 'More' ),
        array( 'title' => 'Configure Product Title and Description', 'video_time' => 'More' ),
        array( 'title' => 'Import and Export', 'video_time' => 'More' ),
    ),
    'plugin-upgrade-video-height'   => 240,
    'plugin-upgrade-videos'         => array(
        array( 'title' => 'Product recommendations Premium Features', 'video_id' => '141020931' ),
    ),

	'plugin-file'					 => CMCRPR_PLUGIN_FILE,
	'plugin-dir-path'				 => plugin_dir_path( CMCRPR_PLUGIN_FILE ),
	'plugin-dir-url'				 => plugin_dir_url( CMCRPR_PLUGIN_FILE ),
	'plugin-basename'				 => plugin_basename( CMCRPR_PLUGIN_FILE ),
	'plugin-icon'					 => '',
	'plugin-name'					 => CMCRPR_NAME,
	'plugin-license-name'			 => CMCRPR_CANONICAL_NAME,
	'plugin-slug'					 => '',
	'plugin-menu-item'				 => 'edit.php?post_type=' . CMCRPR_Base::POST_TYPE,
	'plugin-textdomain'				 => CMCRPR_SLUG_NAME,
	'plugin-userguide-key'			 => '446-cm-contextual-product-recommendations',
	'plugin-store-url'				 => 'https://www.cminds.com/wordpress-plugins-library/cm-product-recommendations-for-wordpress/',
	'plugin-support-url'			 => 'https://wordpress.org/support/plugin/cm-context-related-product-recommendations',
	'plugin-review-url'				 => 'https://wordpress.org/support/view/plugin-reviews/cm-context-related-product-recommendations',
	'plugin-changelog-url'			 => CMCRPR_RELEASE_NOTES,
	'plugin-licensing-aliases'		 => array( CMCRPR_LICENSE_NAME ),
	'plugin-compare-table'			 => '
            <div class="pricing-table" id="pricing-table"><h2 style="padding-left:10px;">Upgrade The Product Recommendations Plugin:</h2>
                <ul>
                    <li class="heading" style="background-color:red;">Current Edition</li>
                    <li class="price">FREE<br /></li>
                   <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Add related products to post</li>
                   <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Adjust related product widget style</li>
                   <hr>
                    Other CreativeMinds Offerings
                    <hr>
                 <a href="https://www.cminds.com/wordpress-plugins-library/seo-keyword-hound-wordpress/" target="blank"><img src="' . plugin_dir_url( __FILE__ ). 'views/Hound2.png"  width="220"></a><br><br><br>
                <a href="https://www.cminds.com/store/cm-wordpress-plugins-yearly-membership/" target="blank"><img src="' . plugin_dir_url( __FILE__ ). 'views/banner_yearly-membership_220px.png"  width="220"></a><br>
                 </ul>

                <ul>
                    <li class="heading">Pro<a href="https://www.cminds.com/wordpress-plugins-library/cm-product-recommendations-for-wordpress/" style="float:right;font-size:11px;color:white;" target="_blank">More</a></li>
                    <li class="price">$29.00<br /> <span style="font-size:14px;">(For one Year / Site)<br />Additional pricing options available <a href="https://www.cminds.com/wordpress-plugins-library/cm-product-recommendations-for-wordpress/" target="_blank"> >>> </a></span> <br /></li>
                    <li class="action"><a href="https://www.cminds.com/?edd_action=add_to_cart&download_id=60302&wp_referrer=https://www.cminds.com/checkout/&edd_options[price_id]=1" style="font-size:18px;" target="_blank">Upgrade Now</a></li>
                     <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>All Free Version Features <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="All free features are supported in the pro"></span></li>
<li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Multiple product per each term<span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Support multiple products per each term."></span></li>
<li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Synonyms Support<span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Use keywords and terms to promote a product. Each product can have a primary term and all related synonyms."></span></li>
<li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Control Widget Appearance<span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Turn product recommendations widget on and off within specific posts"></span></li>
<li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Categories<span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Filter recommendations based on a specific products category"></span></li>
<li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Product Weight<span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Use weight to show specific products more often than others"></span></li>
<li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Widget Design<span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Easily customize widget look and feel"></span></li>
<li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Custom Post Types<span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Support custom posts types"></span></li>
<li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Term Links<span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Add a link to the product page or to an external resource from any term associated with the product. This is a great way to generate affiliate income."></span></li>
<li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Widget Location<span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Locate product recommendations widget anywhere in the post"></span></li>
<li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Reports and Statistics<span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Statistics and reports for each product performance (views / clicks)"></span></li>
<li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Product Title and Description<span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Add description for each product. This will appear below product image in the related products widget."></span></li>
<li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Export and Import<span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Export and import products between you WordPress sites."></span></li>
                 <li class="support" style="background-color:lightgreen; text-align:left; font-size:14px;"><span class="dashicons dashicons-yes"></span> One year of expert support <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:grey" title="You receive 365 days of WordPress expert support. We will answer questions you have and also support any issue related to the plugin. We will also provide on-site support."></span><br />
                         <span class="dashicons dashicons-yes"></span> Unlimited product updates <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:grey" title="During the license period, you can update the plugin as many times as needed and receive any version release and security update"></span><br />
                        <span class="dashicons dashicons-yes"></span> Plugin can be used forever <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:grey" title="Once license expires, If you choose not to renew the plugin license, you can still continue to use it as long as you want."></span><br />
                        <span class="dashicons dashicons-yes"></span> Save 40% once renewing license <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:grey" title="Once license expires, If you choose to renew the plugin license you can do this anytime you choose. The renewal cost will be 35% off the product cost."></span></li>
                 </ul>




            </div>',
);
