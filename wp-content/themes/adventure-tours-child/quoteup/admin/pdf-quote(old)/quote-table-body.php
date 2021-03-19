<tr>
    <?php
    $classPrefix = '';
    if ($show_price == 1) :
        $classPrefix = '-price';
    endif;
    ?>
    <td class="product<?php echo $classPrefix; ?>" align="left">
        <?php
            echo get_the_title($quoteProduct['product_id']);
        if ($_product->is_type('variable')) :
            quoteupRemoveClassFilter('woocommerce_attribute_label', 'WCML_WC_Strings', 'translated_attribute_label');
            global $sitepress;
            remove_filter('get_term', array($sitepress, 'get_term_adjust_id'), 1);
            echo printVariations($quoteProduct);
        endif;

        if( !empty( $args['enquiry_id'] ) ) {
        	global $wpdb;
        	$enq_id	= $args['enquiry_id'];
		    $sql_enq = "SELECT * FROM {$wpdb->prefix}enquiry_detail_new WHERE enquiry_id ='$enq_id'";
		    $sql_enqmeta = "SELECT meta_key,meta_value FROM {$wpdb->prefix}enquiry_meta WHERE enquiry_id='$enq_id'";

		    $results_enq = $wpdb->get_results($sql_enq);
		    $results_enqmeta = $wpdb->get_results($sql_enqmeta);
		    $duration_value	= '';
		    if( !empty( $results_enqmeta ) ) {
		    	foreach ( $results_enqmeta as $enqmeta ) {
		    		if( utf8_decode($enqmeta->meta_key) == 'Durée' ) {
		    			$duration_value = $enqmeta->meta_value;
		    			break;
		    		}
		    	}
		    }

		    if( !empty( $results_enq[0]->date_field ) && !empty( $duration_value ) ) {
    			echo '<br/>'. sprintf( __('Check out %s for %s days', 'woocommerce'), date( 'M j, Y', strtotime($results_enq[0]->date_field) ), $duration_value );
		    }
        }
        ?>
    </td> <!-- td.product ends here -->
    <td class="sku<?php echo $classPrefix ?>" align="left">
        <?php
        if ($_product->is_type('variable')) :
            $_product_variation = wc_get_product($quoteProduct['variation_id']);
            echo $_product_variation->get_sku();
        else :
                    echo $_product->get_sku();
        endif;
        ?>
    </td> <!-- td.sku ends here -->
    <?php if ($show_price == 1) : ?>
        <td align="left" class="old_price">
            <?php echo wc_price($price); ?>
        </td> <!-- td.old_price ends here -->
    <?php endif; ?>
    <td align="left" class="new_price">
        <?php echo wc_price($quoteProduct['newprice']); ?>
    </td> <!-- td.new_price ends here -->
    <td align="center" class="quantitiy">
        <?php echo $quoteProduct['quantity']; ?>
    </td> <!-- td.new_price ends here -->
    <td align="right" class="total">
        <?php echo wc_price($quoteProduct['newprice'] * $quoteProduct['quantity']);
        ?>
    </td> <!-- td.total ends here -->
</tr>