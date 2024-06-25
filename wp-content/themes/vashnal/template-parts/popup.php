<?php

if ( ! defined( 'ABSPATH' ) ) exit;

global $vashnal_svg;

/** @global mixed $args content to display in popup */

?>
<div class="popup">
	<?php
        if ( array_key_exists( 'css', $args ) && $args['css'] ) {
            echo $args['css'];
        }
    ?>
	<div class="popup__close"><?php $vashnal_svg->print_symbol( 'close' ); ?></div>
	<div class="popup__content"><?php echo $args['content']; ?></div>
</div>
<div class="popup__overlay"></div>