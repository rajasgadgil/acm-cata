
    <?php //quoteupGetAdminTemplatePart('pdf-quote/quote-table-header', "", $args); ?>
    <?php
        $products    = unserialize($enquiry_details->product_details);
        $grand_total_price = 0;
        $opt_total_price = 0;
        $main_total_price = 0;
    foreach ($quotation as $key => $quoteProduct) {
    	if( $key == 0 ){
    		
    		?>
    		<table align="center" class="quote_table" style="border-collapse: collapse;">
    		<?
    		 quoteupGetAdminTemplatePart('pdf-quote/quote-table-header', "", $args);
    		$_product    = wc_get_product($quoteProduct['product_id']);
	        if (empty($_product)) {
	            continue;
	        }
	        $price       = $quoteProduct['oldprice'];
	        $main_total_price = $main_total_price + $quoteProduct['newprice'] * $quoteProduct['quantity']     ;
	        $args['quoteProduct'] = $quoteProduct;
	        $args['_product'] = $_product;
	        $args['price'] = $price;
	        quoteupGetAdminTemplatePart('pdf-quote/quote-table-body', "", $args);
	        
	        ?>
	       <tr border="1" class="sub-total">
		        <td align="right" <?php echo ($show_price == 1) ? 'colspan="5"' : 'colspan="4"' ?>> 
		            <?php _e('TOTAL', 'quoteup'); ?> 
		        </td>
		        <td align="right" >
		            <?php echo wc_price($main_total_price); ?>
		        </td>
		    </tr>
		    </table>
		    <table align="center" class="opt_quote_table" style="border-collapse: collapse;">
		    <tr>
			<?php
			    $classPrefix = '';
			if ($show_price == 1) {
			    $classPrefix = '-price';
			}
			    ?>
			    <th class="head-product<?php echo $classPrefix; ?>" align="left"><?php _e('Options', 'quoteup'); ?></th>
			    <th class="head-sku<?php echo $classPrefix ?>" align="left"> <?php _e('Sku', 'quoteup'); ?> </th>
			    <?php if ($show_price == 1) : ?>
			        <th class="old_price" align="left"> <?php _e('Old', 'quoteup'); ?> </th>
			    <?php endif; ?>
			    <th class="new_price" align="left"> <?php _e('New', 'quoteup'); ?> </th>
			    <th class="quantitiy" align="center"> <?php _e('Quantity', 'quoteup'); ?> </th>
			    <th class="total" align="right"> <?php _e('Amount', 'quoteup'); ?> </th>
				
			</tr>
    		<?php
    	}else{
    		$_product    = wc_get_product($quoteProduct['product_id']);
	        if (empty($_product)) {
	            continue;
	        }
	        $price       = $quoteProduct['oldprice'];
	        $opt_total_price = $opt_total_price + $quoteProduct['newprice'] * $quoteProduct['quantity']     ;
	        $args['quoteProduct'] = $quoteProduct;
	        $args['_product'] = $_product;
	        $args['price'] = $price;
	        quoteupGetAdminTemplatePart('pdf-quote/quote-table-body', "", $args);
	        
	         ?>
	       
		    
    		<?php
    	}
    }
    $grand_total_price = $main_total_price + $opt_total_price;
    ?>
    <tr border="1" class="sub-total">
        <td align="right" <?php echo ($show_price == 1) ? 'colspan="5"' : 'colspan="4"' ?>> 
            <?php _e('TOTAL', 'quoteup'); ?> 
        </td>
        <td align="right" >
            <?php echo wc_price($opt_total_price); ?>
        </td>
    </tr>
    </table>
<table align="center" class="quote_table" style="margin-top:15px;border-collapse: collapse;">
    <tr border="1" class="grand-total">
        <td align="right" <?php echo ($show_price == 1) ? 'colspan="5"' : 'colspan="4"' ?>> 
            <?php _e('TOTAL GENERAL', 'quoteup'); ?> 
        </td>
        <td align="right" >
            <?php echo wc_price($grand_total_price); ?>
        </td>
    </tr>
</table>
 