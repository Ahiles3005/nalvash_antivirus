<?php

namespace Vashnal;

use WP_Query;
use WP_Widget;

/**
 * Custom widget for homepage categories.
 *
 * @package Vashnal
 */
class Homepage_Category_Widget extends WP_Widget {

    /**
     * Homepage_Category_Widget constructor.
     */
    function __construct() {
        parent::__construct(
            'vashnal_homepage_category_widget',
            __( 'Vashnal: homepage category', 'vashnal' ),
            array( 'description' => __( 'Homepage category listing', 'vashnal' ), )
        );
    }

    /**
     * Print widget options form.
     *
     * @param mixed $instance
     *
     * @return void
     */
    public function form( $instance ): void {
        $this->get_category_field( $instance['category'] ?? 0 );
        $this->get_amount_field( $instance['amount'] ?? 0 );
        $this->get_icon_field( $instance['icon'] ?? '' );
    }

    /**
     * Build category select field.
     *
     * @param int $current_value Current category ID
     */
    private function get_category_field( int $current_value ): void {
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'category' ); ?>">
                <?php echo __( 'Category:', 'vashnal' ); ?>
            </label>
            <select
                    class="widefat"
                    id="<?php echo $this->get_field_id( 'category' ); ?>"
                    name="<?php echo $this->get_field_name( 'category' ); ?>"
                    value="">
                <?php foreach ( VASHNAL_HOMEPAGE_CATEGORIES as $category_id ) {
                    $selected = ( $category_id === $current_value ) ? ' selected="selected"' : '';
                    ?>
                    <option value="<?php echo $category_id; ?>"<?php echo $selected; ?>>
                        <?php echo Content::get_term_name_by_id( $category_id ); ?>
                    </option>
                <?php } ?>
            </select>
        </p>
        <?php
    }

    /**
     * Build posts amount text field.
     *
     * @param int $current_value
     */
    private function get_amount_field( int $current_value ): void {
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'amount' ); ?>">
                <?php echo __( 'Posts amount:', 'vashnal' ); ?>
            </label>
            <input
                    class="widefat"
                    id="<?php echo $this->get_field_id( 'amount' ); ?>"
                    name="<?php echo $this->get_field_name( 'amount' ); ?>"
                    value="<?php echo ( $current_value ) ? $current_value : ''; ?>"
            />
        </p>
        <?php
    }

    /**
     * Build category icon select field.
     *
     * @param string $current_value
     */
    private function get_icon_field( string $current_value ): void {
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'icon' ); ?>">
                <?php echo __( 'Icon:', 'vashnal' ); ?>
            </label>
            <select
                    class="widefat"
                    id="<?php echo $this->get_field_id( 'icon' ); ?>"
                    name="<?php echo $this->get_field_name( 'icon' ); ?>"
                    value="">
                <?php foreach ( Content::get_icons_list() as $icon_id => $icon_name ) {
                    $selected = ( $icon_id === $current_value ) ? ' selected="selected"' : '';
                    ?>
                    <option value="<?php echo $icon_id; ?>"<?php echo $selected; ?>>
                        <?php echo $icon_name; ?>
                    </option>
                <?php } ?>
            </select>
        </p>
        <?php
    }

    /**
     * Save custom widget fields.
     *
     * @param mixed $new_instance
     * @param mixed $old_instance
     *
     * @return array
     */
    public function update( $new_instance, $old_instance ): array {
        $instance             = array();
        $instance['category'] = ( isset( $new_instance['category'] ) && $new_instance['category'] )
            ? (int) $new_instance['category'] : 0;
        $instance['amount']   = ( isset( $new_instance['amount'] ) && $new_instance['amount'] )
            ? (int) $new_instance['amount'] : 0;
        $instance['icon']     = ( isset( $new_instance['icon'] ) && $new_instance['icon'] )
            ? (string) $new_instance['icon'] : '';

        return $instance;
    }

    /**
     * Build widgets layout.
     *
     * @param mixed $args
     * @param mixed $instance
     */
    public function widget( $args, $instance ): void {
        global $vashnal_svg;
        $category  = get_term( $instance['category'] );
        $the_query = new WP_Query( array(
            'cat'            => $instance['category'],
            'posts_per_page' => $instance['amount']
        ) );
        if ( $the_query->have_posts() ) {
            ?>
            <div class="category__wrapper">
                <div class="category container<?php
                echo ( VASHNAL_STATEMENT_TERM_ID === $instance['category'] ) ? ' statement-homepage' : ''; ?>">
                    <div class="category__header">
                        <?php if ( $instance['icon'] ) { ?>
                            <div class="category__header__icon">
                                <?php $vashnal_svg->print_symbol( 'icon-' . $instance['icon'] ); ?>
                            </div>
                        <?php } ?>
                        <h2 class="category__header__caption caption container">
                            <?php echo Content::get_term_name_by_id( $instance['category'] ); ?>
                        </h2>
                        <a href="<?php echo get_term_link( $category ); ?>" class="category__header__link">
                            <?php echo get_field( 'all_posts_anchor', $category ); ?>&nbsp;&raquo;
                        </a>
                    </div>
                    <div class="category__list">
                        <?php while ( $the_query->have_posts() ) {
                            $the_query->the_post();
                            if ( Content::is_question( get_the_ID() ) ) {
                                get_template_part( 'template-parts/category/post', 'question' );
                            } elseif ( Content::is_statement( get_the_ID() ) ) {
                                get_template_part( 'template-parts/category/post', 'statement-homepage' );
                            } else {
                                get_template_part( 'template-parts/category/post', 'document' );
                            }
                        } ?>
                    </div>
                    <span data-href="<?php echo get_term_link( $category ); ?>"
                          class="category__link pseudo-link internal">
                        <?php echo get_field( 'all_posts_anchor', $category ); ?>&nbsp;&raquo;
                    </span>
                </div>
                <?php
                if ( $instance['category'] === VASHNAL_STATEMENT_TERM_ID ) {
                    $category  = get_term( VASHNAL_SERVICE_TERM_ID );
                    $the_query = new WP_Query( array(
                        'cat'            => VASHNAL_SERVICE_TERM_ID,
                        'posts_per_page' => VASHNAL_HOMEPAGE_SERVICE_AMOUNT
                    ) );
                    if ( $the_query->have_posts() ) {
                        ?>
                        <div class="category container service-homepage">
                            <div class="category__header">
                                <h2 class="category__header__caption caption container">
                                    <?php echo Content::get_term_name_by_id( VASHNAL_SERVICE_TERM_ID ); ?>
                                </h2>
                                <a href="<?php echo get_term_link( $category ); ?>" class="category__header__link">
                                    <?php echo get_field( 'all_posts_anchor', $category ); ?>&nbsp;&raquo;
                                </a>
                            </div>
                            <div class="category__list category__list--service-homepage">
                                <?php while ( $the_query->have_posts() ) {
                                    $the_query->the_post();
                                    get_template_part( 'template-parts/category/post', 'service-homepage' );
                                } ?>
                            </div>
                            <span data-href="<?php
                            echo get_term_link( $category ); ?>" class="category__link pseudo-link internal">
                            <?php echo get_field( 'all_posts_anchor', $category ); ?>&nbsp;&raquo;
                        </span>
                        </div>
                    <?php }
                } ?>
            </div>
            <?php
        }

    }

}