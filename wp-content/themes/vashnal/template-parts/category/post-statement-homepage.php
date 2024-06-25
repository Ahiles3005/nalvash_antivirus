<?php

if ( ! defined( 'ABSPATH' ) ) exit;

use Vashnal\Content;

global $vashnal_svg;

?>
<div class="category__post category__post--statement">
	<div class="category__post__wrapper category__post__wrapper--statement">
		<a class="category__post__title category__post__title--statement category__post__title--statement-homepage" href="<?php echo get_permalink(); ?>">
			<?php the_title() ; ?>
		</a>
		<div class="category__post__content category__post__content--statement category__post__content--statement-homepage">
			<div class="category__post__content__text">
                <?php echo Content::get_preview_text( get_the_ID(), 100 ); ?>
            </div>
		</div>
	</div>
</div>