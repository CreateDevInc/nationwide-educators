<?php 
/*
  Template Name: Homepage
  Template Post Type: page
*/

get_header(); ?>

  <style>
    .hero-section {
      background-image: url("<?php echo get_field('hero-image'); ?>");
    }
  </style>

	<div id="primary" <?php astra_primary_class(); ?>>

    <div class="hero-section">
      <div class="overlay"></div>
      <div class="text">
        <h1><?php the_field('hero-primary-text') ?></h1>
        <h3><?php the_field('hero-secondary-text') ?></h3>
        <br />
        <a href="<?php the_field('hero-button-link') ?>" class="button"><?php the_field('hero-button-text') ?></a>
      </div>
    </div>

    <div class="course-section">
      <?php
        echo ld_list_courses( array( 
          'include_thumbnail' => true,
          'list_wrapper' => array( '<div class="course_list clearfix">', '</div>' ),
          'text_wrapper' => array( '<span>', '</span>' )
        ) ); 
      ?>
    </div>

    <div class="icons-section">
      <h1><?php the_field('icon-primary-text') ?></h1>
      <h3><?php the_field('icon-secondary-text') ?></h3>
      <div class="flex">

        <div>
          <div class="circle">
            <i class="fas fa-pen-square"></i>
          </div>
          <h3><?php the_field('icon-1-text') ?></h3>
        </div>

        <div class="flex-column">
          <i class="fas fa-arrow-alt-circle-right"></i>
        </div>

        <div>
          <div class="circle">
            <i class="fas fa-laptop"></i>
          </div>
          <h3><?php the_field('icon-2-text') ?></h3>
        </div>

        <div class="flex-column">
          <i class="fas fa-arrow-alt-circle-right"></i>
        </div>

        <div>
          <div class="circle">
            <i class="fas fa-file-alt"></i>
          </div>
          <h3><?php the_field('icon-3-text') ?></h3>
        </div>

      </div>
    </div>

	</div>

<?php get_footer(); ?>
