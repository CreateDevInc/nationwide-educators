<?php

/**
 * Returns whether or not the current user is already enrolled in a given course.
 *
 * @param string $course_slug - the slug for the course you wish to check enrollment for (e.g. 'drug-addiction')
 * @return boolean
 */
if (!function_exists('ld_user_is_enrolled_in_course')) {
    function ld_user_is_enrolled_in_course($course_slug)
    {
        global $wpdb;

        $id__user = get_current_user_id();

        if ($id__user == 0) {
          return false;
        }

        $id__course = $wpdb->get_var("SELECT ID FROM wp_posts WHERE post_type = 'sfwd-courses' AND post_name = '$course_slug';");

        global $PC;

        $course_data__serialized = $wpdb->get_var("SELECT meta_value FROM wp_postmeta WHERE meta_key = '_sfwd-courses' AND post_id = $id__course");
        $course_data__unserialized = maybe_unserialize($course_data__serialized);

        $str__course_access_list = $course_data__unserialized['sfwd-courses_course_access_list'];

        if (!$str__course_access_list) {
          return false;
        }

        $id__course_access_list = explode(',', $str__course_access_list);

        $is_enrolled = in_array($id__user, $id__course_access_list);

        return $is_enrolled;
    }
}

if (!function_exists('ld_is_course_page')) {
    function ld_is_course_page($page_url)
    {
        $is_course_regx = '/courses\/.(\w|-)*\/?$/';
        $is_course_page = preg_match($is_course_regx, $page_url);

        return $is_course_page;
    }
}

if (!function_exists("ld_get_courses")) {
    function ld_get_courses()
    {
        global $wpdb;

        $courses = $wpdb->get_results('SELECT ID, post_name, post_title from wp_posts WHERE post_type = "sfwd-courses"');

        return $courses;
    }
}

if (!function_exists("ld_list_courses")) {
    function ld_list_courses($config = array())
    {
        global $permalink__course;

        $courses = ld_get_courses();
        $site_url = get_site_url();
        $html_string = '';

        if (isset($config['list_wrapper'])) {
            $html_string .= $config['list_wrapper'][0];
        }

        foreach ($courses as $course) {
            $course_name = $course->post_name;
            $course_title = $course->post_title;
            $course_link = "$site_url/$permalink__course/$course_name";

            if (isset($config['course_wrapper'])) {
                $html_string .= $config['course_wrapper'][0];
            }

            if (isset($config['include_thumbnail'])) {
                $html_string .= '<div class="course">';
                $html_string .= get_the_post_thumbnail($course->ID);
            }

            $html_string .= "<a href='$course_link'>";

            if (isset($config['text_wrapper'])) {
                $html_string .= $config['text_wrapper'][0];
            }

            $html_string .= $course->post_title;

            if (isset($config['text_wrapper'])) {
                $html_string .= $config['text_wrapper'][1];
            }

            $html_string .= "</a>";

            if (isset($config['include_thumbnail'])) {
                $html_string .= '</div>';
            }

            if (isset($config['course_wrapper'])) {
                $html_string .= $config['course_wrapper'][1];
            }
        }

        if (isset($config['list_wrapper'])) {
            $html_string .= $config['list_wrapper'][1];
        }

        return $html_string;
    }
}

if (!function_exists("ld_get_course_page")) {
    function ld_get_course_page($course_slug)
    {
        global $permalink__course;
        $site_url = get_site_url();

        return "$site_url/$permalink__course/$course_slug";
    }
}

if (!function_exists("ld_get_course_enrollment_page")) {
    function ld_get_course_enrollment_page($course_slug)
    {
        global $permalink__course_enrollment;
        $site_url = get_site_url();

        return "$site_url/$permalink__course_enrollment/$course_slug";
    }
}
