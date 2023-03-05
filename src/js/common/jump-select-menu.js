/*
 * Jump Select Menu
 */
(function($) {
  $(function() {
    $('.js-jump-select-menu').on('change', function() {
      var val = $(this).find('option:checked').val();
      if (val) location.href = val;
    });
  });
})(jQuery);
