(function ($, Drupal) {

  "use strict";
  var fields = ['895', '911'];
  $.each(fields, function(index, value) {
    var text = $('.views-field-custom-' + value).text().trim();
    if (value == '895') {
      if (text === 'Regulated Services Provided:') {
        $('.views-field-custom-' + value).hide();
      }
    }
    else {
      if (text === 'ABA Fields:') {
        $('.views-field-custom-' + value).hide();
      }
    }
  });

})(jQuery, Drupal);
