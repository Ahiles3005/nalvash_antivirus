<?php

namespace Vashnal;

/**
 * SVG icons spritemap handler.
 *
 * @package Vashnal
 */
class Svg {

    /**
     * Spritemap file contents.
     *
     * @var string|false
     */
    var string $spritemap;

    /**
     * Svg constructor.
     */
    function __construct() {
        $this->spritemap = $this->get_spritemap();
    }

    /**
     * Get spritemap file contents.
     *
     * @return false|string
     */
    private function get_spritemap(): string {
        return file_get_contents( Theme::get_svg_spritemap_filename() );
    }

    /**
     * Get SVG symbol from spritemap and print it.
     *
     * @param $id
     * @param string $class
     * @param string $fill
     */
    public function print_symbol( string $id, string $class = '', string $fill = '' ): void {
        echo $this->get_symbol( $id, $class, $fill );
    }

    /**
     * Get symbol from spritemap file by ID.
     *
     * @param string $id
     * @param string $class
     * @param string $fill
     *
     * @return string
     */
    public function get_symbol( string $id, string $class = '', string $fill = '' ): string {
        $matches = array();
        preg_match(
            "#<\s*?symbol\b id=\"" . $id . "\" viewBox=\"([^\"]*)\"[^>]*>(?!symbol)(.*)</symbol\b[^>]*>#Uis",
            $this->spritemap,
            $matches
        );
        if ( isset( $matches[1] ) && $matches[1] && isset( $matches[2] ) && $matches[2] ) {
            return sprintf( VASHNAL_SVG_PATTERN, $matches[1], $class, $fill, $matches[2] );
        } else {
            return '';
        }
    }

}