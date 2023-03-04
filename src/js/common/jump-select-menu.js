(function($) {
  $(function() {
    // select jump menu (select.js-jump-select-menu)
    $('.js-jump-select-menu').on('change', function() {
      var val = $(this).find('option:checked').val();
      if (val) location.href = val;
    });
  });
})(jQuery);
