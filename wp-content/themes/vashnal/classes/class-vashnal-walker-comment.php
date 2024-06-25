<?php

namespace Vashnal;

use Walker_Comment;
use WP_Comment;

/**
 * Custom comment walker for Vashnal theme.
 *
 * @package Vashnal
 * @since 1.0
 */
class Vashnal_Walker_Comment extends Walker_Comment {

    /**
     * Outputs a comment in the HTML5 format.
     *
     * @param WP_Comment $comment Comment to display.
     * @param int $depth Depth of the current comment.
     * @param array $args An array of arguments.
     *
     * @see https://developer.wordpress.org/reference/functions/get_avatar/
     * @see https://developer.wordpress.org/reference/functions/get_comment_reply_link/
     * @see https://developer.wordpress.org/reference/functions/get_edit_comment_link/
     *
     * @see wp_list_comments()
     * @see https://developer.wordpress.org/reference/functions/get_comment_author_url/
     * @see https://developer.wordpress.org/reference/functions/get_comment_author/
     */
    protected function html5_comment( $comment, $depth, $args ) {
        ?>
        <div id="comment_<?php comment_ID(); ?>" class="post__comments__list__item<?php
        echo $this->has_children ? ' parent' : ''; ?>">

            <div class="post__comments__list__item__header">

                <div class="post__comments__list__item__header__name">
                    <?php echo get_comment_author( $comment ); ?>
                </div>

                <div class="post__comments__list__item__header__date">
                    (<?php echo get_comment_date( '', $comment ); ?>)
                </div>

            </div>

            <div class="post__comments__list__item__content">
                <?php comment_text(); ?>
            </div>

            <span class="post__comments__list__item__answer" id="comment_answer_<?php
            comment_ID(); ?>" data-comment-id="<?php comment_ID(); ?>" data-post-id="<?php echo get_the_ID(); ?>">
                <?php echo __( 'answer to comment', VASHNAL_TEXT_DOMAIN ); ?>&nbsp;&raquo;
            </span>

        </div>
        <?php
    }

}
