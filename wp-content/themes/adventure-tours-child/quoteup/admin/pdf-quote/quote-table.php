<table align="center" class="quote_table">
	<?php quoteupGetAdminTemplatePart('pdf-quote/quote-table-header', "", $args); ?>
    <?php
	    global $wpdb;
	    
	    $products    = unserialize($enquiry_details->product_details);
        $total_price = 0;
        
        $products_count	= count($quotation);
        
        $first_quote_prod	= array();
        $option_prod = array();
      	
      	if( $products_count > 1 ){
      		$enq_id	= $args['enquiry_id'];
      		$versionTbl = $wpdb->prefix.'enquiry_quotation_version';
      		$sql = $wpdb->get_row($wpdb->prepare("SELECT product_id FROM $versionTbl WHERE enquiry_id = %d AND variation_index_in_enquiry = %d ORDER BY version", $enq_id, 0), ARRAY_A);
      		$first_variation_pro_id = $sql['product_id'];
      		
      		foreach ( $quotation as $key => $quoteProduct ){
      			
      			if( $quoteProduct['product_id'] == $first_variation_pro_id ){
      				$first_quote_prod = $quoteProduct;
      				unset($quotation[$key]);
      			}
      			
      		}
      		$option_prod	= $quotation;
      	} else {
      		$first_quote_prod	= $quotation[0];
      		unset($quotation[0]);
      		$option_prod = $quotation;
      	}
		
	  // foreach ($first_quote_prod as $key => $quoteProduct) {
	    if( !empty($first_quote_prod) ){
	    	
	    	$_product    = wc_get_product($first_quote_prod['product_id']);
	        if (empty($_product)) {
	            continue;
	        }
	        $price       = $first_quote_prod['oldprice'];
	        $total_price = $total_price + $first_quote_prod['newprice'] * $first_quote_prod['quantity'];
	        $args['quoteProduct'] = $first_quote_prod;
	        $args['_product'] = $_product;
	        $args['price'] = $price;
	        quoteupGetAdminTemplatePart('pdf-quote/quote-table-body', "", $args);
	        
	        //Restrict only first record display
	        //if( $key >= 0 ) break;
	   // }
	    }

	if( !empty( $args['enquiry_id'] ) ) {
		
		$enq_id	= $args['enquiry_id'];
	    $sql_enq = "SELECT * FROM {$wpdb->prefix}enquiry_detail_new WHERE enquiry_id ='$enq_id'";
	    $sql_enqmeta = "SELECT meta_key,meta_value FROM {$wpdb->prefix}enquiry_meta WHERE enquiry_id='$enq_id'";
	
	    $results_enq = $wpdb->get_results($sql_enq);
	    $results_enqmeta = $wpdb->get_results($sql_enqmeta);
	    $duration_value	= '';
	    $nuits = '';
	    $text_boat_field = $embarquement = $debarquement = '';
	    
	    if( !empty( $results_enqmeta ) ) {
	    	foreach ( $results_enqmeta as $enqmeta ) {
	    		
	    		if( $enqmeta->meta_key == 'nuits' ) {
	    			$nuits = $enqmeta->meta_value;
	    		}
	    		
	    		if( $enqmeta->meta_key == 'text_boat_field' ) {
	    			$text_boat_field = $enqmeta->meta_value;
	    		}
	    		
	    		if( utf8_decode($enqmeta->meta_key) == 'Durée' ) {
	    			$duration_value = $enqmeta->meta_value;
	    			
	    		}
                if( $enqmeta->meta_key == 'embarquement' ) {
	    			$embarquement = $enqmeta->meta_value;
	    		}
                if( $enqmeta->meta_key == 'debarquement' ) {
	    			$debarquement = $enqmeta->meta_value;
	    		} 
                           
	    	}
	    }
            
		echo '<tr><td colspan="1"><p>'.$text_boat_field.'<br>
					 D&#xE9;part le '.date( 'd/m/Y', strtotime($results_enq[0]->enquiry_date) ).' - Retour le '.date('d/m/Y', strtotime($results_enq[0]->enquiry_date.' + '.$duration_value.' days')).'<br>
					 Embarquement* '.$embarquement.' - D&#xE9;barquement '.$debarquement.'<br>
					 Nombre de jours : '.$duration_value.'  Nombre de nuits : '.$nuits.'</p></td></tr>';
		
		/*if( !empty( $results_enq[0]->date_field ) && !empty( $duration_value ) ) {
			echo '<tr><td colspan="1">'. sprintf( __('Check out %s for %s days', 'woocommerce'), date( 'd/m/Y', strtotime($results_enq[0]->date_field) ), $duration_value ) .'</td><td></td></tr>';
	    }*/
	}

    //Get coupon codes
    $meta_tbl = $wpdb->prefix.'enquiry_meta';
	$coupon_mkey	= '_coupon_code_id';
	$discounted_coupons = $wpdb->get_col( $wpdb->prepare( "SELECT meta_value FROM $meta_tbl WHERE meta_key = '%s' AND enquiry_id = %d", $coupon_mkey, $args['enquiry_id'] ) );
	$discounted_coupons = !empty( $discounted_coupons[0] ) ? maybe_unserialize( $discounted_coupons[0] ) : '';
	if( !empty( $discounted_coupons ) ) {
		foreach ( $discounted_coupons as $key => $discounted_coupon ) {

			$_coupon	= new WC_Coupon( $discounted_coupon );
			$discount_type	= $_coupon->get_discount_type();
			$amount			= $_coupon->get_amount();
			if( $discount_type == 'percent' ) {
				$dicounted_amount = ($total_price * $amount ) / 100;		
				$total_price	= $total_price - $dicounted_amount;
			} else {
				$dicounted_amount = $amount;				
				$total_price	= $total_price - $dicounted_amount;
			}
			
			echo '<tr>';
			echo '<td align="left" colspan="3">';
			echo get_the_title( $discounted_coupon ). ' - ' .sprintf( '%s %s', $amount, $discount_type == 'percent' ? '%' : '' );
			echo '</td>';
			echo '<td align="right">- '. wc_price($dicounted_amount) .'</td>';
			echo '</tr>';
		}
	}?>
    <tr border="1" class="sub-total">
        <td align="right" colspan="3"> 
            <?php _e('Sous-total Location', 'quoteup'); ?> 
        </td>
        <td align="right" >
            <?php echo wc_price($total_price); ?>
        </td>
    </tr>
</table>
<?php if( !empty($option_prod) ) {  ?>
<table align="center" class="opt_quote_table" style="border-collapse: collapse;">
	<?php quoteupGetAdminTemplatePart('pdf-quote/quote-table-header', "", $args); ?>
    <?php
    $opt_total_price = 0;
    foreach ($option_prod as $key => $quoteProduct) {

        //Skip first record
    	//if( $key <= 0 ) continue;

        $_product    = wc_get_product($quoteProduct['product_id']);
        if (empty($_product)) {
            continue;
        }
        $price       = $quoteProduct['oldprice'];
        $opt_total_price += $quoteProduct['newprice'] * $quoteProduct['quantity'];
        $args['quoteProduct'] = $quoteProduct;
        $args['_product'] = $_product;
        $args['price'] = $price;
        quoteupGetAdminTemplatePart('pdf-quote/quote-table-body', "", $args);
    }
    ?>
    <tr border="1" class="sub-total">
        <td align="right" colspan="3"> 
            <?php _e('Sous-total Options', 'quoteup'); ?> 
        </td>
        <td align="right" >
            <?php echo wc_price($opt_total_price); ?>
        </td>
    </tr>
</table>
<?php } ?>
<table align="center" class="quote_table" style="margin-top:15px;border-collapse: collapse;">
    <tr border="1" class="grand-total">
        <td align="right" colspan="3"> 
            <?php _e('TOTAL GENERAL', 'quoteup'); ?> 
        </td>
        <td align="right" >
            <?php 
		    $grand_total_price = $total_price + $opt_total_price;
            echo wc_price($grand_total_price); ?>
        </td>
    </tr>
</table>
 