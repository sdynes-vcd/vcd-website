<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$this->do_meta_boxes( 'box-gzip-top' );
$this->do_meta_boxes( 'box-gzip-bottom' );

?>

<script>
	jQuery(document).ready( function() {
		if ( window.WPHB_Admin ) {
			window.WPHB_Admin.getModule( 'gzip' );
		}
	});
</script>