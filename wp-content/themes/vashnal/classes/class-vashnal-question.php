<?php

namespace Vashnal;

/**
 * Question form logic.
 *
 * @package Vashnal
 */
class Question {

    /**
     * Init question form hooks.
     */
    public static function init(): void {
        $class = new self();

        // Show question form popup for ajax request.
        add_action( 'wp_ajax_get_question_form', array( $class, 'get_form' ) );
        add_action( 'wp_ajax_nopriv_get_question_form', array( $class, 'get_form' ) );

        // Show question success popup for ajax request.
        add_action( 'wp_ajax_get_question_success', array( $class, 'get_success' ) );
        add_action( 'wp_ajax_nopriv_get_question_success', array( $class, 'get_success' ) );

        // Save question as a new post and send email notification.
        add_action( 'wp_ajax_send_question_form', array( $class, 'send_form' ) );
        add_action( 'wp_ajax_nopriv_send_question_form', array( $class, 'send_form' ) );
    }

    /**
     * Show question form popup for ajax request.
     */
    public static function get_form(): void {
        self::build_form();
        wp_die();
    }

    /**
     * Build popup with question form.
     */
    private static function build_form(): void {
        $template = Assets::get_inline_css( VASHNAL_QUESTION_CSS );
        $template .= self::get_form_template();
        self::build_popup( $template );
    }

    /**
     * Get question form template file.
     *
     * @return string
     */
    private static function get_form_template(): string {
        ob_start();
        get_template_part( VASHNAL_FORM_TEMPLATE_PATH );
        $question_form_html = ob_get_contents();
        ob_end_clean();

        return $question_form_html;
    }

    /**
     * Show question success popup for ajax request.
     */
    public static function get_success(): void {
        self::build_success_popup();
        wp_die();
    }

    /**
     * Build popup with success block.
     */
    private static function build_success_popup(): void {
        $template = Assets::get_inline_css( VASHNAL_QUESTION_SUCCESS_CSS );
        $template .= self::get_form_success_message();
        self::build_popup( $template );
    }

    /**
     * Get question success template file.
     *
     * @return string
     */
    private static function get_form_success_message(): string {
        ob_start();
        get_template_part( VASHNAL_SUCCESS_TEMPLATE_PATH );
        $question_form_html = ob_get_contents();
        ob_end_clean();

        return $question_form_html;
    }

    /**
     * Send notification about new question to admin email.
     *
     * @param int $category_id
     * @param string $name
     * @param string $subject
     * @param string $question
     */
    private static function send_notification(
        int $category_id, string $name,
        string $subject, string $question
    ): void {
        $to            = get_option( 'admin_email' );
        $category      = Content::get_term_name_by_id( $category_id );
        $email_subject = __( VASHNAL_NEW_QUESTION_NOTIFICATION_SUBJECT, VASHNAL_TEXT_DOMAIN );
        $message       = '<b>' . __( 'Category', VASHNAL_TEXT_DOMAIN ) . ': </b>' . $category . '<br>';
        $message       .= '<b>' . __( 'Name', VASHNAL_TEXT_DOMAIN ) . ': </b>' . $name . '<br>';
        $message       .= '<b>' . __( 'Subject', VASHNAL_TEXT_DOMAIN ) . ': </b>' . $subject . '<br>';
        $message       .= '<b>' . __( 'Question', VASHNAL_TEXT_DOMAIN ) . ': </b>' . $question . '<br>';
        $headers       = array( 'Content-Type: text/html; charset=UTF-8' );
        wp_mail( $to, $email_subject, $message, $headers );
    }

    /**
     * Save question as a new post and send email notification.
     */
    public static function send_form(): void {
        $secret_source = filter_input( INPUT_POST, 'secret_source' );
        $secret        = filter_input( INPUT_POST, 'secret' );
        if ( md5( $secret_source ) === $secret ) {
            $category_id = filter_input( INPUT_POST, 'category' );
            $name        = filter_input( INPUT_POST, 'name' );
            $subject     = filter_input( INPUT_POST, 'subject' );
            $question    = filter_input( INPUT_POST, 'question' );
            if ( is_int( self::save_question( $category_id, $name, $subject, $question ) ) ) {
                self::send_notification( $category_id, $name, $subject, $question );
                wp_send_json_success();
            } else {
                wp_send_json_error( array( 'message' => __( 'Something is wrong...', 'vashnal' ) ) );
            }
        } else {
            wp_send_json_error( array( 'message' => __( 'It looks like you are a bot!', 'vashnal' ) ) );
        }
        wp_die();
    }

    /**
     * Save new question as a post.
     *
     * @param int $category_id
     * @param string $name
     * @param string $subject
     * @param string $question
     *
     * @return int
     */
    private static function save_question( int $category_id, string $name, string $subject, string $question ): int {
        $post_args = array(
            'post_title'    => wp_strip_all_tags( $subject ),
            'post_content'  => wp_strip_all_tags( $question ),
            'post_status'   => 'publish',
            'post_author'   => VASHNAL_ASKER_USER_ID,
            'post_category' => array( $category_id )
        );
        $post_id   = wp_insert_post( $post_args );
        update_field( 'name', wp_strip_all_tags( $name ), $post_id );

        return $post_id;
    }

    /**
     * Build popup.
     *
     * @param string $content
     * @param bool $ajax_load
     */
    public static function build_popup( string $content, bool $ajax_load = true ): void {
        $args = array( 'content' => $content );
        if ( $ajax_load ) {
            $args['css'] = Assets::get_inline_css( VASHNAL_POPUP_CSS );
        }
        get_template_part( VASHNAL_POPUP_TEMPLATE_PATH, '', $args );
    }

}