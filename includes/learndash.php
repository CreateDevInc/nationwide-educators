<?php

/**
 * Returns whether or not the current user is already enrolled in a given course.
 *
 * @param string $course_slug - the slug for the course you wish to check enrollment for (e.g. 'drug-addiction')
 * @return boolean
 */
if ( !function_exists( 'ld_user_is_enrolled_in_course' ) ) {
    function ld_user_is_enrolled_in_course($course_slug) {
			global $wpdb;

			$id__user = get_current_user_id();
			$id__course = $wpdb->get_var( "SELECT ID FROM wp_posts WHERE post_type = 'sfwd-courses' AND post_name = '$course_slug';" );

			$course_data__serialized = $wpdb->get_var( "SELECT meta_value FROM wp_postmeta WHERE meta_key = '_sfwd-courses' AND post_id = $id__course" );
			$course_data__unserialized = maybe_unserialize( $course_data__serialized );
			
			$id__course_access_list = explode( ',', $course_data__unserialized['sfwd-courses_course_access_list'] );

      $is_enrolled = in_array($id__user, $id__course_access_list);
      
      return $is_enrolled;
    }
}

if ( !function_exists( 'ld_is_course_page' ) ) {
	function ld_is_course_page($page_url) {
		$is_course_regx = '/courses\/.(\w|-)*\/?$/';
		$is_course_page = preg_match( $is_course_regx, $page_url );

		return $is_course_page;
	}
}

if ( !function_exists( "ld_get_courses" ) ) { 
	function ld_get_courses() {
		global $wpdb;

		$courses = $wpdb->get_results( 'SELECT ID, post_name, post_title from wp_posts WHERE post_type = "sfwd-courses"' );

		return $courses;
	}
}

if ( !function_exists( "ld_list_courses" ) ) { 
	function ld_list_courses( $config ) {
		$courses = ld_get_courses();
		$site_url = get_site_url();
		$html_string = '';

		if ( $config['list_wrapper'] ) {
			$html_string .= $config['list_wrapper']['start'];
		}

		foreach ( $courses as $course ) {
			$course_name = $course->post_name;
			$course_title = $course->post_title;
			$course_link = "$site_url/$permalink__course/$course_name";

			if ( $config['course_wrapper'] ) {
				$html_string .= $config['course_wrapper']['start'];
			}

			if ( $config['include_thumbnail']) {
				$html_string .= '<div class="course">';
				$html_string .= get_the_post_thumbnail( $course->ID );
			}

			$html_string .= "<a href='$course_link'>$course->post_title</a>";

			if ( $config['include_thumbnail']) {
				$html_string .= '</div>';
			}

			if ( $config['course_wrapper'] ) {
				$html_string .= $config['course_wrapper']['end'];
			}
		}

		if ( $config['list_wrapper'] ) {
			$html_string .= $config['list_wrapper']['end'];
		}

		return $html_string;
	}
}