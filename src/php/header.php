<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#">
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width">
    <meta name="format-detection" content="telephone=no">
    <meta name="theme-color" content="#ffffff">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache">
    <?php
      $bodyClassNames = [];
      if (get_post_type()) {
        $postTypeName = $post->post_type;
        $addClassPostTypeName = "p-" . $postTypeName;
        $bodyClassNames[] = $addClassPostTypeName;
      } else {
        $postTypeName = null;
      }
    ?>
    <title><?php echo wp_get_document_title(); ?></title>
    <?php wp_head(); ?>
    <link rel="apple-touch-icon" href="/apple-touch-icon-180x180.png">
    <link rel="shortcut icon" href="/favicon.ico" type="image/vnd.microsoft.icon">
    <link rel="icon" href="/favicon.ico" type="image/vnd.microsoft.icon">
    <meta property="og:title" content="<?php echo wp_get_document_title(); ?>">
    <meta property="og:type" content="article">
    <meta property="og:image" content="">
    <meta property="og:site_name" content="">
    <meta property="og:locale" content="ja">
    <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>?v=<?php echo date('YmdHis'); ?>">
    <link rel="stylesheet" href="<?php echo templateDir(); ?>/assets/css/style.css?v=<?php echo date('YmdHis'); ?>">
    <?php include_once('parts/google-analytics.php'); ?>
  </head>
<?php
  if ( is_404() || is_search() ) {
    $bodyClassNames[] = 'p-other';
  } elseif ( is_home() || $postTypeName == 'post' ) {
    $bodyClassNames[] = 'p-news';
    $bodyClassNames[] = 'page-news';
  }
?>
  <body <?php body_class(join(' ', $bodyClassNames)); ?>>
