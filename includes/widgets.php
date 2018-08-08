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


function astra_logo($echo = true) {
    $display_site_tagline = astra_get_option('display-site-tagline');
    $display_site_title = astra_get_option('display-site-title');
    $html = '';

    $has_custom_logo = apply_filters('astra_has_custom_logo', has_custom_logo());

    // Site logo.
    if ($has_custom_logo) {

        if (apply_filters('astra_replace_logo_width', true)) {
            add_filter('wp_get_attachment_image_src', 'astra_replace_header_logo', 10, 4);
        }

        $html .= '<span class="site-logo-img">';
        $html .= get_custom_logo();
        $html .= '</span>';

        if (apply_filters('astra_replace_logo_width', true)) {
            remove_filter('wp_get_attachment_image_src', 'astra_replace_header_logo', 10);
        }
    }

    if (!apply_filters('astra_disable_site_identity', false)) {

        // Site Title.
        $tag = 'span';
        if (is_home() || is_front_page()) {
            $tag = 'h1';
        }

        /**
         * Filters the tags for site title.
         *
         * @since 1.3.1
         *
         * @param string $tags string containing the HTML tags for Site Title.
         */
        $tag = apply_filters('astra_site_title_tag', $tag);
				$site_title_markup = 
				'<' . $tag . ' itemprop="name" class="site-title"> 
					<a href="' . esc_url(home_url('/')) . '" itemprop="url" rel="home">
						<div class="title">' .
							get_bloginfo('name') .
					 '</div>
						<div class="subtitle">' .
							get_bloginfo('description') .
					 '</div>
					</a> 
				</' . $tag . '>';

        // Site Description.
        $site_tagline_markup = '<p class="site-description" itemprop="description">' . get_bloginfo('description') . '</p>';

        if ($display_site_title || $display_site_tagline) {
            /* translators: 1: Site Title Markup, 2: Site Tagline Markup */
            $html .= sprintf(
                '<div class="ast-site-title-wrap">
                                                %1$s
                                                %2$s
                                        </div>',
                ($display_site_title) ? $site_title_markup : '',
                ($display_site_tagline) ? $site_tagline_markup : ''
            );
        }
    }
    $html = apply_filters('astra_logo', $html, $display_site_title, $display_site_tagline);

    /**
     * Echo or Return the Logo Markup
     */
    if ($echo) {
        echo $html;
    } else {
        return $html;
    }
}
