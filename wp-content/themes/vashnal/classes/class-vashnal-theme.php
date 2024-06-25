<?php

namespace Vashnal;

/**
 * Custom theme functions, helpers and default functionality redefinition.
 *
 * @package Vashnal
 */
class Theme {

    /**
     * Add hooks for redefinition of default Wordpress functionality.
     */
    public static function setup(): void {
        $class = new self();

        // Add html5 support.
        add_theme_support( 'html5', [ 'script', 'style', 'comment-list' ] );

        // Set language text domain for translations.
        load_theme_textdomain( VASHNAL_TEXT_DOMAIN, get_template_directory() . '/languages' );

        // Set custom logo to admin panel login page.
        add_filter( 'login_headertext', array( $class, 'change_login_logo' ), 1, 0 );
        add_action( 'login_head', array( $class, 'remove_default_login_logo' ), 1 );

        // Disable all RSS feed.
        add_action( 'do_feed', array( $class, 'disable_feed' ), 1 );
        add_action( 'do_feed_rdf', array( $class, 'disable_feed' ), 1 );
        add_action( 'do_feed_rss', array( $class, 'disable_feed' ), 1 );
        add_action( 'do_feed_rss2', array( $class, 'disable_feed' ), 1 );
        add_action( 'do_feed_atom', array( $class, 'disable_feed' ), 1 );
        add_action( 'do_feed_rss2_comments', array( $class, 'disable_feed' ), 1 );
        add_action( 'do_feed_atom_comments', array( $class, 'disable_feed' ), 1 );
        remove_action( 'wp_head', 'feed_links_extra', 3 );
        remove_action( 'wp_head', 'feed_links', 2 );

        // Remove yoast comments and unnecessary data
        if ( defined( 'WPSEO_VERSION' ) ) {
            add_action( 'get_header', array( $class, 'remove_yoast_version' ) );
            add_filter( 'wpseo_json_ld_output', '__return_false' );
        }

        // Disable big images scaling.
        add_filter( 'big_image_size_threshold', '__return_false' );

        // Remove DNS prefetch tag for //s.w.org.
        add_filter( 'emoji_svg_url', '__return_false' );

        // Remove default body classes except admin-bar.
        add_filter( 'body_class', array( $class, 'remove_body_classes' ), 10 );

        // Fix autoptimize scripts combining for cyrillic sites.
        add_filter( 'autoptimize_filter_js_defer', array( $class, 'autoptimize_override_defer' ), 10, 1 );
        add_filter( 'autoptimize_filter_js_replacetag', array( $class, 'autoptimize_override_closing_tag' ), 1, 1 );

        // Remove Wordpress default meta tags.
        remove_action( 'wp_head', 'wp_generator' );
        remove_action( 'wp_head', 'rest_output_link_wp_head' );
        remove_action( 'wp_head', 'wlwmanifest_link' );
        remove_action( 'wp_head', 'rsd_link' );
        remove_action( 'wp_head', 'wp-block-library-css' );

        // Remove Wordpress oembed frontend library.
        remove_action( 'wp_head', 'wp_oembed_add_host_js' );
        remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
        remove_action( 'rest_api_init', 'wp_oembed_register_route' );
        remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result' );

        // Remove Wordpress emoji styles and scripts.
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );

        // Register menus.
        add_action( 'init', array( $class, 'register_menus' ) );

        // Register theme widgets placeholders.
        add_action( 'init', array( $class, 'register_widgets' ) );

        //Register custom widgets.
        add_action( 'widgets_init', array( $class, 'init_custom_widgets' ) );
    }

    /**
     * Remove default login page logo.
     */
    public static function remove_default_login_logo(): void {
        ?>
        <style>
            .login h1 a {
                background: none !important;
                height: 200px !important;
                width: 200px !important;
                text-indent: initial !important;
            }

            .login h1 a svg {
                width: 100%;
                height: 100%;
            }
        </style>
        <?php
    }

    /**
     * Add custom svg logo to admin login page.
     *
     * @return string
     */
    public static function change_login_logo(): string {
        global $vashnal_svg;

        return $vashnal_svg->get_symbol( 'logo' );
    }

    /**
     * Register theme widgets placeholders.
     */
    public function register_widgets(): void {
        foreach ( VASHNAL_WIDGETS_PLACES as $widget_slug => $widget ) {
            if ( function_exists( 'register_sidebar' ) ) {
                register_sidebar(
                    array(
                        'name'          => __( $widget['name'], 'vashnal' ),
                        'id'            => $widget_slug,
                        'before_widget' => '',
                        'after_widget'  => '',
                        'before_title'  => isset( $widget['before_title'] ) ? $widget['before_title'] : '',
                        'after_title'   => isset( $widget['after_title'] ) ? $widget['after_title'] : '',
                    )
                );
            }
        }
    }

    /**
     * Register custom widgets.
     */
    public function init_custom_widgets(): void {
        foreach ( VASHNAL_CUSTOM_WIDGETS as $widget_class ) {
            if ( class_exists( $widget_class ) ) {
                register_widget( $widget_class );
            }
        }
    }

    /**
     * Register menus.
     */
    public static function register_menus(): void {
        foreach ( VASHNAL_MENUS as $menu_slug => $menu_name ) {
            if ( function_exists( 'register_nav_menu' ) ) {
                register_nav_menu( $menu_slug, __( $menu_name, 'vashnal' ) );
            }
        }
    }

    /**
     * Return 500 error with notification for all feeds.
     */
    public static function disable_feed(): void {
        wp_die( __( VASHNAL_NO_FEED_MESSAGE, VASHNAL_TEXT_DOMAIN ) );
    }

    /**
     * Remove html comment with Yoast SEO version.
     */
    public static function remove_yoast_version(): void {
        ob_start( function ( $output ) {
            return preg_replace( '/\n?<.*?yoast seo plugin.*?>/mi', '', $output );
        } );
    }

    /**
     * Remove default body classes except admin-bar.
     *
     * @return array
     */
    public static function remove_body_classes(): array {
        if ( ! is_admin() && is_admin_bar_showing() ) {
            return array( 'admin-bar' );
        }

        return array();
    }

    /**
     * Add defer and async attributes for autoptimize compiled scripts.
     *
     * @return string
     */
    public static function autoptimize_override_defer(): string {
        return "defer async ";
    }

    /**
     * Replace autoptimize script place.
     *
     * @return string[]
     */
    public static function autoptimize_override_closing_tag(): array {
        return array( '"telephone=no">', 'after' );
    }

    /**
     * Get URL for theme folder with javascript files.
     *
     * @return string
     */
    public static function get_js_folder_url(): string {
        return get_template_directory_uri() . VASHNAL_JS_PATH;
    }

    /**
     * Get URL for theme folder with CSS/SCSS files.
     *
     * @return string
     */
    public static function get_css_folder_url(): string {
        return get_template_directory_uri() . VASHNAL_CSS_PATH;
    }

    /**
     * Get relative path for theme folder with CSS/SCSS files.
     *
     * @return string
     */
    public static function get_css_folder_path(): string {
        return get_template_directory() . VASHNAL_CSS_PATH;
    }

    /**
     * Get URL for theme folder with fonts.
     *
     * @return string
     */
    public static function get_fonts_folder_url(): string {
        return get_template_directory_uri() . VASHNAL_FONTS_PATH;
    }

    /**
     * Get URL for theme folder with images/icons.
     *
     * @return string
     */
    public static function get_img_folder_url(): string {
        return get_template_directory_uri() . VASHNAL_IMG_PATH;
    }

    /**
     * Get relative path to SVG spritemap file.
     *
     * @return string
     */
    public static function get_svg_spritemap_filename(): string {
        return get_template_directory_uri() . VASHNAL_IMG_PATH . VASHNAL_SVG_SPRITEMAP_NAME . self::min() . '.svg';
    }

    /**
     * Get URL for font file.
     *
     * @param string $style regular|bold|thin
     * @param string $font_type woff|woff2|otf|ttf|eot
     *
     * @return string
     */
    public static function get_font_url( string $style = 'regular', string $font_type = 'woff2' ): string {
        return self::get_fonts_folder_url() . VASHNAL_FONT_NAME . '-' . $style . '.' . $font_type;
    }

    /**
     * Get URL for empty image file for lazy load placeholder.
     *
     * @return string
     */
    public static function get_empty_image_url(): string {
        return self::get_img_folder_url() . VASHNAL_EMPTY_IMAGE_NAME;
    }

    /**
     * Get URL for ajax requests handler.
     *
     * @return string
     */
    public static function get_ajax_handler_url(): string {
        return admin_url( 'admin-ajax.php' );
    }

    /**
     * Return .min file suffix if site is not in debug mode.
     *
     * @return string
     */
    public static function min(): string {
        return ( WP_DEBUG === true ) ? '' : '.min';
    }

    /**
     * Get image for file type icon
     *
     * @param string $type
     *
     * @return string
     */
    public static function get_file_type_icon( string $type ): string {
        $icon_file_id = VASHNAL_FILE_ICON_ID[ strtolower( $type ) ];
        $icon_src     = wp_get_attachment_image_src( $icon_file_id, 'full' );
        $icon         = '';
        if ( is_array( $icon_src ) && ! empty( $icon_src ) ) {
            $icon = '<img src="'
                    . Theme::get_empty_image_url() . '" data-src="' . $icon_src[0] . '" alt="' . $type . '"/>';
        }

        return $icon;
    }

}