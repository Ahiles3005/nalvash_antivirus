<?php
/**
 * Template for 404 page.
 *
 * @package Vashnal
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

?>
	<div class="container">

        <div class="content">

            <div class="error-404">

                <div class="error-404__caption caption caption--center">
                    <?php echo __( '404 Error', VASHNAL_TEXT_DOMAIN ); ?>
                </div>

                <?php if ( is_active_sidebar( '404_error' ) ) { ?>

                    <div class="error-404__text">
                        <?php dynamic_sidebar( '404_error' ); ?>
                    </div>

                    <div class="error-404__links">
                        <?php wp_nav_menu(
                            array(
                                'theme_location' => 'header_menu',
                                'menu_depth' => 1
                            )
                        ); ?>
                    </div>

                <?php } ?>

            </div>

        </div>

	</div>
<?php

get_footer();