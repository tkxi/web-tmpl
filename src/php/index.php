<?php get_header(); ?>
<?php get_sidebar(); ?>
<main class="l-main" role="main" data-file="index">
  <?php get_template_part('parts/head-page-title'); ?>
  <div class="l-main__body-column">
    <div class="l-main__body-column-content">
      <article class="p-entry">
        <?php get_template_part('parts/content-list'); ?>
      </article>
    </div>
  </div>
</main>
<?php get_footer(); ?>
