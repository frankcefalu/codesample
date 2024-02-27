(function ($, Drupal) {

  /**
   * Nda Agreement Modal Customization.
   *
   * @todo: remove jquery from this.
   */
  Drupal.behaviors.nda_agreement = {
    attach: function (context, settings) {
      $(document).ajaxComplete(function () {
        if (document.querySelector('.nda-agreement-accept') && !document.querySelector('.accept-arrow-img')) {
          var divElement = document.createElement('img');
          divElement.src = "/themes/contrib/cohesion-theme/images/back.png";
          divElement.className = "accept-arrow-img";
          document.querySelector('button[class*="nda-agreement-accept"]').after(divElement);
        }
        if (jQuery( ".modal--nda_carlyle" ).length == 1){
          jQuery( ".modal--nda_carlyle" ).once('modal-close').on( "dialogclose", function( event, ui ) {
            if (!getCookie("Drupal.visitor.carlylegirms")) {
              /** If cookie does not exists Open Nda Modal again and again*/
              ndaModal()
            }
          } );
        }
      });
    }
  };

})(jQuery, Drupal);

if (getCookie("Drupal.visitor.carlylegirms")) {
  // have cookie
} else {
  // no cookie
  jQuery(document).ready(function () {
    /** Open Nda Modal */
    ndaModal()
  });

}

function getCookie(name) {
  var match = document.cookie.match(RegExp("(?:^|;\\s*)" + name + "=([^;]*)"));
  return match ? match[1] : null;
}

function ndaModal() {
  var ajaxSettings = {
    url: '/admin/config/clickthrough/NdaAgreementForm',
    dialogType: 'modal',
    dialog: { width: 600, dialogClass: "modal--nda_carlyle no-close" },
  };
  var myAjaxObject = Drupal.ajax(ajaxSettings);
  myAjaxObject.execute();
} 