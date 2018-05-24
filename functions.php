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

include_once STYLESHEETPATH . '/includes/learndash.php' ;

add_action( 'wp_enqueue_scripts', 'astra_parent_theme_enqueue_styles' );

function astra_parent_theme_enqueue_styles() {
	wp_enqueue_style( 'astra-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'astrai-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'astra-style' )
	);
}

function shortcode__ld_take_this_course_button() {
	global $wp;

	$return_value = null;

	$button_link = '#';
	$button_text = '#';
	$button_class = '';

	$link__register_page = get_site_url() . '/register';

	$page_url = home_url( $wp->request );
	$page_url_parts = explode( '/', $page_url );

	if ( ld_is_course_page( $page_url ) ) {
		$course_slug = $page_url_parts[sizeof($page_url_parts) - 1];

		// query string with course slug is to redirect the user after they've
		// registered to the appropriate course-purchase-page
		if ( !is_user_logged_in() ) {
			$button_link = $link__register_page . '?course=' . $course_slug;
			$button_text = 'Take This Course';
		}
		else if ( ld_user_is_enrolled_in_course( $course_slug ) ) {
			$button_text = 'Already Enrolled';
			$button_link = '#';
			$button_class = 'disabled';
		}
		else {
			$button_text = 'Take This Course';
			$button_link = 'https://google.com';
		}
	}

	return "<a class='btn-join $button_class' href='$button_link'>$button_text</a>";
}
add_shortcode('take_course_button', 'shortcode__ld_take_this_course_button');