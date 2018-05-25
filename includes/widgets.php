<?php

/**
 * A widget that shows a basic list of all links to the site courses. Primarily
 * used in the main navigation menu to automatically update the menu as new
 * courses are created or removed.
 */
class ld_course_list extends WP_Widget {
	public function __construct() {
		$widget_options = array( 
      'description' => 'Returns a list of hyperlinks to all of the site courses.'
    );
    parent::__construct( 'ld_course_list', 'LearnDash Course List', $widget_options );
	}

	public function widget( $args, $instance ) {
		echo ld_list_courses();
	}
}

function ld_course_list__register_widget() {
	register_widget('ld_course_list');
}
add_action( 'widgets_init', 'ld_course_list__register_widget' );