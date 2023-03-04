<?php get_header(); ?>

<main class="l-main" role="main" data-file="page">
  <?php get_template_part('parts/head-page-title'); ?>
  <div class="l-main__body">
    <?php if(have_posts()): while(have_posts()): the_post(); ?>
    <?php the_content(); ?>
    <?php endwhile; endif; ?>
  </div>
</main>
<?php get_footer(); ?>
