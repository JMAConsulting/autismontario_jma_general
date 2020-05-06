(function ($, Drupal) {

  "use strict";
  $.each($('.views-field'), function() {
    if ($('.field-content', $(this)).text().trim() == ''  && $('.field-content', $(this)).length) {
      $(this).hide();
    }
  });

})(jQuery, Drupal);
