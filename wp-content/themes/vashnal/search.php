<?php
/**
 * The search template file
 *
 * This is the search results template file in a Vashnal theme
 *
 * @package Vashnal
 *
 * @since 1.0
 */

use Vashnal\Content;

$search_phrase = filter_input( INPUT_GET, 's' );

get_header();

?>
    <h1 class="caption container"><?php echo
            __( 'Search results', VASHNAL_TEXT_DOMAIN ) . ': ' . $search_phrase; ?></h1>

    <div class="search-results container">

    <?php if ( have_posts() ) { ?>

        <div class="search-results__list">
            <?php
            while ( have_posts() ) {
                the_post();
                $preview_text = Content::get_preview_text( get_the_ID(), 400 );
                ?>
            <div class="search-results__list__item">
                <div class="search-results__list__item__date">
                    <?php echo get_the_date( 'd.m.Y', get_the_ID() ); ?>
                </div>
                <a class="search-results__list__item__name" href="<?php echo get_permalink( get_the_ID() ); ?>">
                    <?php echo Content::highlight_search_phrases( get_the_title(), $search_phrase ); ?>
                </a>
                <div class="search-results__list__item__text">
                    <?php echo Content::highlight_search_phrases( $preview_text, $search_phrase ); ?>
                </div>
            </div>
            <?php } ?>
        </div>

        <div class="search-results__pagination pagination">
            <?php
            echo paginate_links( array(
                'mid_size' => 1,
                'prev_text' => __( '&laquo;' ),
                'next_text' => __( '&raquo;' ),
                'format' => '?paged=%#%'
            ) );
            ?>
        </div>

    <?php } else { ?>

        <div class="search-results__empty">

            <?php echo __( 'No results found.', VASHNAL_TEXT_DOMAIN ); ?>

        </div>

    <?php } ?>

    </div>
<?php
get_footer();