<?php
/**
 * Template for category pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Vashnal
 */

if ( ! defined( 'ABSPATH' ) ) exit;

use Vashnal\Content;

get_header();

?>
    <div class="category container">

        <?php if ( Content::has_children_terms() || Content::is_child_term() ) {
	        get_template_part( 'template-parts/category/categories' );
        } ?>

        <?php if ( have_posts() ) { ?>

            <div class="category__list">
                <?php while ( have_posts() ) {
                    the_post();
	                get_template_part( 'template-parts/category/post', Content::get_current_term_template() );
                } ?>
            </div>

			<div class="category__pagination pagination">
				<?php
                echo paginate_links( array(
                        'mid_size' => 1,
						'prev_text' => __( '&laquo;' ),
						'next_text' => __( '&raquo;' )
					) );
                ?>
			 </div> 

        <?php } else { ?>

		    <?php get_template_part( 'template-parts/category/empty' ); ?>

		<?php } ?>

    </div>
<?php

get_footer();