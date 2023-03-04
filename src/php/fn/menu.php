<?php

// wp_nav_menuのliにclass追加
function add_additional_class_on_li($classes, $item, $args)
{
  if (isset($args->add_li_class)) {
    $classes['class'] = $args->add_li_class;
  }
  return $classes;
}
add_filter('nav_menu_css_class', 'add_additional_class_on_li', 1, 3);

// wp_nav_menuのaにclass追加
function add_additional_class_on_a($classes, $item, $args)
{
  if (isset($args->add_li_class)) {
    $classes['class'] = $args->add_a_class;
  }
  return $classes;
}
add_filter('nav_menu_link_attributes', 'add_additional_class_on_a', 1, 3);


/* メインメニュー登録
================================================== */
register_nav_menu('header-navi-pc', '【ヘッダー】ＰＣ');
register_nav_menu('header-navi-sp', '【ヘッダー】スマホ');
register_nav_menu('nav-footer-01', '【フッター】1');
register_nav_menu('nav-footer-02', '【フッター】2');

