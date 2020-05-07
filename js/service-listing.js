(function ($, Drupal) {

  "use strict";
  $.each($('.views-field'), function() {
    if ($('.field-content', $(this)).length && $('.field-content', $(this)).text().trim() == '') {
      $(this).hide();
    }
    if ($('.field-content', $(this)).length) {
      $('.field-content', $(this)).html($('.field-content', $(this)).html().replace(/<br>/g, ''));
    }
  });

  $.each($('.views-field-address-details .right > a'), function() {
    if ($(this).text() == $('#primary-phone').text()) { 
      $('#primary-phone').hide();
    }
  });

})(jQuery, Drupal);
