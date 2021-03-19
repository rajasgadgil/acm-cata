<div class="wrap">
<?php 
$dplugin_name = 'WooCommerce Cart Suggestions';
$dplugin_link = 'http://berocket.com/product/woocommerce-cart-suggestions';
$dplugin_price = 20;
$dplugin_lic   = 21;
$dplugin_desc = '';
@ include 'settings_head.php';
@ include 'discount.php';
?>
<div class="wrap br_settings br_cart_suggestion_settings show_premium">
    <div id="icon-themes" class="icon32"></div>
    <h2>Cart Suggestions Settings</h2>
    <?php settings_errors(); ?>

    <h2 class="nav-tab-wrapper">
        <a href="#general" class="nav-tab nav-tab-active general-tab" data-block="general"><?php _e('General', 'BeRocket_cart_suggestion_domain') ?></a>
        <a href="#css" class="nav-tab css-tab" data-block="css"><?php _e('CSS', 'BeRocket_cart_suggestion_domain') ?></a>
    </h2>

    <form class="cart_suggestion_submit_form" method="post" action="options.php">
        <?php 
        $options = BeRocket_cart_suggestion::get_option(); ?>
        <div class="nav-block general-block nav-block-active">
            <table class="form-table license">
                <tr>
                    <th scope="row"><?php _e('Display position', 'BeRocket_cart_suggestion_domain') ?></th>
                    <td>
                        <div><label><input type="checkbox" name="br-cart_suggestion-options[display_before_cart_table]" value="1"<?php if($options['display_before_cart_table']) echo ' checked'; ?>><?php _e('Before cart table', 'BeRocket_cart_suggestion_domain') ?></label></div>
                        <div><label><input type="checkbox" name="br-cart_suggestion-options[display_after_cart_table]" value="1"<?php if($options['display_after_cart_table']) echo ' checked'; ?>><?php _e('After cart table', 'BeRocket_cart_suggestion_domain') ?></label></div>
                        <div><label><input type="checkbox" name="br-cart_suggestion-options[display_after_cart_total]" value="1"<?php if($options['display_after_cart_total']) echo ' checked'; ?>><?php _e('After cart total', 'BeRocket_cart_suggestion_domain') ?></label></div>
                        <?php
                        $hooks = array(
                            'woocommerce_before_cart' => __('Before cart table 2', 'BeRocket_cart_suggestion_domain'),
                            'before_the_content' => __('Before cart page content', 'BeRocket_cart_suggestion_domain'),
                            'after_the_content'  => __('After cart page content', 'BeRocket_cart_suggestion_domain'),
                        );
                        foreach($hooks as $hook => $hook_name) {
                            echo '<div><label><input type="checkbox" name="br-cart_suggestion-options[display_hooks][]" value="'.$hook.'"' . ( isset($options['display_hooks']) && is_array($options['display_hooks']) && in_array($hook, $options['display_hooks']) ? ' checked' : '') . '>' . $hook_name . '</label></div>';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Style', 'BeRocket_cart_suggestion_domain') ?></th>
                    <td>
                        <div class="brcs_widget_setting">
                            <select class="brcs_wid_type" name="br-cart_suggestion-options[widget_style]">
                            <?php
                                $types = array('default' => 'Default', 'image' => 'Image', 'image_title' => 'Image with Title', 'image_title_price' => 'Image with Title and Price', 'title' => 'Title', 'title_price' => 'Title with Price');
                                foreach( $types as $t_val => $t_name ) {
                                    echo '<option value="', $t_val, '"', ($t_val == $options['widget_style'] ? ' selected' : ''), '>', $t_name, '</option>';
                                }
                            ?>
                            </select>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Suggestions Title', 'BeRocket_cart_suggestion_domain') ?></th>
                    <td>
                        <input class="regular-text" type="text" name="br-cart_suggestion-options[suggestions_title]" value="<?php echo $options['suggestions_title']; ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Max Suggestions Count', 'BeRocket_cart_suggestion_domain') ?></th>
                    <td>
                        <input type="number" name="br-cart_suggestion-options[max_suggestions_count]" value="<?php echo $options['max_suggestions_count']; ?>">
                    </td>
                </tr>
                <?php
                if( defined('ICL_LANGUAGE_CODE') ) {
                    echo '<tr><th colspan=2><p class="notice notice-error">', __('Please use products and categories on default language', 'BeRocket_cart_suggestion_domain'), '</p></th></tr>';
                }
                ?>
                <tr>
                    <th scope="row"><?php _e('Default Suggestions', 'BeRocket_cart_suggestion_domain') ?></th>
                    <td>
                        <?php br_generate_product_selector( array( 'option' => $options['default_suggest'], 'block_name' => 'default_suggest', 'name' => 'br-cart_suggestion-options[default_suggest][]' ) ); ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Categories', 'BeRocket_cart_suggestion_domain') ?></th>
                    <td>
                        <div class="br_add_suggestion_to_specific br_add_suggestion_to_specific_category">
                            <table class="wp-list-table plugins">
                                <thead>
                                    <tr><th colspan="2">Category</th><td colspan="2">Products Suggestions</td></tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if( isset($options['category_suggest']) && is_array($options['category_suggest']) ) {
                                        foreach($options['category_suggest'] as $category) {
                                            $cat = get_term( $category['category'], 'product_cat' );
                                            if(isset($cat)) {
                                                $html = '<tr class="cat_exist_id cat_exist_'.$category['category'].'"><td class="move_suggestions"><i class="fa fa-th"></i></td><td><input class="cat_suggest_position" type="hidden" value="" name="br-cart_suggestion-options[category_suggest]['.$category['category'].'][position]"><input type="hidden" value="'.$category['category'].'" name="br-cart_suggestion-options[category_suggest]['.$category['category'].'][category]">'.$cat->name.'</td><td>';
                                                $html .= br_generate_product_selector( array( 'return' => true, 'option' => (isset($category['products']) ? $category['products'] : array()), 'block_name' => 'category_suggest', 'name' => 'br-cart_suggestion-options[category_suggest]['.$category['category'].'][products][]' ) );
                                                $html .= '</td><td class="cat_suggest_remove"><button type="button" class="cat_suggest_remove_button">Remove</button></td></tr>';
                                                echo $html;
                                            }
                                        }
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr><td colspan="4">Drag and drop to sort</td></tr>
                                </tfoot>
                            </table>
                            <?php
                            $product_categories = get_terms( 'product_cat' );
                            if( isset($product_categories) && is_array($product_categories) && count($product_categories) > 0 ) {
                            ?>
                            <select class="category_suggest">
                            <?php
                            $product_categories = get_terms( 'product_cat' );
                            foreach($product_categories as $category) {
                                echo '<option value="', $category->term_id, '">', $category->name, '</option>';
                            }
                            ?>
                            </select>
                            <span class="button add_category_suggest">Add Category</span>
                            <?php 
                            } else {
                                _e('There are no Categories. Please add one', 'BeRocket_cart_suggestion_domain');
                                echo ' <a href="'.admin_url( 'edit-tags.php?taxonomy=product_cat&post_type=product' ).'">'.__('here', 'BeRocket_cart_suggestion_domain').'</a>';
                            }
                            $category_product_search = br_generate_product_selector( array( 'return' => true, 'block_name' => 'category_suggest', 'name' => 'br-cart_suggestion-options[category_suggest][%cat_id%][products][]' ) ); ?>
                            <script>
                                var category_product_search = <?php echo json_encode($category_product_search); ?>;
                            </script>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Products', 'BeRocket_cart_suggestion_domain') ?></th>
                    <td>
                        <div class="br_add_suggestion_to_specific br_add_suggestion_to_specific_product">
                            <table class="wp-list-table plugins">
                                <thead>
                                    <tr><td colspan="2">Products</td><td colspan="2">Products Suggestions</td></tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if( isset($options['product_suggest']) && is_array($options['product_suggest']) ) {
                                        foreach($options['product_suggest'] as $category) {
                                            if( isset($category['product_ids']) && is_array($category['product_ids']) ) {
                                                $html = '<tr class="cat_exist_id"><td class="move_suggestions"><i class="fa fa-th"></i></td><td><input class="product_suggest_position" type="hidden" value="" data-name="br-cart_suggestion-options[product_suggest][%position%][position]" name="br-cart_suggestion-options[product_suggest][%position%][position]">';
                                                $html .= br_generate_product_selector( array( 'return' => true, 'option' => $category['product_ids'], 'block_name' => 'product_suggest', 'name' => 'br-cart_suggestion-options[product_suggest][%position%][product_ids][]' ) );
                                                $html .= '</td><td>';
                                                $html .= br_generate_product_selector( array( 'return' => true, 'option' => (isset($category['products']) ? $category['products'] : array()), 'block_name' => 'product_suggest_2', 'name' => 'br-cart_suggestion-options[product_suggest][%position%][products][]' ) );
                                                $html .= '</td><td class="cat_suggest_remove"><button type="button" class="cat_suggest_remove_button">Remove</button></td></tr>';
                                                echo $html;
                                            }
                                        }
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr><td colspan="4">Drag and drop to sort</td></tr>
                                </tfoot>
                            </table>
                            <script>
                                
                            var reload_sortable = function() {
                                jQuery('.br_add_suggestion_to_specific table tr td').css('width', '');
                                jQuery('.br_add_suggestion_to_specific table tr td').each(function( i, o ) {
                                    jQuery(o).css('width', jQuery(o).css('width'));
                                });
                            }
                            jQuery(function() {
                                jQuery( ".br_add_suggestion_to_specific table tbody" ).sortable({
                                    axis: "y",
                                    helper: "clone",
                                    opacity: 0.7,
                                    start: function( event, ui ) {
                                        ui.placeholder.css('height', ui.helper.height());
                                    },
                                    handle: '.move_suggestions'
                                });
                                jQuery(window).resize(function(event) {
                                    reload_sortable();
                                });
                                reload_sortable();
                            });
                            </script>
                            <span class="button add_product_suggest">Add Product</span>
                            <?php $product_product_search = br_generate_product_selector( array( 'return' => true, 'block_name' => 'product_suggest', 'name' => 'br-cart_suggestion-options[product_suggest][%position%][product_ids][]' ) );
                            $product_product_search_2 = br_generate_product_selector( array( 'return' => true, 'block_name' => 'product_suggest_2', 'name' => 'br-cart_suggestion-options[product_suggest][%position%][products][]' ) ); ?>
                            <script>
                                var product_product_search = <?php echo json_encode($product_product_search); ?>;
                                var product_product_search_2 = <?php echo json_encode($product_product_search_2); ?>;
                            </script>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="nav-block css-block">
            <table class="form-table license">
                <tr>
                    <th scope="row"><?php _e('Custom CSS', 'BeRocket_cart_suggestion_domain') ?></th>
                    <td>
                        <textarea name="br-cart_suggestion-options[custom_css]"><?php echo $options['custom_css']?></textarea>
                    </td>
                </tr>
            </table>
        </div>
        <input type="submit" class="button-primary" value="<?php _e('Save Changes', 'BeRocket_cart_suggestion_domain') ?>" />
    </form>
</div>
<?php
$feature_list = array(
    'Displays products after the cart totals and before the cart table',
    'Different types of shortcodes',
    'Slider type of widget and shortcode',
);
@ include 'settings_footer.php';
?>
</div>
