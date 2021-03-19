<?php
$heading = __('QUOTATION', 'quoteup');
do_action('woocommerce_email_header', $heading);
?>
        
        <div id="head">
            <h2 class="quote-heading">
                <?php _e('Quote Request', 'quoteup'); ?> #<?php echo "$enquiry_id"; ?>
            </h2>
        </div> <!-- #head ends here -->
        <div id="Enquiry">
            <?php
                quoteupGetAdminTemplatePart('pdf-quote/quote-table', "", $args);
                quoteupGetAdminTemplatePart('pdf-quote/tax-shipping-note', "", $args);
            ?>
        </div> <!-- #Enquiry ends here -->
