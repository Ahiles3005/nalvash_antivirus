<?php

if ( ! defined( 'ABSPATH' ) ) exit;

?>
<div class="post__date"><?php echo get_the_date( 'd.m.Y', get_the_ID() ); ?></div>
<div class="post__question"><?php the_content() ; ?></div>
<?php if ( $author = get_field( 'name', get_the_ID() ) ) { ?>
    <div class="post__author"><?php echo $author; ?></div>
<?php } ?>
<div class="post__answer">
	<?php
    if ( $answer = get_field( 'answer', get_the_ID() ) ) {
        ?>
        <div class="caption caption--blue"><?php echo __( 'Answer', 'vashnal' ); ?></div>
        <?php
	    echo apply_filters( 'vashnal_answer', $answer );
        if ( is_active_sidebar( 'answer_signature' ) ) {
            dynamic_sidebar( 'answer_signature' );
        }
    } else {
        ?>
        <div class="caption caption--gray caption--small caption--center"><?php
            echo __( 'there is no answer yet', 'vashnal' ); ?></div>
        <?php
    }
    ?>
</div>
