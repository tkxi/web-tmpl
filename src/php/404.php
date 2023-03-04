<?php get_header(); ?>

<main class="l-main" role="main" data-file="404">
  <?php get_template_part('parts/head-page-title'); ?>
  <div class="l-main__body">
    <article class="p-entry">
      <section class="m-sct-01">
        <div class="m-column--6">
          <h4>404 Not found.</h4>
          <p>
            あなたがアクセスしようとしたページは削除されたかURLが変更されています。<br>
            ご迷惑をおかけしますがメニューまたは、トップページからご覧いただけますようお願いします。</p>
          <p><a href="/"><?php bloginfo('name'); ?> ホームへ</a></p>
        </div>
      </section>
    </article>
  </div>
</main>
<?php get_footer(); ?>
