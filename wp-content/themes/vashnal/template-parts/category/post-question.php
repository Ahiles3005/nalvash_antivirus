<?php

if ( ! defined( 'ABSPATH' ) ) exit;

use Vashnal\Content;

global $vashnal_svg;

?>
<div class="category__post category__post--question<?php
    echo ( ! is_front_page() ) ? ' category__post--question-cat' : ''; ?>">
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
            <div class="category__post__content__text"><?php
                echo Content::get_preview_text( get_the_ID(), 200 ); ?></div>
        </div>
        <div class="category__post__bottom">
            <a class="category__post__bottom__answer<?php
            echo ( Content::question_has_answer( get_the_ID() ) ) ? ' active' : ''?>" href="<?php
            echo get_permalink(); ?>">
		        <?php echo ( Content::question_has_answer( get_the_ID() ) )
                    ? __( 'answer exists', 'vashnal' )
                    : __( 'there is no answer yet', 'vashnal' ); ?>
            </a>
            <div class="category__post__bottom__data">
                <div class="category__post__bottom__data__name"><?php
                    echo get_field( 'name', get_the_ID() ); ?></div>
                <div class="category__post__bottom__data__date"><?php
                    echo get_the_date( 'd.m.Y', get_the_ID() ); ?></div>
            </div>
        </div>
    </div>
</div>