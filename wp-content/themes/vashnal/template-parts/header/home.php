<?php

if ( ! defined( 'ABSPATH' ) ) exit;

global $vashnal_svg;

?>
<?php if ( ! is_front_page() ) { ?>
	<a href="/" class="header__home">
<?php } else { ?>
	<div class="header__home">
<?php } ?>
        <?php $vashnal_svg->print_symbol( 'logo', 'mobile', 'none' ); ?>
        <?php $vashnal_svg->print_symbol( 'home', 'desktop', 'none' ); ?>
<?php if ( is_front_page() ) { ?>
	</div>
<?php } else { ?>
	</a>
<?php } ?>