<?php
/**
 * Astrai-child Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package astrai-child
 */

// Assumptions:
// - When creating a course, that course should have the same slug across all pages

$permalink__course_enrollment = 'enroll';
$permalink__course = 'courses';

include_once STYLESHEETPATH . '/includes/widgets.php';
include_once STYLESHEETPATH . '/includes/shortcodes.php';
include_once STYLESHEETPATH . '/includes/learndash.php';

add_action( 'wp_enqueue_scripts', 'astra_parent_theme_enqueue_styles' );

function astra_parent_theme_enqueue_styles() {
	wp_enqueue_style( 'astra-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'astrai-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'astra-style' )
	);
}