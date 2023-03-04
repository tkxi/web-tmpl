<?php
  if (get_post_type()) {
    $postTypeName = $post->post_type;
  } else {
    $postTypeName = null;
  }
  if ( is_404() ) {
    $heading = 'ページが見つかりません';
  } elseif ( is_home() || $postTypeName == 'post' ) {
    $heading = 'ニュース';
  } else {
    $heading = get_the_title();
  }
  $subtitle = SCF::get('header_subtitle');
?>
<div class="l-main__head">
  <div class="l-main__ttl">
    <?php if(!empty($subtitle)):?>
    <div class="l-main__sttl"><?php echo $subtitle; ?></div>
    <?php endif;?>
    <h1><?php echo $heading ?></h1>
  </div>
</div>
