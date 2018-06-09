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
$user = get_current_user();
$permalink__course = 'courses';
$permalink__course_enrollment = 'enroll';

include_once STYLESHEETPATH . '/includes/utils.php';
include_once STYLESHEETPATH . '/includes/widgets.php';
include_once STYLESHEETPATH . '/includes/shortcodes.php';
include_once STYLESHEETPATH . '/includes/learndash.php';
include_once STYLESHEETPATH . '/includes/woocommerce.php';

add_action( 'wp_enqueue_scripts', 'astra_parent_theme_enqueue_styles' );

function astra_parent_theme_enqueue_styles() {
	wp_enqueue_style( 'astra-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'astrai-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'astra-style' )
	);
  wp_enqueue_style( 'font-awesome',
    'https://use.fontawesome.com/releases/v5.0.13/css/all.css'
  );
}

/**
 * Defining custom query parameters that I want to be able to use throughout
 * the site's PHP.
 *
 * @param array $vars - an empty array to push permitted query parameters onto
 * @return void
 */
function add_query_vars_filter( $vars ) {
	$vars[] = 'course';
	return $vars;
}
add_filter( 'query_vars', 'add_query_vars_filter' );

/**
 * Redirect away from registration page if the user is logged in. If
 * they've just registered and there is a course slug in the query string,
 * then redirect them to that enroll page
 *
 * @return void
 */
function ld_redirect_actions() {
	global $wp;

	$course = get_query_var( 'course' );
	$current_url = home_url( $wp->request );

	$is_logged_in = is_user_logged_in();
  $is_account_page = is_account_page( $current_url );
	$is_register_page = is_register_page( $current_url );

  if ( $is_logged_in ) {
    if ( $is_register_page && $course ) {
      wp_redirect( ld_get_course_enrollment_page( $course ) );
      exit();
    }
    else if ( $is_register_page ) {
      wp_redirect( get_site_url() );
      exit();
    }
    else if ( is_front_page() && user_can( get_current_user_id(), 'group_leader' )) {
      wp_redirect( get_site_url() . '/overview');
      exit();
    }
    else if ( is_front_page() ) {
      wp_redirect( get_site_url() . '/profile' );
      exit();
    }
  }
}
add_action( 'wp', 'ld_redirect_actions' );

<<<<<<< HEAD
function logged_in_header() {
	if ( is_user_logged_in() ) { ?>
    <div class="logged-in-header">
    <h5>
      <b>
        <?php echo wp_get_current_user()->user_login; ?>
      </b>
    </h5>
    </div>
  <?php
	}
}
add_action( 'astra_header_before', 'logged_in_header' );
 
=======
/* function logged_in_header() { */
/* 	if ( is_user_logged_in() ) { ?> */
/*     <div class="logged-in-header"> */
/*     <h5> */
/*       <b> */
/*         <?php echo wp_get_current_user()->user_login; ?> */
/*       </b> */
/*     </h5> */
/*     </div> */
/*   <?php */
/* 	} */
/* } */
/* add_action( 'astra_header_before', 'logged_in_header' ); */
>>>>>>> master
