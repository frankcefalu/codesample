/**
 * @file
 * Contains medpro javascript.
 */

(function($) {
  Drupal.behaviors.janrain_medpro = {
    attach: function() {
      Drupal.behaviors.janrainCaptureCallbacks.addCaptureLoadCallback(Drupal.behaviors.janrain_medpro.CaptureLoad);
    },

    CaptureLoad: function() {
      // @todo: maybe we should not be doing this?
      localStorage.medpro_fail_count = 0;

      janrain.events.onCaptureRenderComplete.addHandler(function(result) {
        if (result.screen == "traditionalRegistration" || result.screen == "editProfile") {
          $('#capture_' + result.screen + '_form_item_reasonCode').hide();
          $('#capture_' + result.screen + '_form_item_sampleability').hide();

          var saveButton;
          if (result.screen == "traditionalRegistration") {
            saveButton = document.getElementById("capture_" + result.screen + "_createAccountButton");
          }
          else if (result.screen == "editProfile") {
            saveButton = document.getElementById("capture_" + result.screen + "_saveButton");
            // Disable Status change for Professional.
            if ($('#capture_editProfile_professionalStatus').val() == 'Professional') {
              $('#capture_editProfile_professionalStatus').attr('disabled', 'disabled');
              $('#capture_editProfile_form_item_professionalStatus').addClass('capture_disabled');
            }
          }

          if (saveButton != null) {
            $(saveButton).on('click', function(e) {
              $('.fail-message').hide();
              var valid = false;
              var professionalStatus = document.getElementById('capture_' + result.screen + '_professionalStatus');
              var professionalStatusValue = '';
              if (professionalStatus != null) {
                professionalStatusValue = professionalStatus.value;
              }

              // Do MedoPro Validation only for Professional.
              if (professionalStatusValue == 'Professional') {
                if (Drupal.settings.janrain_medpro.fields !== undefined) {
                  var data = janrainMeDProExtractData(result.screen);
                  var medpro_fail_count;
                  if (localStorage.medpro_fail_count) {
                    medpro_fail_count = localStorage.medpro_fail_count;
                  }
                  else {
                    localStorage.medpro_fail_count = 0;
                    medpro_fail_count = 0;
                  }

                  var scrolled = false;

                  if (data) {
                    $.ajax({
                      method: "POST",
                      url: Drupal.settings.basePath + Drupal.settings.pathPrefix + 'janrain_medpro/validate/Practitioner',
                      data: data,
                      async: false,
                      success: function (returnResult) {
                        if (returnResult.status !== undefined) {
                          if (returnResult.status == 'error') {
                            if (returnResult.result.Sampleability != undefined && (returnResult.result.Sampleability == 'N' || returnResult.result.Sampleability == 'D' || returnResult.result.Sampleability == 'U')) {
                              // Show message #1.
                              $('#fail-message-1').show();
                              scrolled = true;
                              $('.modal-hide').scrollTop($('#fail-message-1').position().top);
                            }
                            else {
                              localStorage.medpro_fail_count = parseInt(medpro_fail_count) + 1;
                              if ((parseInt(Drupal.settings.janrain_medpro.fail_limit) == 0) || (localStorage.medpro_fail_count <= Drupal.settings.janrain_medpro.fail_limit)) {
                                // Show message #2.
                                $('#fail-message-2').show();
                                scrolled = true;
                                $('.modal-hide').scrollTop($('#fail-message-2').position().top);
                              }
                              else {
                                // Show message #3.
                                $('#fail-message-3').show();
                                scrolled = true;
                                $('.modal-hide').scrollTop($('#fail-message-3').position().top);
                              }
                            }
                          }
                          else if (returnResult.status == 'success') {
                            if (returnResult.result.ReasonCode != undefined && returnResult.result.ReasonCode != '' && Drupal.settings.janrain_medpro.fields.ReasonCode != '') {
                              field = document.getElementById('capture_' + result.screen + '_' + Drupal.settings.janrain_medpro.fields.ReasonCode);
                              if (field != null) {
                                field.value = returnResult.result.ReasonCode;
                              }
                            }

                            if (returnResult.result.Sampleability != undefined && returnResult.result.Sampleability != '' && Drupal.settings.janrain_medpro.fields.Sampleability != '') {
                              field = document.getElementById('capture_' + result.screen + '_' + Drupal.settings.janrain_medpro.fields.Sampleability);
                              if (field != null) {
                                field.value = returnResult.result.Sampleability;
                              }
                            }

                            valid = true;
                          }
                        }
                      }
                    });
                  }
                }
              }
              else {
                valid = true;
              }

              if (Drupal.settings.janrain_medpro.promo_codes !== undefined) {
                var promo_code_field = document.getElementById('capture_' + result.screen + '_promoCode');
                if (promo_code_field != null && promo_code_field.value != '') {
                  if (!Drupal.settings.janrain_medpro.promo_codes.some(function(element, i){
                      if (String(promo_code_field.value).toLowerCase() == element.toLowerCase()) {
                        return true;
                      }
                    })) {
                    valid = false;
                    $('#capture_' + result.screen + '_form_item_promoCode .capture_tip_error').html(Drupal.settings.janrain_medpro.promo_codes_error_message);
                    $('#capture_' + result.screen + '_promoCode').addClass('capture_required');
                    $('#capture_' + result.screen + '_form_item_promoCode').addClass('capture_error').removeClass('capture_validated');
                    if (!scrolled) {
                      $('.modal-hide').scrollTop($('#capture_' + result.screen + '_form_item_promoCode').position().top);
                    }
                  }
                }
              }

              if (!valid) {
                e.preventDefault();
                return false;
              }
            });
          }
        }
      });

      function janrainMeDProExtractData(screen) {
        var data = {};
        var valid = true;

        if (Drupal.settings.janrain_medpro.token !== undefined) {
          data.token = Drupal.settings.janrain_medpro.token;
        }

        var field, field_id, field_key, field_value, field_form_item_id;
        for (field_key in Drupal.settings.janrain_medpro.fields) {
          field_id = 'capture_' + screen + '_';
          field_form_item_id = 'capture_' + screen + '_form_item_';
          if (screen == "traditionalRegistration" && (field_key == 'FirstName' || field_key == 'LastName')) {
            field_id += screen + '_';
            field_form_item_id += screen + '_';
          }
          field = document.getElementById(field_id + Drupal.settings.janrain_medpro.fields[field_key]['key']);
          if (field != null) {
            field_value = field.value;
            if (field_value != '') {
              data[field_key] = field_value;
            }
            else {
              if (Drupal.settings.janrain_medpro.fields[field_key]['required']) {
                // Show field error message.
                $('#' + field_form_item_id + Drupal.settings.janrain_medpro.fields[field_key]['key'] + ' .capture_tip_error').html(Drupal.settings.janrain_medpro.fields[field_key]['required_message']);
                $('#' + field_id + Drupal.settings.janrain_medpro.fields[field_key]['key']).addClass('capture_required');
                $('#' + field_form_item_id + Drupal.settings.janrain_medpro.fields[field_key]['key']).addClass('capture_error').removeClass('capture_validated');
                valid = false;
              }
            }
          }
        }

        return valid ? data : false;
      }
    }

  };

})(jQuery);
