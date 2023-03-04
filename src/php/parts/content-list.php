<?php if(have_posts()): ?>
  <div class="p-news__list" data-file="content-list">
    <ul class="_list">
      <?php while(have_posts()): the_post(); ?>
      <?php
        $post_type_obj   = get_post_type_object(get_post_type());
        $post_type_label = $post_type_obj->labels->name;
        $post_type_slug  = $post_type_obj->name;
        $category = get_the_category();
        $cat_id   = $category[0]->cat_ID;
        $cat_name = $category[0]->cat_name;
        $cat_slug = $category[0]->category_nicename;
      ?>
      <li>
        <a href="<?php the_permalink(); ?>">
          <div class="_fig">
            <?php if (has_post_thumbnail()): ?>
              <?php echo the_post_thumbnail( array(100, 100) ); ?>
            <?php else: ?>
              <!-- <i class="a-thumbnail-dummy"></i> -->
              <img src="<?php echo templateDir(); ?>/assets/img/common/dummy-1x1.svg">
            <?php endif; ?>
          </div>
          <div class="_tx">
            <span class="_date"><?php echo get_the_date(); ?></span><span class="_tag"><?php echo $cat_name; ?></span><?php echo add_new_icon(); ?>
            <div class="_ttl"><?php the_title(); ?></div>
          </div>
        </a>
      </li>
    <?php endwhile; ?>
    </ul>
    <?php
      wp_pagenavi();
      // the_posts_pagination(array(
      //   'prev_text' => '前へ',
      //   'next_text' => '次へ',
      //   'mid_size' => 1
      // ));
    ?>
  </div>
<?php endif; ?>
