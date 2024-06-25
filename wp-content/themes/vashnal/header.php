<?php
/**
 * The header for Vashnal theme.
 *
 * @package Vashnal
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

use Vashnal\Theme;

global $vashnal_svg;

?><!doctype html>

<html <?php language_attributes(); ?>>

	<head>
<link rel="preload" as="image" href="https://vashnal.ru/wp-content/uploads/2024/03/mobile_1.webp">
        <link rel="preload" href="<?php echo Theme::get_font_url(); ?>" as="font" type="font/woff2" crossorigin />
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="format-detection" content="telephone=no">
		<title><?php wp_title(); ?></title>

		<?php wp_head(); ?>

        <?php if ( is_active_sidebar( 'before_head' ) ) {
             dynamic_sidebar( 'before_head' );
        } ?>

<style>
    .logo.container {
    margin: 15px 0;
    width: 23%;
}
.vs_banner img.planshet {
    display: none;
}

.vs_banner img.mobilnye {
    display: none;
}

.vs_banner {
    width: calc(77% - 20px);
}
@media (max-width: 1199px) {
    .logo.container {
        display: none;
    }
    .vs_banner img.planshet {
        display: block;
    }
    .vs_banner img.desktop {
        display: none;
    }
    .vs_banner {
        width: calc(100% - 60px);
        margin: auto;
    }
}
@media (max-width: 539px) {
    .vs_banner img.mobilnye {
        display: block;
    }
    .vs_banner img.planshet {
        display: none;
    }
    .vs_banner {
        width: calc(100% - 30px);
        margin: auto;
    }
}
.logo__image {
    margin: initial !important;
}

.header-banner {
    display: flex;
    max-width: 1170px;
    height: 204px;
    margin: 50px auto 30px;
    gap: 20px;
}

.reklama-banner-dsk {
    margin: auto;
}
.reklama-banner-mob {
		display:none;		
}

@media (max-width: 1200px) {
	
	.header-banner {
		gap: 0px;
		height:auto;
	}
	
	.logo.container {
    padding: 0 !important;
 }

}
 
@media (max-width: 940px) {
	
	.header-banner {
		margin: 20px 0 0;
    	min-height: 100px;
    	display: block;
	}

	
	 .reklama-banner-dsk {
		display:none;
 	}
	
	.reklama-banner-mob {
		display:block;
		margin: auto;
 	}

}
</style>


	</head>

	<body <?php body_class(); ?>>

        <?php if ( is_active_sidebar( 'after_open_body' ) ) {
            dynamic_sidebar( 'after_open_body' );
        } ?>

		<?php wp_body_open(); ?>

		<header class="header">

            <div class="header__container container">

	            <?php get_template_part( 'template-parts/header/home' ); ?>

                <nav class="header__menu" id="main_menu">

                    <?php wp_nav_menu(
                        array(
                            'theme_location' => 'header_menu',
                            'menu_depth' => 1
                        )
                    ); ?>

                    <?php get_template_part( 'template-parts/header/search' ); ?>

                </nav>

                <div class="header__button button button--question" id="open_question_form">
	                <?php $vashnal_svg->print_symbol( 'message' ); ?>
                    <span><?php echo __( 'ask question', 'vashnal' ); ?></span>
                </div>

                <div class="header__hamburger" id="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>

                <div class="header__hamburger__placeholder"></div>

            </div>

		</header>

		<div class="wrapper">
		
		    <!--Header-->
			<div class="header-banner">
				<!--Logo-->
				<?php get_template_part( 'template-parts/header/logo' ); ?>
				
				<?php 
			    $desktop_banner  = get_field('desktop', 16);
			    $planshet_banner  = get_field('planshet', 16);
			    $mobilnye_banner  = get_field('mobilnye', 16);
			    $link_banner  = get_field('ssylka', 16);
				if ($desktop_banner || $planshet_banner || $mobilnye_banner) { ?>
				    <div class="vs_banner">
				        <?=$link_banner?'<a target="_blank" href="'.$link_banner.'">':''?>
				        <img class="desktop" src="<?php echo $desktop_banner; ?>" width="2610" height="600" decoding="async" alt="">
				        <img class="planshet" src="<?php echo $planshet_banner; ?>" width="1932" height="600" loading="lazy" decoding="async" alt="">
				        <img class="mobilnye" src="<?php echo $mobilnye_banner; ?>" width="1000" height="1000" decoding="async" alt="">
				        <?=$link_banner?'</a>':''?>
				    </div>
				<?php
				} else { ?>
				<!-- Yandex.RTB -->
				<script>window.yaContextCb=window.yaContextCb||[]</script>
				<script src="https://yandex.ru/ads/system/context.js" async></script>
				<!--Desktop Banner-->
				<div class="reklama-banner-dsk">					
					<!-- Yandex.RTB R-A-113746-4 -->
					<div id="yandex_rtb_R-A-113746-4"></div>
					<script>window.yaContextCb.push(()=>{
					  Ya.Context.AdvManager.render({
						renderTo: 'yandex_rtb_R-A-113746-4',
						blockId: 'R-A-113746-4'
					  })
					})</script>
	
				</div>
				
				<!--Mobile Banner-->
				<div class="reklama-banner-mob">					
					<!-- Yandex.RTB R-A-113746-2 -->
					<div id="yandex_rtb_R-A-113746-2"></div>
					<script>window.yaContextCb.push(()=>{
					  Ya.Context.AdvManager.render({
						renderTo: 'yandex_rtb_R-A-113746-2',
						blockId: 'R-A-113746-2'
					  })
					})</script>
	
				</div>
				
				<?php } ?>
				
            </div>
			<!--/-->

            <?php if ( is_active_sidebar( 'header_links' ) && is_front_page() ) { ?>
                <div class="header__links container">
                    <?php dynamic_sidebar( 'header_links' ); ?>
                </div>
            <?php } ?>

            <?php if ( ! is_front_page() ) { ?>

                <div class="breadcrumbs container">
                    <?php
                    if ( function_exists( 'yoast_breadcrumb' ) ) {
                        yoast_breadcrumb( '<div class="breadcrumbs__list">','</div>' );
                    }
                    ?>
                </div>

                <?php if ( is_category() ) { ?>
                    <h1 class="caption container"><?php the_archive_title(); ?></h1>
                <?php } elseif ( ! is_search() ) { ?>
                    <h1 class="caption container"><?php echo get_the_title(); ?></h1>
                <?php } ?>

            <?php }