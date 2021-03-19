<?php if (! empty($expiration_date)) : ?>
	<div class="expiracy_date">
		<b>Offre valable jusqu&#x2019;au</b> :  <?php echo date( 'd/m/Y', strtotime($expiration_date));?>
	</div>
<?php endif; ?>