(function($) {
  $(function() {
    // Modal
    $('.is-modaal').modaal();
    $('.is-modaal-img').modaal({
      type: 'image'
    });

    $('.gallery-item a').filter(function(){
      return this.href.match(/\.(jpg|jpeg|png|gif)$/i);
    }).modaal({
      type: 'image'
    });
  });
})(jQuery);
