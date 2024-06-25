<?php

if ( ! defined( 'ABSPATH' ) ) exit;

global $vashnal_svg;

?>
<div class="header__search">
	<form method="get" id="search_form" class="header__search__form" action="/">
		<button class="header__search__button" type="submit" aria-label="<?php
            echo __( 'Search', VASHNAL_TEXT_DOMAIN ); ?>">
			<?php $vashnal_svg->print_symbol( 'search' ); ?>
		</button>
		<label>
			<input class="header__search__form__input" type="text" value="<?php
                echo filter_input( INPUT_GET, 's' ); ?>" aria-label="<?php
                echo __( 'Search', VASHNAL_TEXT_DOMAIN ); ?>" name="s" autocomplete="off">
		</label>
	</form>
</div>