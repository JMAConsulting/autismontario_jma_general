(function ($, Drupal) {

  "use strict";
  var fields = ['895', '911', '905'];
  $.each(fields, function(index, value) {
    var text = $('.views-field-custom-' + value).text().trim();
    if (value == '895') {
      if (text === 'Regulated Services Provided:' || text === 'Services réglementés fournis:') {
        $('.views-field-custom-' + value).hide();
      }
    }
    else if (value == '911') {
      if (text === 'ABA Fields:' || text === 'Titre(s) de compétence détenu(s)') {
        $('.views-field-custom-' + value).hide();
      }
    }
    else {
      $('#other-languages').append($('.views-field-custom-' + value));
      if (text === 'Other language(s):') {
        $('.views-field-custom-' + value).hide();
      }
    }
  });

})(jQuery, Drupal);
