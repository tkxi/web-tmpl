<?php if(have_posts()):  while(have_posts()): the_post(); ?>
<?php
  $category = get_the_category();
  $cat_id   = $category[0]->cat_ID;
  $cat_name = $category[0]->cat_name;
  $cat_slug = $category[0]->category_nicename;
?>
<article class="p-entry" data-file="content">
  <div class="p-entry__title">
    <h1 class="_first"><?php the_title(); ?></h1>
  </div>
  <div class="p-entry__body">
    <aside class="u-mb--15">
      <time class="m-tag-time"><?php echo get_the_date(); ?> </time>
      <span class="m-tag01"><?php echo $cat_name; ?></span><?php echo add_new_icon(); ?>
    </aside>
    <?php
      if ( has_post_thumbnail() ) {
        echo '<figure class="eye-catch">';
        the_post_thumbnail(array(1000, 1000));
        echo '</figure>';
      }
    ?>
    <?php the_content(); ?>
  </div>
</article>
<?php endwhile; endif; ?>
