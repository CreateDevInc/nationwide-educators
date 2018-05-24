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

		$courses = $wpdb->get_results( 'SELECT post_name, post_title from wp_posts WHERE post_type = "sfwd-courses"' );

		return $courses;
	}
}