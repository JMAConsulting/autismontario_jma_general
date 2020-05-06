(function ($, Drupal) {

  "use strict";
  $.each($('.views-field'), function() {
    if ($('.field-content', $(this)).length && $('.field-content', $(this)).text().trim() == '') {
      $(this).hide();
    }
  });

})(jQuery, Drupal);
