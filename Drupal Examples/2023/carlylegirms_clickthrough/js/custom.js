(function ($, Drupal) {

  /**
   * Custom.
   *
   * @todo: remove jquery from this.
   */
  Drupal.behaviors.custom = {
    attach: function (context, settings) {
      if (window.location.pathname == '/carlyle/login') {
        $('body.path-carlyle div.dialog-off-canvas-main-canvas .coh-style-padding-top-bottom-large').wrapAll('<div class="slider-wrapper"></div>');
      }
      $('.slick--view--featured-case-studies').once('slider-loaded').on('init', function(event, slick, currentSlide, nextSlide){
        $('.slick__arrow > .slick-prev').on('click', function(){
          $('.slick--view--featured-case-studies > .slick-slider').slick('slickPrev')
        })
        $('.slick__arrow > .slick-next').on('click', function(){
          $('.slick--view--featured-case-studies > .slick-slider').slick('slickNext')
        })
      });
    }
  };

})(jQuery, Drupal);

