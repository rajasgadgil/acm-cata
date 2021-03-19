<?php

if (!defined('ABSPATH')) {
    exit;
}
?>

<script type="text/template" id="tmpl-quote-creation" >
    <tr class="wdmpe-detailtbl-content-row quotetbl-content-row">
        <td class="quote-product-remove">
            <a href="#" class="remove" data-row-num="{{{data.perProductDetail.count}}}" data-id="{{{data.perProductDetail.ID}}}" data-product_id="{{{data.perProductDetail.productID}}}" data-variation_id="{{{data.perProductDetail.variationID}}}" data-variation="{{{data.perProductDetail.variationAttributes}}}">×</a>
        </td>';
        <td class="wdmpe-detailtbl-content-item item-content-img">
            <img src= "{{{data.perProductDetail.product_image}}}" class='wdm-prod-img'/>
        </td>
        <td class="quote-product-title"> 
            <a href="{{{data.perProductDetail.url}}}" target='_blank' id='product-title-{{{data.perProductDetail.count}}}' >{{{data.perProductDetail.productTitle}}}</a>
        </td>
        <td class="quote-product-variation" data-row-num="{{{data.perProductDetail.count}}}" id="variations-{{{data.perProductDetail.count}}}" data-product-link="{{{data.perProductDetail.productTitle}}}">
                {{{data.perProductDetail.variationSelector}}}
        </td>
        <td class="wdmpe-detailtbl-content-item item-content-sku">
            {{{data.perProductDetail.sku}}}
        </td>
        <td class="quote-product-sale-price item-content-old-cost" data-old_price='{{{data.perProductDetail.oldPriceData}}}'>
            <span class="woocommerce-Price-amount amount">{{{data.perProductDetail.productPriceSymbol}}}</span>
            <input type="hidden" name="content-sale-price" id="content-sale-price-{{{data.perProductDetail.count}}}" data-row-num="{{{data.perProductDetail.count}}}" value="{{{data.perProductDetail.productPrice}}}">
        </td>
        <td class="quote-product-new-price"> 
            <input id="content-new-{{{data.perProductDetail.count}}}" type="number" data-row-num="{{{data.perProductDetail.count}}}" data-id="{{{data.perProductDetail.ID}}}" data-product_id="{{{data.perProductDetail.productID}}}" data-variation_id="{{{data.perProductDetail.variationID}}}" data-variation="{{{data.perProductDetail.variationAttributes}}}" name="quote-product-new-price" class="wdm-prod-price input-text" value="{{{data.perProductDetail.productPrice}}}" min="0" step="any">
        </td>'
        <td class="quote-product-qty input-text qty">
            <input id="content-qty-{{{data.perProductDetail.count}}}" type="number" min="1" step="1" data-row-num="{{{data.perProductDetail.count}}}" data-id="{{{data.perProductDetail.ID}}}" data-product_id="{{{data.perProductDetail.productID}}}" data-variation_id="{{{data.perProductDetail.variationID}}}" data-variation="{{{data.perProductDetail.variationAttributes}}}" name="quote-product-qty" class="wdm-prod-quant quote-newqty input-text qty" value="1">
        </td>';
        <td class="quote-product-total" id="content-cost-{{{data.perProductDetail.count}}}">
            {{{data.perProductDetail.productPriceSymbol}}}
        </td>
        <input type="hidden" name="content-amount" class="amount_database" id="content-amount-{{{data.perProductDetail.count}}}" data-row-num="{{{data.perProductDetail.count}}}" value="{{{data.perProductDetail.productPrice}}}">
    </tr>
</script>