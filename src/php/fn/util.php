<?php

/* テンプレートディレクトリURLを取得
================================================== */
function templateDir() {
	$templateDir = esc_url(get_template_directory_uri());
	//return $templateDir;//絶対パス
	return preg_replace( '/^(https?:\/\/.+?)\/(.*)$/', '/$2', $templateDir );//サイトルートパス
}


/* stdClass Objectを配列に変換
================================================== */
function stdclass_to_array($objDATA) {
  $arrayDATA = (array)$objDATA;
  $arrayKeys = array_keys($arrayDATA);
  $intCount = count($arrayKeys);

  for ( $i = 0; $i < $intCount; $i++ ) {
    $arrayDATA[$arrayKeys[$i]] = (array)$arrayDATA[$arrayKeys[$i]];
  }
  return $arrayDATA;
}


/* スラッグ名からIDを取得
================================================== */
function get_ID_by_slug($page_slug) {
    $page = get_page_by_path($page_slug);

    if ($page) {
        return $page->ID;
    } else {
        return null;
    }
}


/* ファイルサイズを取得
================================================== */
function get_file_size($file) {
  $mfile = str_replace(site_url(), ABSPATH, $file);

  if ( is_file($mfile) ) {
    $filesize = filesize($mfile);
    $s = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    $e = floor(log($filesize)/log(1024));
    if ($e == 0 || $e == 1) { $format = '%d '; }
    else { $format = '%.1f '; }
    $filesize = sprintf($format.$s[$e], ($filesize/pow(1024, floor($e))));
    return $filesize;
  }
}


/* 最初の画像を読み込む
================================================== */
function catch_that_image() {
  global $post, $posts;
  $first_img = '';
  ob_start();
  ob_end_clean();
  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  $first_img = $matches[1][0];

if(empty($first_img)){ //Defines a default image
    $first_img = "/images/default.jpg";
  }
  return $first_img;
}


/* 日付の出力
================================================== */
function smart_entry_date() {
  printf('<time class="entry-date published" datetime="%1$s">%2$s</time>',
    esc_attr(get_the_date()),
    get_the_date()
  );
}


/* カテゴリの出力
================================================== */
function smart_entry_category($pretag="", $endtag="") {
  $categories_list = get_the_category_list(', ');
  if ($categories_list) {
    printf($pretag.'%1$s'.$endtag, $categories_list);
  }
}


/* タグの出力
================================================== */
function smart_entry_tag($pretag="", $endtag="") {
  $tags_list = get_the_tag_list('', ', ');
  if ($tags_list) {
    printf($pretag.'%1$s'.$endtag, $tags_list);
  }
}


/* New icon 追加（15日間以内）
================================================== */
function add_new_icon() {
  $days = 15;
  $today = date('U');
  $entry = get_the_time('U');
  $diff = date('U', ($today-$entry)) / 86400;

  if ($days > $diff) {
    return '<i class="a-tag-new"></i>';
    // return '<img src="'.get_bloginfo('template_directory').'/assets/imgs/ico-new.png" alt="NEW" class="ico-new" />';
  }
}


/* カスタムフィールドのPDFを取得
================================================== */
function add_pdf_icon() {
  $post_id = get_the_ID();
  $field_pdf = get_post_meta( $post_id, 'pdf_file', true ); //カスタムフィールドの値を取得

  if ($field_pdf) {
    return '<i class="fa fa-file-pdf-o fa-lg c-ico-pdf"></i>';
    // return '<img src="'.get_bloginfo('template_directory').'/assets/imgs/ico-pdf.png" alt="PDF" class="ico-pdf" />';
  }
}


/* ページネーション
================================================== */
/* =============== 一般 =============== */
function paginationTag() {
  global $wp_query;
  $maxpage = $wp_query->max_num_pages;
  if ( !$maxpage ) return '';//$maxpage;

  global $paged;
  $current = $paged;
  if ( !$current ) $current = 1;
  //$paged = get_query_var('paged') ? get_query_var('paged') : 1 ;

  return paginationView($current,$maxpage);
}

/* =============== 固定ページ =============== */
function paginationTagForPage($the_query) {
  $maxpage = $the_query->max_num_pages;
  if ( !$maxpage ) return '';

  $current = $the_query->query['paged'];
  if ( !$current ) $current = 1;

  return paginationView($current,$maxpage);
}

/* =============== ページ送り(like amazon) =============== */
function paginationView($current,$maxpage) {
  $displayCount = 3;//odd number
  $count = 0;
  $absVal = floor( ($displayCount + 1) / 2 );
  $difVal = floor( ($displayCount - 1) / 2 );
  $needAdjustment = ($maxpage > $displayCount + 2) ? 1 : 0;

  $dotLeft = ($current > $absVal) ? 1 : 0;//最左グループいる場合
  $dotRight = ($current <= $maxpage - $absVal) ? 1 : 0;//最右グループいる場合

  // start tag ==========
  $pagination_tag = '';
  $pagination_tag .= '<div class="paginationContainer">';
  $pagination_tag .= '<ul class="pagination">';

  // arrow left ==========
  if( $current > 1) {
    $pagination_tag .= '<li><a href="'. get_previous_posts_page_link() .'">&laquo;</a></li>';
  } else {
    $pagination_tag .= '<li class="disabled"><span>&laquo;</span></li>';
  }

  // loop ==========
  if ($needAdjustment) {
    if ($dotLeft) {
      $pagination_tag .= '<li><a href="'. get_pagenum_link(1) .'">'. 1 .'</a></li>';
      if ($current != $absVal + 1) $pagination_tag .= '<li>…</li>';
    }

    if (!$dotLeft) {//最初のグループ
      $min = 1;
      $max = $displayCount;
    } else if (!$dotRight) {//最後のグループ
      $min = $maxpage - $displayCount;
      $max = $maxpage;
    } else {// 前後を取得するグループ
      $min = $current - $difVal;
      $max = $current + $difVal;
    }

    for ($i = $min; $i <= $max; $i++) {
      if ($i == $current) {
        $pagination_tag .= '<li class="active"><span>'. $i .'</span></li>';
      } else {
        $pagination_tag .= '<li><a href="'. get_pagenum_link($i) .'">'. $i .'</a></li>';
      }
    }

    if ($dotRight) {
      if ($current != $maxpage - $absVal) $pagination_tag .= '<li>…</li>';
      $pagination_tag .= '<li><a href="'. get_pagenum_link($maxpage) .'">'. $maxpage .'</a></li>';
    }
  } else {
    for ($i = 1; $i <= $maxpage; $i++) {
      if ($i == $current) {
        $pagination_tag .= '<li class="active"><span>'. $i .'</span></li>';
      } else {
        $pagination_tag .= '<li><a href="'. get_pagenum_link($i) .'">'. $i .'</a></li>';
      }
    }
  }

  // arrow right ==========
  if( $current < $maxpage ) {
    $pagination_tag .= '<li><a href="'. get_next_posts_page_link() .'">&raquo;</a></li>';
  } else {
    $pagination_tag .= '<li class="disabled"><span>&raquo;</span></li>';
  }

  // end tag ==========
  $pagination_tag .= '</ul>';
  $pagination_tag .= '</div>';
  return $pagination_tag;
}
