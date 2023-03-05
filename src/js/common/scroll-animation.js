/*
 * Scroll Animation (use plugin)
 */
(function($) {
  $(function() {
    // Scroll Animation
    $('.anm, [class*="anm-"], .anm-list > *').each(function () {
      $(this).acs();
    });
  });
})(jQuery);
