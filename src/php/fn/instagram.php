<?php
//Instagram ショートコード
function shortcode_instagram($arg){
  extract ( shortcode_atts ( array (
  'url' => '', //InstagramのURL（https://www.instagram.com/p/XXXXXXXXXXX/）
  'maxwidth' => '320', //最大幅（任意）
  ), $arg ) );

  $ret = '';

  $ret .= '<blockquote class="instagram-media" data-instgrm-version="7" ';
  $ret .= 'style=" background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); ';
  $ret .= 'margin: 1px; max-width:658px; padding:0; width:99.375%; width:-webkit-calc(100% - 2px); ';
  $ret .= 'width:calc(100% - 2px);">';
  $ret .= '<div style="padding:8px;"> ';
  $ret .= '<div style="background:#F8F8F8; line-height:0; margin-top:40px; padding:50.0% 0; text-align:center; width:100%;"> ';
  $ret .= '<div style="background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACwAAAAsCAMAAAApWqozAAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAAMUExURczMzPf399fX1+bm5mzY9AMAAADiSURBVDjLvZXbEsMgCES5/P8/t9FuRVCRmU73JWlzosgSIIZURCjo/ad+EQJJB4Hv8BFt+IDpQoCx1wjOSBFhh2XssxEIYn3ulI/6MNReE07UIWJEv8UEOWDS88LY97kqyTliJKKtuYBbruAyVh5wOHiXmpi5we58Ek028czwyuQdLKPG1Bkb4NnM+VeAnfHqn1k4+GPT6uGQcvu2h2OVuIf/gWUFyy8OWEpdyZSa3aVCqpVoVvzZZ2VTnn2wU8qzVjDDetO90GSy9mVLqtgYSy231MxrY6I2gGqjrTY0L8fxCxfCBbhWrsYYAAAAAElFTkSuQmCC); ';
  $ret .= 'display:block; height:44px; margin:0 auto -44px; position:relative; top:-22px; width:44px;"></div></div>';
  $ret .= '<p style="color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; line-height:17px; margin-bottom:0; margin-top:8px; ';
  $ret .= 'overflow:hidden; padding:8px 0 7px; text-align:center; text-overflow:ellipsis; white-space:nowrap;"><a href="';
  $ret .= $url;
  $ret .= '" style="color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:normal; ';
  $ret .= 'line-height:17px; text-decoration:none;" target="_blank"></a></p></div></blockquote>';

  if (isset($maxwidth) && $maxwidth != '') {
  $ret = '<div style="max-width:'.$maxwidth.'px">'.$ret.'</div>';
  }

  return $ret;
}
add_shortcode('instagram', 'shortcode_instagram');

//Instagram スクリプト
function shortcode_scripts() {
  global $post;
  if (has_shortcode($post->post_content, 'instagram')) {
    wp_enqueue_script('instagramjs', '//platform.instagram.com/en_US/embeds.js');
  }
}
add_action('wp_enqueue_scripts', 'shortcode_scripts');
