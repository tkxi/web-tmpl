/*
 * Menu
 */
(function($) {
  const ua = navigator.userAgent.toLowerCase();
  let isiPhone = (ua.indexOf('iphone') > -1) && (ua.indexOf('ipad') == -1);
  let isAndroid = (ua.indexOf('android') > -1) && (ua.indexOf('mobile') > -1);
  let isSP = (isiPhone || isAndroid);
  let menuEvent = (isSP) ? 'orientationchange' : 'resize';

  /*
   * Header Menu Control
   */
  function checkMenuTrigger() {
    let $body = $('body');
    let $menuTrigger = $('#menu');

    if ($menuTrigger.prop('checked')) {
      $body.addClass('open-menu');
    } else {
      $body.removeClass('open-menu');
    }
  }
  $('#menu').on('change', checkMenuTrigger);
  checkMenuTrigger();

  /*
   * Header Menu Hide
   */
  function hideMenu() {
    let $body = $('body');
    let $menuTrigger = $('#menu');
    $body.removeClass('open-menu');
    $menuTrigger.prop('checked', false).trigger('change');
  }
  $(window).on(menuEvent, hideMenu);
  $('#nav-overlay').on('click', hideMenu);

  /*
   * Scroll header hide
   */
  let startPos = 0, currentPos = 0;

  function scrollHeader() {
    currentPos = $(window).scrollTop();

    if (currentPos > 100 && currentPos >= startPos) {
      $('.l-hdr').addClass('is-hdr-hide');
    } else {
      $('.l-hdr').removeClass('is-hdr-hide');
    }

    startPos = currentPos;
  }
  $(window).on('scroll', scrollHeader);
  scrollHeader();

})(jQuery);
