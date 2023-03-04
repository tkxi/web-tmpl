<?php

/* ウィジェット登録
================================================== */
/* template */
// $args = array(
//   'name'          => 'リンク',
//   'id'            => 'footer',
//   'class'         => '',
//  // 'before_widget' => '<li id="%1$s" class="widget %2$s">',
// 	// 'after_widget'  => '</li>',
// 	// 'before_title'  => '<h2 class="widgettitle">',
// 	// 'after_title'   => '</h2>'
//   'before_widget' => '<li class="side-box widget %2$s">',
//   'after_widget'  => '</li>',
//   'before_title'  => '<h6 class="side-box__heading">',
//   'after_title'   => '</h6>'
// );
// register_sidebar($args);

/* サイドナビ：共通 */
$args_side = array(
  'name'          => 'ニュース：サイドナビ',
  'id'            => 'sidebar-post-01',
  'class'         => '',
  'before_widget' => '<div class="side-box widget %2$s">',
  'after_widget'  => '</div>',
  'before_title'  => '<h6 class="side-box__heading">',
  'after_title'   => '</h6>'
);
register_sidebar($args_side);
