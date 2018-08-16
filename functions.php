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

add_action('wp_enqueue_scripts', 'astra_parent_theme_enqueue_styles');

function astra_parent_theme_enqueue_styles()
{
    wp_enqueue_style('astra-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('astrai-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('astra-style')
    );
    wp_enqueue_style('font-awesome',
        'https://use.fontawesome.com/releases/v5.0.13/css/all.css'
    );
    wp_enqueue_script('main-js', get_stylesheet_directory_uri() . '/js/main.js', array('wc-checkout', 'jquery'), '1.0.0', true);
    wp_localize_script('main-js', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}

/**
 * Defining custom query parameters that I want to be able to use throughout
 * the site's PHP.
 *
 * @param array $vars - an empty array to push permitted query parameters onto
 * @return void
 */
function add_query_vars_filter($vars)
{
    $vars[] = 'course';
    return $vars;
}
add_filter('query_vars', 'add_query_vars_filter');

/**
 * Redirect away from registration page if the user is logged in. If
 * they've just registered and there is a course slug in the query string,
 * then redirect them to that enroll page
 *
 * @return void
 */
function ld_redirect_actions()
{
    global $wp;

    $course = get_query_var('course');
    $current_url = home_url($wp->request);

    $is_logged_in = is_user_logged_in();
    $is_account_page = is_account_page($current_url);
    $is_register_page = is_register_page($current_url);

    $is_admin = current_user_can('administrator');

    if (is_page_type('cart', $current_url)) {
        global $woocommerce;

        if ($woocommerce->cart->get_cart_contents_count() > 0) {
            wp_redirect(get_permalink(wc_get_page_id('checkout')));
            exit();
        }
    }

    // if user tries to access an enrollment page and is already enrolled in that course,
    // then redirect them to the courses page for that course
    if (preg_match('/.*\/enroll\/(\w|-)*\/?($|\?)/', $current_url)) {
        $course_slug = explode('enroll/', $current_url)[1];
        $is_enrolled = ld_user_is_enrolled_in_course($course_slug);

        if ($is_enrolled && !$is_admin) {
            wp_redirect(str_replace('enroll', 'courses', $current_url));
        }
    }

    // if user tries to access a courses page and is not enrolled in that course,
    // then redirect them to the enrollment page for that course
    if (preg_match('/.*\/courses\/(\w|-)*\/?($|\?)/', $current_url)) {
        $course_slug = explode('courses/', $current_url)[1];
        $is_enrolled = ld_user_is_enrolled_in_course($course_slug);

        if (!$is_enrolled && !$is_admin) {
            wp_redirect(str_replace('courses', 'enroll', $current_url));
        }
    }

    if ($is_logged_in) {
        //  if ($is_register_page && $course) {
        //      wp_redirect(ld_get_course_enrollment_page($course));
        //      exit();
        //  } else if ($is_register_page) {
        //      wp_redirect(get_site_url());
        //      exit();
        //  } else if (is_front_page() && user_can(get_current_user_id(), 'group_leader')) {
        //      wp_redirect(get_site_url() . '/overview');
        //      exit();
        //  }

        //  Disabled for now to avoid conflicts with the WooCommerce check out,
        //  which sends a GET request to the homepage and triggers this code block.

        //   else if (is_front_page()) {
        //      wp_redirect(get_site_url() . '/profile');
        //      exit();
        //  }
    }
}
add_action('wp', 'ld_redirect_actions');

/* function logged_in_header() { */
/*     if ( is_user_logged_in() ) { ?> */
/*     <div class="logged-in-header"> */
/*     <h5> */
/*       <b> */
/*         <?php echo wp_get_current_user()->user_login; ?> */
/*       </b> */
/*     </h5> */
/*     </div> */
/*   <?php */
/*     } */
/* } */
/* add_action( 'astra_header_before', 'logged_in_header' ); */
