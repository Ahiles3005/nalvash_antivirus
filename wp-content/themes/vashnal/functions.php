<?php

// Define theme constants.
require_once( 'inc/defines.php' );

// Include Vashnal\Homepage_Category_Widget class with custom category widget declaration
require_once( 'classes/class-vashnal-homepage-category-widget.php' );

// Include Vashnal\HTML_Widget class for overriding default html widget
require_once( 'classes/class-vashnal-html-widget.php' );

// Custom theme functions, helpers and default functionality redefinition.
require_once( 'classes/class-vashnal-theme.php' );

// Theme assets organization.
require_once( 'classes/class-vashnal-assets.php' );

// SVG icons handler.
require_once( 'classes/class-vashnal-svg.php' );

// Common content parsing static functions.
require_once( 'classes/class-vashnal-content.php' );

// QA form building and handling.
require_once( 'classes/class-vashnal-question.php' );

// Comment form building and handling.
require_once( 'classes/class-vashnal-comment.php' );

// Custom comments walker class
require_once( 'classes/class-vashnal-walker-comment.php' );

// Add hooks for redefinition of default Wordpress functionality.
Vashnal\Theme::setup();

// Include page type based assets.
Vashnal\Assets::init();

// Create global svg handler object. Using one global instance in order to decrease spritemap file reading amount.
global $vashnal_svg;
$vashnal_svg = new Vashnal\Svg();

// Add custom filters for content modification.
Vashnal\Content::add_hooks();

// Init question form hooks.
Vashnal\Question::init();

// Init comment form hooks.
Vashnal\Comment::init();