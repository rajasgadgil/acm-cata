<?php
 
if( !empty( $args['enquiry_id'] ) ) {
	global $wpdb;
	$enq_id	= $args['enquiry_id'];
    $sql_enq = "SELECT * FROM {$wpdb->prefix}enquiry_detail_new WHERE enquiry_id ='$enq_id'";

    $results_enq = $wpdb->get_results($sql_enq);
}?>
<html>
    <body>
        <div id='header'>
            <?php quoteupGetAdminTemplatePart('pdf-quote/company-logo', "", $args); ?>
            <div class="content">
               <div class="quote-info">
				<div class="quote-info-right">
					<span style="font-style:italic;font-weight:bold;">ACM Caraïbes</span>  : <span style="color:#e36c0a; font-size: small;font-weight: 700;">Affilia&on <font color="#1367dc">F</font><font color="#ccc">F</font><font color="red">V</font> – Label <font color="#1367dc">F</font><font color="#ccc">F</font><font color="red">V</font> Ecole de croisière – Label <font color="#1367dc">F</font><font color="#ccc">F</font><font color="red">V</font> coach plaisance</span><br>Ecole de croisière - Ges.on & Loca.on -Ventes « Mul.marque »
				</div>
				<div class="quote-info-left">
					Date : <?php if( !empty( $results_enq[0]->enquiry_date ) ) {echo date( 'M j, Y', strtotime($results_enq[0]->enquiry_date));}?><br>
					Contrat : <?php echo $args['enquiry_id'];?>
				</div>
			</div>
			<div class="quote-inner-content">
				<div class="contrat-location">
					<span style="font-weight:bold;">Contrat de loca:on d’un bateau de plaisance</span> <br>
					<span style="color:#e36c0a; font-size: small;font-weight: 700;">SARL ADN Yach,ng agrément dans le cadre d’un contrat de mandataire par le propriétaire</span>
				</div>
				
                <?php quoteupGetAdminTemplatePart('pdf-quote/expiration-date', "", $args);?>
               
                <?php quoteupGetAdminTemplatePart('pdf-quote/sender-info', "", $args); ?>
                
                <?php quoteupGetAdminTemplatePart('pdf-quote/recipient-info', "", $args); ?>
               
				
			<div id="Enquiry">
		        <?php
                quoteupGetAdminTemplatePart('pdf-quote/quote-table', "", $args);
                //quoteupGetAdminTemplatePart('pdf-quote/tax-shipping-note', "", $args);
            	?>
   			 </div>
   			 <div class="footer-content">
   			 	<h4 style="width: 70%;background: #ccc;"> AUTRES ELEMENTS A PREVOIR </h4> 
   				<span>
					Le transfert A&#xE9;roport &#x2014;&#x3E; &#xAB;!le marin!&#xBB;<br>
					Avitaillement<br>
					Taxes de douane et parcs nationaux<br>
				</span>
				<h5 style="width: 70%;background: #ccc;"> Retrouver ces &#xE9;l&#xE9;ments dans la rubrique &#x2018;Infos Compl&#xE9;mentaires&#x2019; (include link to /mon-compte2/infos/ </h5> 
   			 	
				<p>Une caution d&#x2019;un montant de [caution] vous sera demand&#xE9;e &#xE0; la prise du bateau par Carte bancaire.
Franchise : si vous ne b&#xE9;n&#xE9;ficiez pas du rachat de franchise, le montant est de [franchise] ou [franchise-rachat] avec l&#x2019;option rachat de
franchise. Le rachat de franchise est obligatoire avec skipper et tr&#xE8;s vivement conseill&#xE9;e pour une location sans Skipper.</p>
				
				<p>*Embarquement : 1&#xE8;re nuit &#xE0; bord et mise en main technique le lendemain matin<br>
*Non couvert par l&#x27;assurance et le rachat de franchise le vol annexe / moteur HB) et franchise en RC<br>
*** Le catamaran doit &#xEA;tre rendu propre, vaisselle et coin cuisine nettoy&#xE9;e. %<br>
Dans le cas o&#xF9; le cata est rendu tr&#xE8;s sale un suppl&#xE9;ment de 100 ! est retenu sur la caution. Merci de votre compr&#xE9;hension %</p>
				<p><span style="font-weight:bold">Acompte &#xE0; la r&#xE9;servation</span> &#xE0; l&#x2019;ordre de ADN puis le solde 1 mois avant votre d&#xE9;part. Mode de r&#xE8;glement: virement, ch&#xE8;que ou
directement par carte bancaire sur le site <font color="red">Adresse si&#xE8;ge</font>: 41 ruelle du ch&#xE2;teau 69 620 le Bois d&#x2019;Oingt</p>			
				<p>VOUS POUVEZ ACCEPTER CE CONTRAT EN LIGNE EN REGLANT DIRECTEMENT L&#x2019;ACOMPTE DE 30% DU MONTANT DE LA
LOCATION EN CLIQUANT SUR CE LIEN - <font color="blue" style="font-weight:bold;" >APPROUVER CETTE PROPOSITION (link to proposal web acceptance)</font></p>
				<span style="font-weight:bold;">loueur signature lu et approuve/date</span><span  style="font-weight:bold;">locataire signature lu et approuve/date</span>
   				<h5>Cordialement</h5>
   				<p>Ce contract n'a de vaieur qua reception de l'acompte de reservation (merci de votre comprehension) photo non contractuelle</p>
   			 	<div class="footer-img"><img src="http://acm-caraibes.com/wp-content/uploads/2017/07/footer-icon.png"></div>
				</div>
   			</div>
      </div> <!-- .content ends here -->
       
    </body>
</html>
