<?php
/**
 * The template file for displaying the comments and comment form for the Vashnal theme.
 *
 * @package Vashnal
 * @since 1.0
 *
 * @var array $comments
 */

if ( post_password_required() ) {
	return;
}
$comments_number = absint( get_comments_number() );

?>
<div class="post__comments">

    <div class="container">

        <div class="post__comments__wrapper">

            <div class="post__comments__caption caption">
                <?php echo __( 'Comments', VASHNAL_TEXT_DOMAIN ); ?>
                <?php echo ( $comments_number ) ? '<span class="post__comments__caption__number">'
                                                  . $comments_number . '</span>': '' ; ?>
            </div>

            <?php if ( comments_open() || pings_open() ) { ?>
                <div id="comment-form-wrapper" data-post-id="<?php echo get_the_ID(); ?>"></div>
            <?php } ?>

            <?php if ( $comments ) { ?>
	            <div class="post__comments__list" id="comments">
                    <?php
                    wp_list_comments(
                        array(
                            'walker' => new Vashnal\Vashnal_Walker_Comment(),
                            'reverse_top_level' => true
                        )
                    );
                    ?>
	            </div>
            <?php } ?>

        </div>

    </div>

</div>