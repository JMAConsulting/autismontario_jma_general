(function ($, Drupal) {

  "use strict";

  $('.field--name-id > .field__item').hide();
  $('.field--name-field-geolocation').on('click', function(e) {
     if ($(this).data('quickedit-field-id')) {
      var parts = $(this).data('quickedit-field-id').split('/');
      if (parts[0] == 'civicrm_event') {
        window.open('/event-location/' + parts[1], '_blank');
      }
      if (parts[0] == 'civicrm_contact') {
        window.open('/contact-map/' + parts[1], '_blank');
      }
    }
    else {
      var contactID = $('.field--name-id > .field__item' , $(this).parent()).text();
      window.open('/contact-map/' + contactID, '_blank');
    }
  });


})(jQuery, Drupal);
