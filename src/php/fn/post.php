<?php

/**
 * 投稿にアーカイブ(投稿一覧)を持たせる　※記載後にパーマリンク設定で「変更を保存」する
 */
add_filter( 'register_post_type_args', 'post_has_archive', 10, 2 );

function post_has_archive( $args, $post_type ) {
	if ( 'post' == $post_type ) {
		$args['rewrite'] = true;
		$args['has_archive'] = 'post-all'; // ページ名
	}
	return $args;
}


/**
 * 投稿フォーマット
 */
// add_theme_support('post-formats', array('status'));


/**
 * カスタム投稿タイプ・タクソノミー　※プラグインで管理に変更
 */
// add_action('init', 'add_my_custom_post_type');
// function add_my_custom_post_type() {
// }


/**
 * カスタム投稿タイプでカテゴリ未選択時にデフォルト設定
 * カスタム投稿タイプ：blog
 * タクソノミー：blog_cat
 * ターム：misc
 * フック先：publish_blog（publish_[カスタム投稿タイプ]）
 */
// add_action( 'publish_blog', 'add_defaultcategory_automatically' );

function add_defaultcategory_automatically($post_ID) {
  global $wpdb;
  // 設定されているカスタム分類のタームを取得
  $curTerm = wp_get_object_terms($post_ID, 'blog_cat');
  // 既存のターム指定数が 0（つまり未設定）であれば）「misc」を指定
  if (0 == count($curTerm)) {
      // misc のタームID
      $term = get_term_by( 'slug', 'misc', 'blog_cat' );
      $defaultTerm= $term->term_id;
      wp_set_object_terms($post_ID, $defaultTerm, 'blog_cat');
  }
}


/**
 * カスタム投稿タイプでaddquicktagを使う
 */
// add_filter( 'addquicktag_post_types', 'use_addquicktag_in_custom_post_type' );

// function use_addquicktag_in_custom_post_type( $post_types ) {
// 	$post_types[] = 'news';
// 	return $post_types;
// }


/**
 * カスタム投稿タイプの年別リストに「年」をつける設定
 */
add_filter( 'get_archives_link', 'add_nen_year_archives' );

function add_nen_year_archives( $link_html ) {
  $regex = array (
    "/ title='([\d]{4})'/" => " title='$1年'",
    "/ ([\d]{4}) /"        => " $1年 ",
    "/>([\d]{4})<\/a>/"    => ">$1年</a>"
  );
  $link_html = preg_replace( array_keys( $regex ), $regex, $link_html );
  return $link_html;
}


/**
 * カスタム投稿タイプのアーカイブで、ポストタイプを正確に取得する
 */
// add_filter( 'getarchives_where', 'my_custom_post_type_archive_where', 10, 2 );

function my_custom_post_type_archive_where( $where, $args ){
  $post_type  = isset( $args['post_type'] ) ? $args['post_type'] : 'post';
  $where = "WHERE post_type = '$post_type' AND post_status = 'publish'";
  return $where;
}


/**
 * タクソノミーのタームごとに月別アーカイブを表示
 */
// add_filter( 'getarchives_join', 'my_getarchives_join', 10, 2 );
// add_filter( 'getarchives_where', 'my_getarchives_where', 10, 2 );

function my_getarchives_join( $join, $args ) {
  global $wpdb;
  if ( isset( $args['taxonomy'] ) ) {
    $join .= "LEFT JOIN $wpdb->term_relationships ON ($wpdb->posts.ID = $wpdb->term_relationships.object_id)";
    $join .= "LEFT JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)";
    $join .= "LEFT JOIN $wpdb->terms ON ($wpdb->term_taxonomy.term_id = $wpdb->terms.term_id)";
  }
  return $join;
}

function my_getarchives_where( $where, $args ) {
  global $wpdb;
  if ( isset( $args['taxonomy'] ) ) {
    $where .= $wpdb->prepare( "AND $wpdb->term_taxonomy.taxonomy = %s", $args['taxonomy'] );
  }
  if ( isset( $args['slug'] ) ) {
    $where .= $wpdb->prepare( "AND $wpdb->terms.slug = %s", $args['slug'] );
  }
  return $where;
}


/**
 * カテゴリごとのアーカイブを取得
 */
// add_filter('getarchives_where', 'custom_archives_where', 10, 2);
// add_filter('getarchives_join', 'custom_archives_join', 10, 2);

function custom_archives_join($x, $r) {
  global $wpdb;
  $cat_ID = $r['cat'];
  if (isset($cat_ID)) {
    return $x . " INNER JOIN $wpdb->term_relationships ON ($wpdb->posts.ID = $wpdb->term_relationships.object_id) INNER JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)";
  } else {
    return $x;
  }
}

function custom_archives_where($x, $r) {
  global $wpdb;
  $cat_ID = $r['cat'];
  if (isset($cat_ID)) {
    return $x . " AND $wpdb->term_taxonomy.taxonomy = 'category' AND $wpdb->term_taxonomy.term_id IN ($cat_ID)";
  } else {
    $x;
  }
}

// 【使用方法】現在のカテゴリを取得して利用する場合
// $cat = get_the_category();
// $cat_ID = $cat[0]->cat_ID;
// wp_get_cat_archives('type=monthly', $cat_ID);
function wp_get_cat_archives($opts, $cat) {
  $args = wp_parse_args($opts, array('echo' => '1')); // default echo is 1.
  $echo = $args['echo'] != '0'; // remember the original echo flag.
  $args['echo'] = 0;
  $args['cat'] = $cat;

  $archives = wp_get_archives(build_query($args));
  $archs = explode('</li>', $archives);
  $links = array();

  foreach ($archs as $archive) {
    $link = preg_replace("/href='([^']+)'/", "href='$1?cat={$cat}'", $archive);
    array_push($links, $link);
  }
  $result = implode('</li>', $links);

  if ($echo) {
    echo $result;
  } else {
    return $result;
  }
}

// ギャラリーにクラス追加
function add_class_images($content){
  $my_custom_class = "is-modaal-img";
  $add_class = str_replace('<img class="', '<img class="'.$my_custom_class.' ', $content);
  return $add_class;
}
// add_filter('the_content', 'add_class_images');
