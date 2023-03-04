/**
 * Anchor Smooth Scroll
 */

(function($) {
  let scrollableElement;
  let FF = navigator.userAgent.match(/Firefox\/([0-9]+)\./);

  if ('scrollingElement' in document) {
    scrollableElement = document.scrollingElement;
  } else if (/*@cc_on!@*/false || (!!window.MSInputMethodContext && !!document.documentMode)) {
    scrollableElement = document.documentElement;
  } else if (FF && parseInt(FF[1]) <= 47) {
    scrollableElement = document.documentElement;
  } else {
    scrollableElement = document.body;
  }

  const scrollConf = {
    duration: 400,
    easing: 'easeOutExpo'
  };

  function scrollToPos(target) {
    let adjust = $('header').outerHeight(false);
    let $dest = $(target);

    if($dest.length > 0) {
      let pos = $dest.offset().top;
      if (adjust) pos -= adjust;
      $(scrollableElement).animate({scrollTop:pos}, scrollConf);
    }
  }

  $('[href^="#"], .js-scroll').not('a[href="#"], .is-no-scroll').on('click', function() {
    let target;
    let url = $(this).attr('href') ? $(this).attr('href') : $(this).attr('data-anchor');
    let hash = url.split('#');

    // 同じページ内のアンカー移動
    target = '#' + hash[hash.length - 1];
    scrollToPos(target);
    return false;
  });

  $('.pageup').on('click', function() {
    $(scrollableElement).animate({scrollTop:0}, scrollConf);
    return false;
  });

  // URLにハッシュがある場合のスクロール
  $(window).on('load', function() {
    let urlHash = location.hash;

    if(urlHash){
      let $target = $(urlHash);
      if ($target.length > 0) {
        setTimeout(function() {
          scrollToPos(urlHash);
        }, 100);
      }
    }
  });

  // pageup
  let $globalPageup = $('.l-pageup');
  $(window).on('scroll', function() {
    let st = $(this).scrollTop();
    if (st > 200) {
      $globalPageup.addClass('active');
    } else {
      $globalPageup.removeClass('active');
    }
  });

})(jQuery);
