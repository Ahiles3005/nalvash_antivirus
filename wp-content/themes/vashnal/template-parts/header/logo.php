<?php

if ( ! defined( 'ABSPATH' ) ) exit;

global $vashnal_svg;

?>
<div class="logo container<?php if ( ! is_active_sidebar( 'header_banner' ) ) echo ' empty_hidden';?>">
	<div class="logo__image pseudo-link internal" data-href="/">
		<?php $vashnal_svg->print_symbol( 'logo','','#3d3d3d'); ?>
	</div>
	<div class="logo__advert advert">
		<?php if ( is_active_sidebar( 'header_banner' ) ) {
		    dynamic_sidebar( 'header_banner' );
		} ?>
	</div>
</div>
