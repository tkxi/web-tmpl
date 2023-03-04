(function($) {
  $(function() {
    // Inquiry Form
    function checkAgree() {
      var $submit = $('#submit, .js-submit, [name="submitConfirm"]');
      var $agree = $('#agree, .js-agree');

      if ($agree.prop('checked')) {
        $submit.prop('disabled', false);
      } else {
        $submit.prop('disabled', true);
      }
    }

    $('#agree, .js-agree').on('change', checkAgree);
    checkAgree();
  });
})(jQuery);
