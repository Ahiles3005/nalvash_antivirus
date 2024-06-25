<?php
/**
 * The single post template file
 *
 * This is the single post template file for a Vashnal theme
 *
 * @package Vashnal
 *
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

use Vashnal\Content;

get_header();

while ( have_posts() ) {
    the_post();
?>
    <div class="post container">

        <div class="<?php echo apply_filters( 'content_class', 'content' ) ?>">
            <?php
            if ( Content::is_question( get_the_ID() ) ) {
                get_template_part( 'template-parts/single/post', 'question' );
            } else {
                get_template_part( 'template-parts/single/post' );
            }
            ?>
        </div>

    </div>

    <?php if ( ( is_single() || is_page() )
              && ( comments_open() || get_comments_number() )
              && ! post_password_required() ) {
        comments_template();
    } ?>

<?php
}

get_footer();