<?php
/**
 * Homepage template for Vashnal theme
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Vashnal
 */

if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

?>
<?php if ( is_active_sidebar( 'homepage_category' ) ) { ?>
    <div class="homepage">
        <?php dynamic_sidebar( 'homepage_category' ); ?>
    </div>
<?php
}

get_footer();