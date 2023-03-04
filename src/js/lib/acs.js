(function($) {

  /**
   * Add Class in Scrolling
   * $('.anm, [class*="anm-"], .anm-list > *').each(function () {
   *   $(this).acs();
   * });
   */
  $.fn.acs = function (options) {

    var el = this;
    var defaults = {
      screenPos: 0.8,
      className: 'is-animated'
    };
    var setting = $.extend({}, defaults, options);

    $(window).on('load scroll', function () {
      add_class_in_scrolling();
    });

    function add_class_in_scrolling() {
      var winScroll = $(window).scrollTop();
      var winHeight = $(window).height();
      var scrollPos = winScroll + (winHeight * setting.screenPos);

      if (el.offset().top < scrollPos) {
        el.addClass(setting.className);
      }
    }
  }

  /**
   * list animation delay
   * $('.anm-list > *').each(function () {
   *   $(this).anmDelay();
   * });
   */
  $.fn.anmDelay = function (options) {
    var el = this;
    var defaults = {
      delay: 0.2,
      property: 'animation-delay'
    };
    var setting = $.extend(defaults, options);
    var index = el.index();
    var time = index * setting.delay;
    el.css(setting.property, time + 's');
  }

})(jQuery);
