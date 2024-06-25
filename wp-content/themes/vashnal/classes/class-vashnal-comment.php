<?php

namespace Vashnal;

/**
 * Comment form logic.
 *
 * @package Vashnal
 */
class Comment {

    /**
     * Init comment form hooks.
     */
    public static function init(): void {
        $class = new self();

        // Show question form popup for ajax request.
        add_action( 'wp_ajax_get_comment_form', array( $class, 'get_form' ) );
        add_action( 'wp_ajax_nopriv_get_comment_form', array( $class, 'get_form' ) );

        // Save question as a new post and send email notification.
        add_action( 'wp_ajax_send_comment_form', array( $class, 'send_form' ) );
        add_action( 'wp_ajax_nopriv_send_comment_form', array( $class, 'send_form' ) );
    }

    /**
     * Show comment form for ajax request.
     */
    public static function get_form(): void {
        self::build_form();
        wp_die();
    }

    /**
     * Build comment form.
     */
    private static function build_form(): void {
        get_template_part( VASHNAL_COMMENT_TEMPLATE_PATH );
    }

    /**
     * Send notification about new comment to admin email.
     *
     * @param int $post_id
     * @param string $name
     * @param string $comment
     */
    private static function send_notification( int $post_id, string $name, string $comment ): void {
        $to            = get_option( 'admin_email' );
        $post          = get_post( $post_id );
        $post_link     = '<a href="' . get_permalink( $post ) . '">' . $post->post_title . '</a>';
        $email_subject = __( VASHNAL_NEW_COMMENT_NOTIFICATION_SUBJECT, VASHNAL_TEXT_DOMAIN );
        $message       = '<b>' . __( 'Page', VASHNAL_TEXT_DOMAIN ) . ': </b>' . $post_link . '<br>';
        $message       .= '<b>' . __( 'Name', VASHNAL_TEXT_DOMAIN ) . ': </b>' . $name . '<br>';
        $message       .= '<b>' . __( 'Comment', VASHNAL_TEXT_DOMAIN ) . ': </b>' . $comment . '<br>';
        $headers       = array( 'Content-Type: text/html; charset=UTF-8' );
        wp_mail( $to, $email_subject, $message, $headers );
    }

    /**
     * Save comment and send email notification.
     */
    public static function send_form(): void {
            $comment_id = filter_input( INPUT_POST, 'comment_id' );
            $name       = filter_input( INPUT_POST, 'name' );
            $comment    = filter_input( INPUT_POST, 'comment' );
            $post_id    = filter_input( INPUT_POST, 'post_id' );

            if ( is_int( self::save_comment( $comment_id, $post_id, $name, $comment ) ) ) {
                self::send_notification( $post_id, $name, $comment );
                wp_send_json_success();
            } else {
                wp_send_json_error( array( 'message' => __( 'Something is wrong...', VASHNAL_TEXT_DOMAIN ) ) );
            }
        wp_die();
    }

    /**
     * Save new comment.
     *
     * @param int $post_id
     * @param int $comment_id
     * @param string $name
     * @param string $comment
     *
     * @return int
     */
    private static function save_comment( int $comment_id, int $post_id, string $name, string $comment ): int {
        $comment_data = array(
            'comment_agent'     => $_SERVER['HTTP_USER_AGENT'],
            'comment_approved'  => '1',
            'comment_author'    => $name,
            'comment_author_IP' => $_SERVER['REMOTE_ADDR'],
            'comment_content'   => $comment,
            'comment_parent'    => $comment_id,
            'comment_post_ID'   => $post_id
        );

        return wp_insert_comment( $comment_data );
    }

}