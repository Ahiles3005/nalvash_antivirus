<?php

define( 'VASHNAL_TEXT_DOMAIN', 'vashnal' );
define( 'VASHNAL_DOMAIN_PLOT', 'vashnal' );

define( 'VASHNAL_NO_FEED_MESSAGE', 'No feed available!' );

define( 'VASHNAL_CSS_PATH', '/assets/css/' );
define( 'VASHNAL_FONTS_PATH', '/assets/fonts/' );
define( 'VASHNAL_IMG_PATH', '/assets/img/' );
define( 'VASHNAL_JS_PATH', '/assets/js/' );
define( 'VASHNAL_SVG_SPRITEMAP_NAME', 'spritemap' );
define( 'VASHNAL_EMPTY_IMAGE_NAME', 'empty.png' );

define( 'VASHNAL_FONT_NAME', 'opensans' );
define( 'VASHNAL_ASSETS_PREFIX', 'vashnal' );
define( 'VASHNAL_POPUP_CSS', 'popup' );
define( 'VASHNAL_QUESTION_CSS', 'question' );
define( 'VASHNAL_QUESTION_SUCCESS_CSS', 'question-success' );

define( 'VASHNAL_NEW_QUESTION_NOTIFICATION_SUBJECT', 'Vashnal service: New question was added.' );
define( 'VASHNAL_NEW_COMMENT_NOTIFICATION_SUBJECT', 'Vashnal service: New comment was added.' );

define( 'VASHNAL_ICON_PREFIX', 'icon-' );
define( 'VASHNAL_ICON_FIELD_NAME', 'icon' );

define( 'VASHNAL_RECAPTCHA_URL', 'https://www.google.com/recaptcha/api.js' );
define( 'VASHNAL_RECAPTCHA_KEY', '6LfGwwcaAAAAADaVMrdLKZWxJtmZ7kthGR54b4qN' );
define( 'VASHNAL_RECAPTCHA_KEY_SECRET', '6LfGwwcaAAAAAOEs1EK3xe4Uhop8i6wq5dS0pC3g' );

define( 'VASHNAL_FILE_ICON_ID', array(
    'doc' => 565,
    'docx' => 565,
    'xls' => 477,
    'xlsx' => 477,
    'pdf' => 475,
    'tiff' => 476,
    'tif' => 476
) );

define( 'VASHNAL_HOMEPAGE_SERVICE_AMOUNT', 9 );

define( 'VASHNAL_OLD_SIGNATURE', "<p>Портал[^<]+Ваши налоги[^<]*</p>");

define( 'VASHNAL_DEFAULT_PREVIEW_TEXT_LENGTH', 100 );
define( 'VASHNAL_QA_TERM_ID', 3 );
define( 'VASHNAL_STATEMENT_TERM_ID', 4 );
define( 'VASHNAL_SERVICE_TERM_ID', 7 );
define( 'VASHNAL_ASKER_USER_ID', 2 );
define( 'VASHNAL_FORM_TEMPLATE_PATH', '/template-parts/form/question' );
define( 'VASHNAL_COMMENT_TEMPLATE_PATH', '/template-parts/form/comment' );
define( 'VASHNAL_SUCCESS_TEMPLATE_PATH', '/template-parts/form/question-success' );
define( 'VASHNAL_POPUP_TEMPLATE_PATH', '/template-parts/popup' );
define( 'VASHNAL_SVG_PATTERN', '<svg viewBox="%s" class="%s" fill="%s">%s</svg>' );
define( 'VASHNAL_JQUERY_VERSION', '3.5.1' );

define( 'VASHNAL_CATEGORIES_TEMPLATES', array(
    3 => 'question',
    5 => 'document',
    7 => 'service',
    4 => 'statement',
    2 => 'clarification'
) );
define( 'VASHNAL_HOMEPAGE_CATEGORIES', array(
    3,
    5,
    4,
    2
) );

define( 'VASHNAL_MENUS', array (
    'header_menu' => 'Main menu',
    'footer_menu' => 'Footer',
) );

define( 'VASHNAL_CUSTOM_WIDGETS', array(
    'Vashnal\Homepage_Category_Widget',
    'Vashnal\HTML_Widget',
) );
define( 'VASHNAL_WIDGETS_PLACES', array (
    'header_banner' => array(
        'name' => 'Header banner'
    ),
    'popup_question_success' => array(
        'name' => 'Popup question success',
        'before_title' => '<div class="caption">',
        'after_title' => '</div>'
    ),
    'footer_text' => array(
        'name' => 'Footer text'
    ),
    'footer_copyright' => array(
        'name' => 'Footer copyright'
    ),
    'homepage_category' => array(
        'name' => 'Homepage categories'
    ),
    '404_error' => array(
        'name' => '404 error message',
        'before_title' => '<p class="error-404__text__intro">',
        'after_title' => '</p>'
    ),
    'header_links' => array(
        'name' => 'Header links'
    ),
    'before_head' => array(
        'name' => 'Before </head>'
    ),
    'after_open_body' => array(
        'name' => 'Before <body>'
    ),
    'before_body_end' => array(
        'name' => 'Before </body>'
    ),	
    'answer_signature' => array(
        'name' => 'Answer signature'
    ),

) );

define( 'VASHNAL_COMMON_ASSETS', array (
    'css' => array (
        array ( 'slug' => 'fonts', 'deps' => array(), 'ver' => '1.0.0' ),
        array ( 'slug' => 'global', 'deps' => array(), 'ver' => '1.0.0' ),
        array ( 'slug' => 'header', 'deps' => array(), 'ver' => '1.0.0' ),
        array ( 'slug' => 'breadcrumbs', 'deps' => array(), 'ver' => '1.0.0' ),
        array ( 'slug' => 'footer', 'deps' => array(), 'ver' => '1.0.0' )
    ),
    'js' => array (
        array ( 'slug' => 'lazyload', 'deps' => array( 'jquery' ), 'ver' => '12.4' ),
        array ( 'slug' => 'menu', 'deps' => array( 'jquery' ), 'ver' => '1.0.0' ),
        array ( 'slug' => 'pseudo-links', 'deps' => array( 'jquery' ), 'ver' => '1.0.0' ),
        array ( 'slug' => 'popup', 'deps' => array( 'jquery' ), 'ver' => '1.0.0' ),
        array ( 'slug' => 'question', 'deps' => array( 'jquery' ), 'ver' => '1.0.0',
                'params' => array(
                    'action' => 'get_question_form',
                    'send_action' => 'send_question_form',
                    'success_action' => 'get_question_success',
                    'add_script' => 'md5'
                )
        )
    )
) );
define( 'VASHNAL_HOMEPAGE_ASSETS', array (
    'css' => array (
        array ( 'slug' => 'category', 'deps' => array(), 'ver' => '1.0.0' )
    )
) );
define( 'VASHNAL_POST_ASSETS', array (
    'css' => array(
        array ( 'slug' => 'post', 'deps' => array(), 'ver' => '1.0.0' ),
        array ( 'slug' => 'comment', 'deps' => array(), 'ver' => '1.0.0' )
    ),
    'js' => array (
        array ( 'slug' => 'post-menu', 'deps' => array( 'jquery' ), 'ver' => '1.0.0' ),
        array ( 'slug' => 'comment', 'deps' => array( 'jquery' ), 'ver' => '1.0.0',
                'params' => array(
                    'action' => 'get_comment_form',
                    'send_action' => 'send_comment_form',
                    'add_script' => 'md5',
                    'recaptcha_url' => VASHNAL_RECAPTCHA_URL,
                    'recaptcha_key' => VASHNAL_RECAPTCHA_KEY
                )
        )
    )
) );
define( 'VASHNAL_CATEGORY_ASSETS', array (
    'css' => array(
        array ( 'slug' => 'category', 'deps' => array(), 'ver' => '1.0.0' ),
        array ( 'slug' => 'pagination', 'deps' => array(), 'ver' => '1.0.0' )
    ),
    'js' => array (
        array ( 'slug' => 'lazyload', 'deps' => array( 'jquery' ), 'ver' => '12.4' ),
        array ( 'slug' => 'menu', 'deps' => array( 'jquery' ), 'ver' => '1.0.0' ),
        array ( 'slug' => 'pseudo-links', 'deps' => array( 'jquery' ), 'ver' => '1.0.0' ),
        array ( 'slug' => 'popup', 'deps' => array( 'jquery' ), 'ver' => '1.0.0' ),
        array ( 'slug' => 'question', 'deps' => array( 'jquery' ), 'ver' => '1.0.0',
                'params' => array(
                    'action' => 'get_question_form',
                    'send_action' => 'send_question_form',
                    'success_action' => 'get_question_success',
                    'add_script' => 'md5'
                )
        )
    )
) );
define( 'VASHNAL_ADMIN_ASSETS', array (
    'css' => array (
        array ( 'slug' => 'admin', 'deps' => array(), 'ver' => '1.0.0' )
    )
) );
define( 'VASHNAL_SEARCH_ASSETS', array (
    'css' => array (
        array ( 'slug' => 'search', 'deps' => array(), 'ver' => '1.0.0' ),
        array ( 'slug' => 'pagination', 'deps' => array(), 'ver' => '1.0.0' )
    )
) );
define( 'VASHNAL_ERROR404_ASSETS', array (
    'css' => array (
        array ( 'slug' => '404', 'deps' => array(), 'ver' => '1.0.0' )
    )
) );