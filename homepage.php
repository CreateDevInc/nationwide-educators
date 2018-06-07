<?php 
/*
  Template Name: Homepage
  Template Post Type: page
*/

get_header(); ?>

<?php if ( astra_page_layout() == 'left-sidebar' ) : ?>

	<?php get_sidebar(); ?>

<?php endif ?>

	<div id="primary" <?php astra_primary_class(); ?>>

    <?php
      echo ld_list_courses( array( 
        'include_thumbnail' => true,
        'list_wrapper' => array( '<div class="course_list clearfix">', '</div>' ),
        'text_wrapper' => array( '<span>', '</span>' )
      ) ); 
    ?>

	</div><!-- #primary -->

<?php if ( astra_page_layout() == 'right-sidebar' ) : ?>

	<?php get_sidebar(); ?>

<?php endif ?>

<?php get_footer(); ?>
