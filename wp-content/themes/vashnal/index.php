<?php
/**
 * The main template file
 *
 * Default template file in a Vashnal theme
 *
 * @package Vashnal
 *
 * @since 1.0
 */

get_header();

while ( have_posts() ) {

	the_post();

	the_content();

}

get_footer();