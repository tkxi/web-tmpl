<?php get_header(); ?>
<?php get_sidebar(); ?>
<main class="l-main" role="main" data-file="single">
  <?php get_template_part('parts/head-page-title'); ?>
  <div class="l-main__body">
    <?php get_template_part('parts/content'); ?>
  </div>
</main>
<?php get_footer(); ?>
