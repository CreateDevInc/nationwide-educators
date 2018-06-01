<?php
/**
 * Outputs a button that does different things depending on the
 * user who is viewing it:
 *  1. If the user is logged out, clicking the button will take them
 *     to the registration page. The registration page will store
 *     the relevant course in the query string, and after registering,
 *     the user will be redirected to purchase it.
 *  2. If the user is logged in and has not registered for the course,
 *     the button will take them to the course purchase page.
 *  4. If the user is logged in and has already registered for the course,
 *     the button will display the text "Already Registered" and will not be
 *     clickable.
 *
 * @return void
 */
function shortcode__ld_take_this_course_button() {
	global $wp;
	global $permalink__course_enrollment;

	$return_value = null;

	$button_link = '#';
	$button_text = 'Take This Course';

	$link__register_page = get_site_url() . '/register';

	$page_url = home_url( $wp->request );
	$page_url_parts = explode( '/', $page_url );

	if ( ld_is_course_page( $page_url ) ) {
		$course_slug = $page_url_parts[sizeof($page_url_parts) - 1];

		if ( !is_user_logged_in() ) {
			$button_link = $link__register_page . '?course=' . $course_slug;
			$button_text = 'Take This Course';
		}
		else if ( ld_user_is_enrolled_in_course( $course_slug ) ) {
      return;
		}
		else {
			$button_text = 'Take This Course';
			$button_link = ld_get_course_enrollment_page( $course_slug );
		}
	}

	return "<a class='btn-join' href='$button_link'>$button_text</a>";
}
add_shortcode('take_course_button', 'shortcode__ld_take_this_course_button');

/**
 * Creates a grid of blocks that each link to a course. Prominently featured on the
 * homepage of the site.
 *
 * @return void
 */
function shortcode__ld_course_list() {
	echo ld_list_courses( array( 
		'include_thumbnail' => true,
		'list_wrapper' => array( '<div class="course_list clearfix">', '</div>' ),
		'text_wrapper' => array( '<span>', '</span>' )
	) );
}
add_shortcode( 'list_courses', 'shortcode__ld_course_list' );
