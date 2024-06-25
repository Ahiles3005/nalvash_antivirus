<?php

namespace Vashnal;

use WP;
use WP_Post;
use WP_Query;
use WP_Term;

/**
 * Common content parsing static functions.
 *
 * @package Vashnal
 */
class Content {

    public static function add_hooks() {
        $class = new self();

        // Change query for getting categories by non-unique slug.
        add_action( 'parse_request', array( $class, 'fix_subcategory_link' ) );

        // Sort posts by custom order value instead of date.
        add_action( 'pre_get_posts', array( $class, 'reorder_posts' ), 10 );

        // Change category title. Delete sections and rename subcategories.
        add_filter( 'get_the_archive_title', array( $class, 'change_category_title' ) );

        // Rewrite category link for subcategories with non-unique slug.
        add_filter( 'term_link', array( $class, 'rewrite_term_link' ), 10, 2 );

        // Rewrite post permalinks - remove 0-level parent slug from URL.
        add_filter( 'post_link', array( $class, 'rewrite_post_link' ), 10, 2 );

        //Add custom hook for modifying post content html classes.
        add_filter( 'content_class', array( $class, 'modify_content_class' ), 10 );

        // Disable default lazy load and prepare images and iframes for a custom one.
        add_filter( 'the_content', array( $class, 'add_lazyload' ) );
        add_filter( 'vashnal_answer', array( $class, 'add_lazyload' ) );
        add_filter( 'wp_lazy_loading_enabled', '__return_false', 10 );

        // Add links to content images for new tab opening
        add_filter( 'the_content', array( $class, 'add_images_links' ), 10, 1 );
        add_filter( 'vashnal_answer', array( $class, 'add_images_links' ), 10, 1 );

        // Add to post menu based on h2-h6 headers.
        add_filter( 'the_content', array( $class, 'add_post_menu' ) );

        // Format tables.
        add_filter( 'the_content', array( $class, 'format_tables' ) );
        add_filter( 'vashnal_answer', array( $class, 'format_tables' ) );

        // Fix internal links.
        add_filter( 'the_content', array( $class, 'fix_internal_links' ) );
        add_filter( 'vashnal_answer', array( $class, 'fix_internal_links' ) );

        // Fix internal links.
        add_filter( 'the_content', array( $class, 'add_file_type_icons' ) );
        add_filter( 'vashnal_answer', array( $class, 'add_file_type_icons' ) );

        // Remove old signature
        add_filter( 'vashnal_answer', array( $class, 'remove_signature' ) );

        // Fix search page breadcrumbs links.
        add_filter( 'wpseo_breadcrumb_links', array( $class, 'fix_search_page_breadcrumbs' ) );

        // Add checkbox for rebuilding slug from title
        add_action( 'edit_form_after_title', array( $class, 'add_slug_refresh_checkbox' ) );

        // Update slug from title
        add_filter( 'wp_insert_post_data', array( $class, 'update_post_slug' ), 20, 2 );
    }

    /**
     * Update slug from title.
     *
     * @param array $data
     * @param array $post_array
     *
     * @return array
     */
    public static function update_post_slug( array $data, array $post_array ): array {
        if ( $post_array['update_slug'] === 'yes' ) {
            $data['post_name'] = sanitize_title( $data['post_title'] );
        }

        return $data;
    }

    /**
     * Add checkbox for rebuilding slug from title.
     */
    public static function add_slug_refresh_checkbox(): void {
        ?>
        <div>
            <div class="inside">
                <label>
                    <input value="yes" type="checkbox" name="update_slug">
                    <?php echo __( 'Update slug', VASHNAL_TEXT_DOMAIN ); ?>
                </label>
            </div>
        </div>
        <?php
    }

    /**
     * Change category title. Delete sections and rename subcategories.
     *
     * @param string $title
     *
     * @return string
     */
    public static function change_category_title( string $title ): string {
        if ( is_category() ) {
            $title = single_cat_title( '', false );
        }
        $term = get_queried_object();
        if ( $term->parent ) {
            $parent = get_term( $term->parent );
            $title  = $parent->name;
        }

        return $title;
    }

    /**
     * Change query for getting categories by non-unique slug.
     *
     * @param WP $query
     *
     * @return WP
     */
    public static function fix_subcategory_link( WP $query ): WP {
        if ( isset( $query->query_vars['name'] ) && $query->query_vars['name']
             && isset( $query->query_vars['category_name'] ) && $query->query_vars['category_name']
             && ( ! isset( $query->query_vars['page'] ) || ! $query->query_vars['page'] ) ) {
            if ( $parent = get_term_by( 'slug', $query->query_vars['category_name'], 'category' ) ) {
                $real_category_slug = $query->query_vars['name'] . '-' . $parent->slug;
                if ( $category = get_term_by( 'slug', $real_category_slug, 'category' ) ) {
                    unset( $query->query_vars['name'] );
                    unset( $query->query_vars['page'] );
                    $query->query_vars['category_name'] = $parent->slug . '/' . $category->slug;
                }
            }
        }

        return $query;
    }

    /**
     * Rewrite children categories permalinks - remove parent slug
     *
     * @param $permalink string
     * @param $term WP_Term
     *
     * @return string
     */
    public static function rewrite_term_link( string $permalink, WP_Term $term ): string {
        if ( $term->parent ) {
            $parent = get_term( $term->parent );

            return preg_replace( "#-{$parent->slug}$#", '', $permalink );
        }

        return $permalink;
    }

    /**
     * Rewrite post permalinks - remove 0-level parent slug from URL.
     *
     * @param $permalink string
     * @param $post mixed
     *
     * @return string
     */
    public static function rewrite_post_link( string $permalink, $post ): string {
        $categories = wp_get_post_categories( $post->ID );
        if ( is_array( $categories ) && ! empty( $categories ) && isset( $categories[0] ) ) {
            $category_id = $categories[0];
            $term        = get_term( $category_id );
            if ( $term->parent ) {
                $parent = get_term( $term->parent );

                return preg_replace( "#-{$parent->slug}/#", '/', $permalink );
            }
        }

        return $permalink;
    }

    /**
     * Sort posts by custom order value instead of date.
     *
     * @param $query
     *
     * @return mixed
     */
    public static function reorder_posts( WP_Query $query ): WP_Query {
        if ( ! is_admin() && self::use_custom_sort( $query ) ) {
            $query->set( 'orderby', 'meta_value' );
            $query->set( 'meta_key', 'order' );
            $query->set( 'order', 'ASC' );
        }

        return $query;
    }

    /**
     * Add custom hook for modifying post content html classes.
     *
     * @param $classes
     *
     * @return string
     */
    public static function modify_content_class( string $classes ): string {
        if ( self::content_has_menu() || self::is_question( get_the_ID() ) ) {
            $classes .= ' has-menu';
        }

        return $classes;
    }

    /**
     * Prepare images for lazyload.
     *
     * @param string $content
     *
     * @return string
     */
    public static function add_lazyload( string $content ): string {
        $content = preg_replace( "#<img([^>]*)src=#", "<img$1 data-src=", $content );
        $content = preg_replace( "#<img([^>]*)srcset=#", "<img$1 data-srcset=", $content );
        $content = preg_replace( "#<img([^>]*)sizes=#", "<img$1 data-sizes=", $content );
        $content = str_replace( '<img ', '<img src="' . Theme::get_empty_image_url() . '" ', $content );

        $content = str_replace( " frameborder=\"0\"", '', $content );
        $content = preg_replace( "#<iframe([^>]*) src=#", "<iframe$1 data-src=", $content );

        return $content;
    }

    /**
     *
     *
     * @param string $content
     *
     * @return string
     */
    public static function add_images_links( string $content ): string {
        $content = preg_replace( "#(<img[^>]*data-src=\"([^\"]*)\"[^>]*>)#",
            "<a class=\"image\" href=\"$2'\" target=\"_blank\">$1</a>", $content );

        return $content;
    }

    /**
     * Add to post menu based on h2-h6 headers.
     *
     * @param string $content
     *
     * @return string
     */
    public static function add_post_menu( string $content ): string {
        if ( $menu = self::build_post_menu( $content ) ) {
            if ( ! preg_match( "#\[menu]#", $content ) ) {
                $content = preg_replace( "#</p>#", "</p>" . $menu, $content, 1 );
            } else {
                $content = str_replace( '[menu]', $menu, $content );
            }
        } else {
            $content = str_replace( '[menu]', '', $content );
        }

        return $content;
    }

    /**
     * Format tables.
     *
     * @param string $content
     *
     * @return string
     */
    public static function format_tables( string $content ): string {
        $content = preg_replace( "#<tr([^>]*)>#", "<tr>", $content );
        $content = preg_replace( "#<table([^>]*)>#", "<table>", $content );
        $content = preg_replace( "#<td([^>]*)>#", "<td>", $content );
        $content = str_replace( '<table>', '<div class="table-wrapper"><table>', $content );
        $content = str_replace( '</table>', '</table></div>', $content );
        $content = str_replace( '<iframe', '<div class="table-wrapper"><iframe', $content );
        $content = str_replace( '</iframe>', '</iframe></div>', $content );

        return $content;
    }

    /**
     * Fix internal links.
     *
     * @param string $content
     *
     * @return string
     */
    public static function fix_internal_links( string $content ): string {

        // Change all vashnal domain variations to the current one
        $search  = "#href=\"[^\"]+\/\/[^\"]*" . VASHNAL_DOMAIN_PLOT . "[^\/]*\/#";
        $replace = 'href="' . get_site_url() . '/';
        $content = preg_replace( $search, $replace, $content );

        // Remove rel for all internal links
        $search  = "#<a([^>]*)href=\"([^\"]*)" . VASHNAL_DOMAIN_PLOT . "([^\"]*)\"([^>]*) rel=\"[^\"]*\"([^>]*)>#";
        $replace = "<a$1href=\"$2" . VASHNAL_DOMAIN_PLOT . "$3\"$4 $5>";
        $content = preg_replace( $search, $replace, $content );

        return $content;
    }

    /**
     * Add file type icons for file links.
     *
     * @param string $content
     *
     * @return string
     */
    public static function add_file_type_icons( string $content ): string {
        $matches = array();
        preg_match_all( "#<a[^>]*href=\"[^\"]*(doc|docx|xls|xlsx|pdf|tif|tiff)\"[^>]*>[^<]*</a>#uis",
            $content, $matches );
        $matches[0] = array_unique( $matches[0] );
        foreach ( $matches[0] as $index => $file ) {
            $link     = $file;
            $type     = $matches[1][ $index ];
            $icon     = Theme::get_file_type_icon( $type );
            $new_link = str_replace( '<a', '<a class="file-link ' . $type . '" ', $link );
            $new_link = str_replace( '">', '">' . $icon, $new_link );
            $content  = str_replace( $link, $new_link, $content );
        }

        return $content;
    }

    /**
     * Remove old signature
     *
     * @param string $answer
     *
     * @return string
     */
    public static function remove_signature( string $answer ): string {
        if ( Content::is_question( get_the_ID() ) ) {
            $answer = preg_replace( "#" . VASHNAL_OLD_SIGNATURE . "#ui", '', $answer );
        }

        return $answer;
    }

    /**
     * Build post menu basing on h2-h6.
     *
     * @param string $content
     *
     * @return string
     */
    public static function build_post_menu( string &$content ): string {
        $caption_matches = array();
        preg_match_all( "#<h(\d)[^>]*>(.*)</h\d>#", $content, $caption_matches );
        if ( isset( $caption_matches[2] ) && ! empty( $caption_matches[2] ) ) {
            $menu = '';
            foreach ( $caption_matches[0] as $counter => $caption ) {
                $caption_new = preg_replace(
                    "#<h(\d)([^>]*)>#",
                    '<h$1$2 data-anchor="' . ( $counter ) . '">',
                    $caption
                );
                $content     = str_replace( $caption, $caption_new, $content );
                $level_class = ( $caption_matches[1][ $counter ] >= 3 ) ? 'class="level level--'
                                                                          . ( $caption_matches[1][ $counter ] ) . '"' : '';
                $menu        .= '<li data-link="' . $counter . '" ' . $level_class . '>'
                                . $caption_matches[2][ $counter ] . '</li>';
            }

            return '<div class="post__menu">'
                   . '<div class="post__menu__caption">' . __( 'Table of contents', 'vashnal' ) . '</div>'
                   . '<ul class="post__menu__list">' . $menu . '</ul>'
                   . '</div>';
        } else {
            return '';
        }
    }

    /**
     * Get post preview text.
     *
     * @param int $post_id
     * @param int $text_length
     *
     * @return string
     */
    public static function get_preview_text(
        int $post_id,
        int $text_length = VASHNAL_DEFAULT_PREVIEW_TEXT_LENGTH
    ): string {
        if ( $preview_text = get_field( 'preview_text', $post_id ) ) {
            return $preview_text;
        } else {
            return self::get_cut_text( get_post_field( 'post_content', $post_id ), $text_length );
        }
    }

    /**
     * Check whether post with particular ID has icons.
     *
     * @param int $post_id
     *
     * @return bool
     */
    public static function post_icon_exists( int $post_id ): bool {
        return get_post_field( 'icon', $post_id ) ? true : false;
    }

    /**
     * Get post icon.
     *
     * @param int $post_id
     *
     * @return string
     */
    public static function get_post_icon( int $post_id ): string {
        return VASHNAL_ICON_PREFIX . get_post_field( 'icon', $post_id );
    }

    /**
     * Cut text by N symbols.
     *
     * @param $text
     * @param int $length
     * @param string $postfix
     *
     * @return string
     */
    public static function get_cut_text( string $text, int $length, string $postfix = '...' ): string {
        $text = strip_tags( $text );
        if ( mb_strlen( $text ) <= $length ) {
            return $text;
        }
        $tmp = mb_substr( $text, 0, $length );

        return mb_substr( $tmp, 0, mb_strripos( $tmp, ' ' ) ) . $postfix;
    }

    /**
     * Get children terms.
     *
     * @param int $term_id
     *
     * @return WP_Term[]
     */
    public static function get_children_terms( int $term_id ): array {
        $terms_array = array();
        $terms_ids   = get_term_children( $term_id, 'category' );
        foreach ( $terms_ids as $term_id ) {
            $terms_array[] = get_term( $term_id );
        }

        return $terms_array;
    }

    /**
     * Get list of children QA categories as a list of select options
     *
     * @return string
     */
    public static function get_qa_terms_options_list(): string {
        $options = '';
        $terms   = self::get_children_terms( VASHNAL_QA_TERM_ID );
        foreach ( $terms as $term ) {
            $options .= '<option value="' . $term->term_id . '">' . $term->name . '</option>';
        }

        return $options;
    }

    /**
     * Get category template part name based on current category id.
     *
     * @param int $term_id
     *
     * @return string Template part name
     */
    public static function get_current_term_template( int $term_id = 0 ): string {
        if ( $term_id !== 0 ) {
            $term = get_term( $term_id );
        } else {
            $term = get_queried_object();
        }
        if ( get_class( $term ) === 'WP_Term' ) {
            if ( self::is_child_term( $term->term_id ) ) {
                $category_template_id = $term->parent;
            } else {
                $category_template_id = $term->term_id;
            }
            if ( isset( VASHNAL_CATEGORIES_TEMPLATES[ $category_template_id ] )
                 && strlen( VASHNAL_CATEGORIES_TEMPLATES[ $category_template_id ] ) ) {
                return VASHNAL_CATEGORIES_TEMPLATES[ $category_template_id ];
            }
        }

        return '';
    }

    /**
     * Check if provided category is a child.
     *
     * @param int $term_id
     *
     * @return bool Current category is a child
     */
    public static function is_child_term( int $term_id = 0 ): bool {
        if ( $term_id !== 0 ) {
            $term = get_term( $term_id );
        } else {
            $term = get_queried_object();
        }
        if ( get_class( $term ) === 'WP_Term' && property_exists( $term, 'parent' ) && $term->parent !== 0 ) {
            return true;
        }

        return false;
    }

    /**
     * Check if category with provided term_id is current.
     *
     * @param $term_id int Category ID
     *
     * @return bool
     */
    public static function is_current_category( int $term_id ): bool {
        $queried_object = get_queried_object();
        if ( get_class( $queried_object ) === 'WP_Term' && $queried_object->term_id === $term_id ) {
            return true;
        }

        return false;
    }

    /**
     * Check if question has an answer.
     *
     * @param int $question_id
     *
     * @return bool
     */
    public static function question_has_answer( int $question_id ): bool {
        $answer = get_field( 'answer', $question_id );
        if ( gettype( $answer ) === 'string' && strlen( $answer ) ) {
            return true;
        }

        return false;
    }

    /**
     * Get term name by ID
     *
     * @param int $term_id
     *
     * @return string
     */
    public static function get_term_name_by_id( int $term_id ): string {
        $term = get_term( $term_id );

        return $term->name;
    }

    /**
     * Get icons list from ACF icon field.
     *
     * @return array
     */
    public static function get_icons_list(): array {
        global $wpdb;
        $query      = "SELECT `post_content` 
                  FROM `{$wpdb->prefix}posts` 
                  WHERE `post_type` = 'acf-field' AND `post_excerpt` = '" . VASHNAL_ICON_FIELD_NAME . "'";
        $field_data = unserialize( $wpdb->get_var( $query ) );

        return ( isset( $field_data['choices'] ) && is_array( $field_data['choices'] ) )
            ? $field_data['choices'] : array();
    }

    /**
     * Check whether current category has children terms.
     *
     * @return bool
     */
    public static function has_children_terms(): bool {
        $queried_object = get_queried_object();
        if ( get_class( $queried_object ) === 'WP_Term'
             && ! empty( self::get_children_terms( $queried_object->term_id ) ) ) {
            return true;
        }

        return false;
    }

    /**
     * Check if current post is a question.
     *
     * @param int $post_id
     *
     * @return bool
     */
    public static function is_question( int $post_id ): bool {
        $categories = get_the_terms( $post_id, 'category' );
        foreach ( $categories as $category ) {
            if ( $category->term_id === VASHNAL_QA_TERM_ID || $category->parent === VASHNAL_QA_TERM_ID ) {
                return true;
            }
        }

        return false;
    }


    /**
     * Check if current post is a statement.
     *
     * @param int $post_id
     *
     * @return bool
     */
    public static function is_statement( int $post_id ): bool {
        $categories = get_the_terms( $post_id, 'category' );
        foreach ( $categories as $category ) {
            if ( $category->term_id === VASHNAL_STATEMENT_TERM_ID || $category->parent === VASHNAL_STATEMENT_TERM_ID ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if current single post has h2-h6 tags for building menu
     *
     * @return bool
     */
    private static function content_has_menu(): bool {
        preg_match_all( "#<h(\d)[^>]*>(.*)</h\d>#", get_the_content(), $caption_matches );
        if ( isset( $caption_matches[2] ) && ! empty( $caption_matches[2] ) ) {
            return true;
        }

        return false;
    }

    /**
     * Check whether custom sort by number used instead of date sorting.
     *
     * @param WP_Query $query
     *
     * @return bool
     */
    private static function use_custom_sort( WP_Query $query ): bool {
        if ( isset( $query->query_vars['category_name'] ) ) {
            if ( $category_slug = $query->query_vars['category_name'] ) {
                if ( $term = get_category_by_slug( $category_slug ) ) {
                    return get_field( 'custom_order', $term->taxonomy . '_' . $term->term_id );
                }
            }
        }

        return false;
    }

    /**
     * Add <b> tag around phrase in text.
     *
     * @param string $content
     * @param string $search_phrase
     *
     * @return string
     */
    public static function highlight_search_phrases( string $content, string $search_phrase ): string {
        return preg_replace( "#(" . $search_phrase . ")#ui", "<b>$1</b>", $content );
    }

    /**
     * Fix search page breadcrumbs links.
     *
     * @param array $links
     *
     * @return array
     */
    public static function fix_search_page_breadcrumbs( array $links ): array {
        if ( is_search() ) {
            $links[1]['url'] = get_site_url() . '/?s=' . filter_input( INPUT_GET, 's' );
        }

        return $links;
    }

}