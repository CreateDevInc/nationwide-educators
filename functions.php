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


add_action( 'wp_enqueue_scripts', 'astra_parent_theme_enqueue_styles' );

function astra_parent_theme_enqueue_styles() {
	wp_enqueue_style( 'astra-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'astrai-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'astra-style' )
	);
}


// Change the logo on the login page
function my_login_logo() { ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
		background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/login-logo.png);
		height: 100px;
		width: 130px;
		background-size: 80px 80px;
		background-repeat: no-repeat;
		background-position: center center;
		padding-bottom: 30px;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );


// Override the "Register" link on the login page
function wpse127636_register_url($link){
    return str_replace(site_url('wp-login.php?action=register', 'login'),site_url('register'),$link);
}
add_filter('register','wpse127636_register_url');

// Redirect from "Register" page to custom register page
function wpse127636_fix_register_urls($url, $path, $orig_scheme){
    if ($orig_scheme !== 'login')
        return $url;

    if ($path == 'wp-login.php?action=register')
        return site_url('register', 'login');

    return $url;
}
add_filter('site_url', 'wpse127636_fix_register_urls', 10, 3);

//
// Function to add subscribe text to posts and pages
function subscribe_link_shortcode() {
	global $wp;

	$return_value = null;

	$button_link = '#';
	$button_text = '#';
	$button_class = '';

	$link__register_page = get_site_url() . '/register';

	$page_url = home_url( $wp->request );
	$page_url_parts = explode( '/', $page_url );

	$is_course_regx = '/courses\/.(\w|-)*\/?$/';
	$is_course_page = preg_match( $is_course_regx, $page_url );

	if ( $is_course_page ) {
		$course_slug = $page_url_parts[sizeof($page_url_parts) - 1];

		// if the user is logged out, take them to the registration page with
		// a reference to the current course stored in the query string
		if ( !is_user_logged_in() ) {
			$button_link = $link__register_page . '?course=' . $course_slug;
			$button_text = 'Take This Course';
		}
		// if the user is logged in, we need to check and see whether or not
		// they're already registered for the course.
		else {
			global $wpdb;

			$id__current_user = get_current_user_id();
			$id__current_course = $wpdb->get_var( "SELECT ID FROM wp_posts WHERE post_type = 'sfwd-courses' AND post_name = '$course_slug';" );

			$course_data__serialized = $wpdb->get_var( "SELECT meta_value FROM wp_postmeta WHERE meta_key = '_sfwd-courses' AND post_id = $id__current_course" );
			$course_data__unserialized = maybe_unserialize( $course_data__serialized );
			
			$id__course_access_list = explode( ',', $course_data__unserialized['sfwd-courses_course_access_list'] );

			$is_enrolled = in_array($id__current_user, $id__course_access_list);

			if ($is_enrolled) {
				$button_text = 'Already Enrolled';
				$button_link = '#';
				$button_class = 'disabled';
			}
			else {
				$button_text = 'Take This Course';
				$button_link = 'https://google.com';
			}
		}
	}

	return "<a class='btn-join $button_class' href='$button_link'>$button_text</a>";
}
add_shortcode('subscribe', 'subscribe_link_shortcode');