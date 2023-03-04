<?php if ( $post->post_type == 'post' || is_archive() ): ?>
  <div class="l-side">
    <label class="l-side__switch">
      <input id="side_switch" type="checkbox" name="side_switch"><span></span>
    </label>

    <div class="l-side__container">
      <?php dynamic_sidebar('sidebar-post-01'); ?>

      <div class="widget widget_archive">
        <h6>過去のお知らせ</h6>
        <ul>
          <?php
            wp_get_archives(array(
              'post_type' => 'post',
              'type' => 'monthly',
              'limit' => 5
            ));
          ?>
        </ul>
        <div class="l-side__jump-menu">
          <div class="m-select _full">
            <select class="js-select-jumb-menu">
              <option value="" selected>過去のお知らせ一覧</option>
              <?php
                wp_get_archives(array(
                  'post_type' => 'post',
                  'type' => 'monthly',
                  'before' => '',
                  'after' => '',
                  'format' => 'option'
                ));
              ?>
            </select>
          </div>
        </div>
      </div>
      <div class="widget" style="border-top: 1px solid #ddd;">
        <ul>
          <li class="cat-item"><a href="/news/">ニューストップ</a></li>
        </ul>
      </div>
    </div>
  </div>
<?php endif; ?>
