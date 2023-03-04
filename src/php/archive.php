<?php get_header(); ?>
<?php get_sidebar(); ?>
<main class="l-main" role="main" data-file="archive">
  <?php get_template_part('parts/head-page-title'); ?>
  <div class="l-main__body">
    <article class="p-entry">
    <h5 class="p-news__list-ttl"><?php
        if(is_month()) {
          echo the_time("Y年m月").'の記事一覧';
        } elseif(is_year()) {
          echo the_time("Y年").'の記事一覧';
        } else {
          echo get_the_archive_title();
        }
      ?></h5>
      <?php get_template_part('parts/content-list'); ?>
    </article>
  </div>
</main>
<?php get_footer(); ?>
