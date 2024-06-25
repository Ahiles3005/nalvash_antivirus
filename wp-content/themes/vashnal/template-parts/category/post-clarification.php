<?php

if ( ! defined( 'ABSPATH' ) ) exit;

use Vashnal\Content;

global $vashnal_svg;

?>
<div class="category__post category__post--clarification">
    <div class="category__post__wrapper">
        <div class="category__post__date"><?php echo get_the_date( 'd.m.Y', get_the_ID() ); ?></div>
        <a class="category__post__title category__post__title--clarification" href="<?php echo get_permalink(); ?>">
            <?php the_title() ; ?>
        </a>
        <div class="category__post__content">
            <div class="category__post__content__text">
                <?php echo Content::get_preview_text( get_the_ID(), 400 ); ?>
            </div>
        </div>
    </div>
</div>