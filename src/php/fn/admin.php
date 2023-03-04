<?php

/**
 * WordPressをアップグレード通知させない
 */
//add_filter('pre_site_transient_update_core', create_function('$a', "return null;"));


/**
 * エディタ・スタイルシート
 */
// add_filter( 'admin_head', 'include_editor_css' );

function include_editor_css() {
  $screen = get_current_screen();
  $posttype = $screen->post_type;

  if( $posttype == 'page' ){
    //固定ページ用
    add_editor_style('/assets/css/editor-style-page.css');
  } else {
    //投稿用
    add_editor_style('/assets/css/editor-style.css');
  }
}


/**
 * 投稿画面での不使用機能を非表示
 */
// add_action('admin_menu','remove_default_post_screen_metaboxes');

function remove_default_post_screen_metaboxes() {
	// remove_meta_box('postcustom','post','normal');//カスタムフィールド
	remove_meta_box('postexcerpt','post','normal');//抜粋
	remove_meta_box('commentstatusdiv','post','normal');//コメント
	remove_meta_box('trackbacksdiv','post','normal');//トラックバック
	// remove_meta_box('slugdiv','post','normal');//スラッグ
	remove_meta_box('authordiv','post','normal');//著者
}


/**
 * エディタ：自動的にpタグを記述しない
 */
add_action( 'init', 'my_editor_custom' );

function my_editor_custom() {
  remove_filter('the_title', 'wptexturize');
  remove_filter('the_content', 'wptexturize');
  remove_filter('the_excerpt', 'wptexturize');
  remove_filter('the_title', 'wpautop');
  remove_filter('the_content', 'wpautop');
  remove_filter('the_excerpt', 'wpautop');
  // remove_filter('the_editor_content', 'wp_richedit_pre');
}

/**
 * エディタ：tinymce advancedの自動チェック＆修正を禁止
 */
// add_filter( 'tiny_mce_before_init', 'override_mce_options' );

function override_mce_options( $init_array ) {
  global $allowedposttags;

  $init_array['verify_html']             = false;
  $init_array['valid_elements']          = '*[*]';
  $init_array['extended_valid_elements'] = '*[*]';
  $init_array['valid_children']          = '+*[*]';
  $init_array['valid_children']          = '+a[' . implode( '|', array_keys( $allowedposttags ) ) . ']';
  $init_array['valid_children']          = '+h2[div],+*[' . implode( '|', array_keys( $allowedposttags ) ) . ']';
  $init_array['indent']                  = false;
  $init_array['wpautop']                 = false;
  $init_array['apply_source_formatting'] = true;

  return $init_array;
}



/**
 * 管理画面での不要サイドナビを非表示
 */
// add_action('admin_menu', 'remove_menus');

function remove_menus () {
	//if (!current_user_can('level_10')) { //level10以下のユーザーの場合メニューをunsetする
		global $menu;
		//unset($menu[2]);//ダッシュボード
		//unset($menu[4]);//メニューの線1
		//unset($menu[5]);//投稿
		//unset($menu[10]);//メディア
		unset($menu[15]);//リンク
		//unset($menu[20]);//ページ
		unset($menu[25]);//コメント
		//unset($menu[59]);//メニューの線2
		//unset($menu[60]);//テーマ
		//unset($menu[65]);//プラグイン
		//unset($menu[70]);//プロフィール（ユーザー）
		//unset($menu[75]);//ツール
		//unset($menu[80]);//設定
		//unset($menu[90]);//メニューの線3
	//}
}


/**
 * Authorアーカイブを無効
 */
add_action( 'template_redirect', 'theme_slug_redirect_author_archive' );

function theme_slug_redirect_author_archive() {
	if (is_author() ) {
		wp_redirect( home_url());
		exit;
	}
}


/**
 * 管理画面:一覧に項目追加 https://www.nxworld.net/wordpress/wp-add-posts-columns.html
 */
// 投稿一覧に「サムネイル」「ID」「スラッグ」「文字数」項目追加
add_filter( 'manage_posts_columns', 'add_posts_columns' );
add_action( 'manage_posts_custom_column', 'add_posts_columns_row', 10, 2 );

function add_posts_columns($columns) {
  $columns['thumbnail'] = 'サムネイル';
  $columns['postid']    = 'ID';
  // $columns['slug']      = 'スラッグ';
  // $columns['count']     = '文字数';

  echo '<style type="text/css">
  .fixed .column-thumbnail {width: 120px;}
  .fixed .column-postid {width: 10%;}
  .fixed .column-slug, .fixed .column-count {width: 10%;}
  </style>';

  return $columns;
}

function add_posts_columns_row( $column_name, $post_id ) {
  if ( $column_name == 'thumbnail' ) {
    $thumb = get_the_post_thumbnail( $post_id, array( 100, 100 ), 'thumbnail' );
    echo ( $thumb ) ? $thumb : '－';
  } elseif ( $column_name == 'postid' ) {
    echo $post_id;
  } elseif ( $column_name == 'slug' ) {
    $slug = get_post( $post_id ) -> post_name;
    echo $slug;
  } elseif ( $column_name == 'count' ) {
    $count = mb_strlen( strip_tags( get_post_field( 'post_content', $post_id ) ) );
    echo $count;
  }
}

// 固定ページ一覧に「スラッグ」項目追加
add_filter( 'manage_pages_columns', 'add_page_columns');
add_action( 'manage_pages_custom_column', 'add_page_column_row', 10, 2);

function add_page_columns($columns) {
  $columns['thumbnail'] = 'サムネイル';
  $columns['slug']      = "スラッグ";

  echo '<style type="text/css">
  .fixed .column-thumbnail {width: 120px;}
  .fixed .column-slug, .fixed .column-count {width: 10%;}
  </style>';

  return $columns;
}
function add_page_column_row($column_name, $post_id) {
  if( $column_name == 'slug' ) {
      $post = get_post($post_id);
      $slug = $post->post_name;
      echo esc_attr($slug);
  }
}
