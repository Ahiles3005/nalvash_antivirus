<?php

namespace Vashnal;

/**
 * Theme assets organization.
 *
 * @package Vashnal
 */
class Assets {

    /**
     * Include necessary assets.
     */
    public static function init(): void {

        // Replace default jquery lib for a fresh one
        self::replace_jquery();

        // Include assets.
        add_action( 'wp_enqueue_scripts', array( new self(), 'include_assets' ) );
    }

    /**
     * Replace default jquery lib for a fresh one.
     */
    private static function replace_jquery(): void {
        if ( ! is_admin() ) {
            add_action( "wp_enqueue_scripts", function () {
                wp_deregister_script( 'jquery' );
                wp_register_script( 'jquery',
                    Theme::get_js_folder_url() . 'jquery' . Theme::min() . '.js',
                    false,
                    VASHNAL_JQUERY_VERSION,
                    true );
                wp_enqueue_script( 'jquery' );
            } );
        }
    }

    /**
     * Include necessary assets based on current page type.
     */
    public function include_assets(): void {

        // Include common assets.
        $this->include_page_assets( VASHNAL_COMMON_ASSETS );

        // Include page type based assets.
        if ( is_front_page() ) {
            $this->include_page_assets( VASHNAL_HOMEPAGE_ASSETS );
        }
        if ( is_admin_bar_showing() ) {
            $this->include_page_assets( VASHNAL_ADMIN_ASSETS );
        }
        if ( is_search() ) {
            $this->include_page_assets( VASHNAL_SEARCH_ASSETS );
        }
        if ( is_404() ) {
            $this->include_page_assets( VASHNAL_ERROR404_ASSETS );
        }
        if ( is_single() ) {
            $this->include_page_assets( VASHNAL_POST_ASSETS );
        }
        if ( is_category() ) {
            $this->include_page_assets( VASHNAL_CATEGORY_ASSETS );
        }

        // Dequeue Wordpress block library
        wp_dequeue_style( 'wp-block-library' );
    }

    /**
     * Include assets from slugs array.
     *
     * @param $assets array list of js and css slugs to include
     */
    public function include_page_assets( array $assets ) {
        if ( isset( $assets['css'] ) && ! empty( $assets['css'] ) ) {
            foreach ( $assets['css'] as $css ) {
                $this->include_css( $css );
            }
        }
        if ( isset( $assets['js'] ) && ! empty( $assets['js'] ) ) {
            foreach ( $assets['js'] as $js ) {
                $this->include_js( $js );
            }
        }
    }

    /**
     * Include css file by params.
     *
     * @param string[] $css Array with css file info: slug, deps array, version.
     */
    public function include_css( array $css ): void {
        wp_enqueue_style(
            VASHNAL_ASSETS_PREFIX . '-' . $css['slug'],
            Theme::get_css_folder_url() . $css['slug'] . Theme::min() . '.css',
            $css['deps'],
            $css['ver']
        );
    }

    /**
     * Include js file by params.
     *
     * @param string[] $js Array with js file info: slug, deps array, version.
     */
    public function include_js( array $js ): void {
        $handle = VASHNAL_ASSETS_PREFIX . '-' . $js['slug'];
        wp_enqueue_script(
            $handle,
            Theme::get_js_folder_url() . $js['slug'] . Theme::min() . '.js',
            $js['deps'],
            $js['ver'],
            true
        );
        if ( isset( $js['params'] ) && is_array( $js['params'] ) && ! empty( $js['params'] ) ) {
            $params = array();
            if ( isset( $js['params']['action'] ) && $js['params']['action'] ) {
                $params['url'] = Theme::get_ajax_handler_url();
            }
            if ( isset( $js['params']['add_script'] ) && $js['params']['add_script'] ) {
                $params['add_script'] = Theme::get_js_folder_url() . $js['params']['add_script'] . Theme::min() . '.js';
            }
            $params = array_merge( $js['params'], $params );
            wp_localize_script( $handle, $js['slug'] . '_params', $params );
        }
    }

    /**
     * Get inline css string by css file name.
     *
     * @param string $slug
     *
     * @return string
     */
    public static function get_inline_css( string $slug ): string {
        $css_file = Theme::get_css_folder_path() . $slug . Theme::min() . '.css';
        $css      = file_get_contents( $css_file );

        return '<style>' . $css . '</style>';
    }

}