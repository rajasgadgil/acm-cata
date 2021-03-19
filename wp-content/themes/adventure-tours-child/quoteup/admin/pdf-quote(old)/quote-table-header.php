<tr>
<?php
    $classPrefix = '';
if ($show_price == 1) {
    $classPrefix = '-price';
}
    ?>
    <th class="head-product<?php echo $classPrefix; ?>" align="left"><?php _e('Product', 'quoteup'); ?></th>
    <th class="head-sku<?php echo $classPrefix ?>" align="left"> <?php _e('Sku', 'quoteup'); ?> </th>
    <?php if ($show_price == 1) : ?>
        <th class="old_price" align="left"> <?php _e('Old', 'quoteup'); ?> </th>
    <?php endif; ?>
    <th class="new_price" align="left"> <?php _e('New', 'quoteup'); ?> </th>
    <th class="quantitiy" align="center"> <?php _e('Quantity', 'quoteup'); ?> </th>
    <th class="total" align="right"> <?php _e('Amount', 'quoteup'); ?> </th>
	
</tr>