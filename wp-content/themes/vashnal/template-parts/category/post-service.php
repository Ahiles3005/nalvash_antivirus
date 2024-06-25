<?php

if ( ! defined( 'ABSPATH' ) ) exit;

use Vashnal\Content;

global $vashnal_svg;

?>
<div class="category__post">
    <div class="category__post__wrapper">
        <a class="category__post__title" href="<?php echo get_permalink(); ?>">
            <?php the_title() ; ?>
        </a>
        <div class="category__post__content">
            <?php if ( Content::post_icon_exists( get_the_ID() ) ) { ?>
                <div class="category__post__content__icon">
                    <?php $vashnal_svg->print_symbol( Content::get_post_icon( get_the_ID() ) );?>
                </div>
            <?php } ?>
            <div class="category__post__content__text"><?php echo Content::get_preview_text( get_the_ID() ); ?></div>
        </div>
        <a class="category__post__more" href="<?php echo get_permalink(); ?>">
            <?php echo __( 'Read more', 'vashnal' ); ?> &raquo;
        </a>
    </div>
</div>