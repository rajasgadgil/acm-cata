<div class="to-info">
    <div class="to-title">
        <?php _e('Quote For', 'quoteup');?>
    </div> <!-- .to-title ends here -->
    <div class="to-data">
        <?php
            echo $name . "<br>";
            echo $mail . "<br>";
        ?>

    </div> <!-- .to-data ends here -->
</div> <!-- .to-info ends here -->
 <?php echo "<br>", "<br>", "<br>","<p align='left'>", ('Merci pour votre demande de devis. Ci-dessous les informations détaillées')?>
 <?php echo wc_get_product($quoteProduct);?>
 
