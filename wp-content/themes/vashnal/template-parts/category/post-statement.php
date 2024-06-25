<?php

if ( ! defined( 'ABSPATH' ) ) exit;

use Vashnal\Content;

global $vashnal_svg;

?>
<div class="category__post category__post--statement">
	<div class="category__post__wrapper category__post__wrapper--statement">
        <div class="category__post__icon">
			<?php $vashnal_svg->print_symbol('icon-declaration' );?>
            <div class="category__post__icon__subtitle">
                <?php echo get_field( 'subtitle', get_the_ID() ); ?>
            </div>
        </div>
		<a class="category__post__title category__post__title--statement" href="<?php echo get_permalink(); ?>">
			<?php the_title() ; ?>
		</a>
		<div class="category__post__content category__post__content--statement">
			<div class="category__post__content__text"><?php echo Content::get_preview_text( get_the_ID(), 200 ); ?></div>
		</div>
	</div>
</div>