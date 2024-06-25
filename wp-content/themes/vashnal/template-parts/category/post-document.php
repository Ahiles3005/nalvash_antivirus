<?php

if ( ! defined( 'ABSPATH' ) ) exit;

use Vashnal\Content;

global $vashnal_svg;

?>
<div class="category__post category__post--question">
    <div class="category__post__wrapper">
        <a class="category__post__title" href="<?php echo get_permalink(); ?>">
            <?php the_title() ; ?>
        </a>
        <div class="category__post__content">
            <div class="category__post__content__text"><?php echo Content::get_preview_text( get_the_ID() ); ?></div>
        </div>
        <div class="category__post__bottom">
            <div class="category__post__bottom__data">
                <div class="category__post__bottom__data__date"><?php
                    echo get_the_date( 'd.m.Y', get_the_ID() ); ?></div>
            </div>
            <div class="category__post__bottom__data">
                <a class="category__post__more" href="<?php echo get_permalink(); ?>">
			        <?php echo __( 'Read more', 'vashnal' ); ?> »
                </a>
            </div>
        </div>

    </div>
</div>