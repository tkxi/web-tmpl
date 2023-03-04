<?php

/**
 * headタグ内の出力停止
 */
function disable_outupt_wp_head() {
  //wp_deregister_script('jquery');

  // remove_action('wp_head', '_wp_render_title_tag', 1);
  // remove_action('wp_head', 'wp_enqueue_scripts', 1);
  // remove_action('wp_head', 'wp_resource_hints', 2);
  // remove_action('wp_head', 'feed_links', 2);
  // remove_action('wp_head', 'feed_links_extra', 3);
  remove_action('wp_head', 'rsd_link');
  remove_action('wp_head', 'wlwmanifest_link');
  // remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
  // remove_action('wp_head', 'locale_stylesheet');
  // remove_action('wp_head', 'noindex', 1);
  // remove_action('wp_head', 'print_emoji_detection_script', 7);
  // remove_action('wp_head', 'wp_print_styles', 8);
  // remove_action('wp_head', 'wp_print_head_scripts', 9);
  remove_action('wp_head', 'wp_generator');
  // remove_action('wp_head', 'rel_canonical');
  // remove_action('wp_head', 'index_rel_link');
  // remove_action('wp_head', 'parent_post_rel_link');
  // remove_action('wp_head', 'start_post_rel_link');
  // remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
  // remove_action('wp_head', 'wp_site_icon', 99);
  // remove_action('wp_head', 'wp_no_robots');
  // remove_action('wp_head', 'wp_post_preview_js', 1);
  // remove_action('wp_head', 'wp_oembed_add_discovery_links');
  // remove_action('wp_head', 'wp_oembed_add_host_js');
  // remove_action('wp_head', 'rest_output_link_wp_head', 10, 0);
  // remove_action('wp_head', '_custom_logo_header_styles');
}
disable_outupt_wp_head();


/**
 * 読み込みscript指定
 */
// add_action( 'wp_enqueue_scripts', 'my_scripts_method' );

function my_scripts_method() {
  wp_deregister_script('jquery');
  wp_enqueue_script( 'slick', get_template_directory_uri() . '/assets/js/lib/slick.min.js', array('jquery'), '1.9.0', true );
}

/**
 * 管理バーを消す
 */
// add_filter('show_admin_bar', '__return_false');


/**
 * 絵文字を使用不可に
 */
add_action( 'init', 'disable_emojis' );

function disable_emojis() {
  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
  remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
  remove_action( 'wp_print_styles', 'print_emoji_styles' );
  remove_action( 'admin_print_styles', 'print_emoji_styles' );
  remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
  remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
  remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}


/**
 * タイトル表示の調整
 */
// add_filter('wp_title', 'my_wp_title');

function my_wp_title($title) {
  if(is_front_page() && is_home()){
    return get_bloginfo('name');
  } else {
    return $title."|". get_bloginfo('name');
  }
}


/**
 * wp_title()の日付を修正
 */
add_filter( 'wp_title', 'jp_date_wp_title', 10, 3 );

function jp_date_wp_title( $title, $sep, $seplocation ) {
  if ( is_date() ) {
    $m = get_query_var('m');
    if ( $m ) {
      $year = substr($m, 0, 4);
      $month = intval(substr($m, 4, 2));
      $day = intval(substr($m, 6, 2));
    } else {
      $year = get_query_var('year');
      $month = get_query_var('monthnum');
      $day = get_query_var('day');
    }

    $title = ($seplocation != 'right' ? " $sep " : '') .
    ($year ? $year . '年' : '') .
    ($month ? $month . '月' : '') .
    ($day ? $day . '日' : '') .
    ($seplocation == 'right' ? " $sep " : '');
  }
  return $title;
}


/**
 * アーカイブタイトルの編集
 */
add_filter( 'get_the_archive_title', 'my_theme_archive_title' );

function my_theme_archive_title( $title ) {
  if ( is_category() ) {
    $title = single_cat_title( '', false );
  } elseif ( is_tag() ) {
    $title = single_tag_title( '', false );
  } elseif ( is_author() ) {
    $title = '<span class="vcard">' . get_the_author() . '</span>';
  } elseif ( is_post_type_archive() ) {
    $title = post_type_archive_title( '', false );
  } elseif ( is_tax() ) {
    $title = single_term_title( '', false );
  }

  return $title;
}


/**
 * bodyにスラッグを追加（body_class機能拡張）
 */
add_filter('body_class', 'add_slug_body_class');

function add_slug_body_class($slug = '') {
	if (is_page()) {
		$page = get_page(get_the_ID());
		$slug[] = 'page-' . $page->post_name;
		$slug[] = 'p-' . $page->post_name;
		if ($page->post_parent) {
			$slug[] = 'page-' . get_page_uri($page->post_parent);
			$slug[] = 'p-' . get_page_uri($page->post_parent);
		}
	}
	return $slug;
}


/**
 * previous_post_link() と next_post_link() にクラス付加
 */
add_filter( 'previous_post_link', 'add_prev_post_link_class' );
add_filter( 'next_post_link', 'add_next_post_link_class' );

function add_prev_post_link_class($output) {
  return str_replace('<a href=', '<a class="page-numbers prev" href=', $output);
}

function add_next_post_link_class($output) {
  return str_replace('<a href=', '<a class="page-numbers next" href=', $output);
}


/**
 * 「保護中:」の文言を「ユーザー限定：」変更
 */
add_filter( 'the_title', 'change_hoge_title' );

function change_hoge_title($title) {
  $title = str_replace('保護中: ', '【ユーザー限定】', $title);
  return $title;
}


/**
 * 抜粋の...を変更・削除
 */
add_filter('excerpt_more', 'new_excerpt_more');

function new_excerpt_more($more) {
  return '';
}


/**
 * カスタムヘッダーの設定（トップのスライド設定は、ギャラリーからの読み込み設定に変更）
 */
// add_theme_support('custom-header', array(
//   'width'         => 2560,
//   'height'        => 1280,
//   'default-image' => get_template_directory_uri() . '/assets/img/slide-photo-03.jpg'
// ));


/**
 * ギャラリーのスタイル出力停止
 */
add_filter( 'use_default_gallery_style', '__return_false' );


/**
 * アイキャッチ画像の有効化
 */
add_theme_support('post-thumbnails');

/**
 * カスタム画像サイズを追加
 */
add_image_size('staff_photo', 480, 480, true);
add_image_size('top_slide', 1920, 1440, false);
add_image_size('news_thumbnail', 480, 320, false);
add_image_size('photo_480', 480, 480, false);
add_image_size('mid_mid_800', 800, 800, false);
add_image_size('vga', 640, 640, false);

function custom_sizes($sizes) {
  return array_merge($sizes, array(
      'top_slide'      => __('トップスライド1920'),
      'news_thumbnail' => __('ニュースサムネイル'),
      'photo_480'      => __('写真480'),
      'mid_mid_800'    => __('写真800'),
      'vga'            => __('VGA_640')
  ));
}
add_filter('image_size_names_choose', 'custom_sizes');
