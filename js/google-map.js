(function ($, Drupal, navigator) {

  "use strict";

  if (!$('.geolocation-location').length && $('.geolocation-location').length !== -1 && $('.view-id-search_solr_.view-display-id-attachment_1').length) {
    $('.view-id-search_solr_.view-display-id-attachment_1').hide();
  }

  if (!navigator.geolocation.getCurrentPosition()) {
    $('input[name*="current_location"]').attr('readonly', true);
    console.log(Drupal.t('No location data found. Your browser does not support the W3C Geolocation API.'));
  }
  else {
    getclientlocation();
  }

  $('input[name*="current_location"]').on('click', function() {
    if (this.checked) {
     $('input[name*="street_address"], input[name*="city"], input[name*="postal_code"]').val('');
     getclientlocation();
    }
  });
  $('input[name*="street_address"], input[name*="city"], input[name*="postal_code"]').on('keyup', function(e) {
    $('input[name*="current_location"]:checked').trigger('click');
  });

  $('input[name*="center_lat"], input[name*="center_long"]').parent().hide()
  $('input[name*="street_address"]').attr('size', 50);
  $('input[name*="city"]').attr('size', 15);
  $('input[name*="postal_code"]').attr('size', 7);
  $('input[name*="postal_code"]').attr('maxlength', 7);
  $('input[name*="postal_code"]').attr('placeholder', 'x#x #x#');

  $('.view-display-id-attachment_1').on('click', function(e) {
    var contactIds = '';
    var eventIds = '';
    e.preventDefault();
    $('.geolocation-map-wrapper .geolocation-location').each(function(e) {
      var type = $('.location-content > .views-field-search-api-datasource .field-content', $(this)).text();
      if (type == 'entity:civicrm_event') {
        var id = $('.location-content > .views-field-id-1 .field-content', $(this)).text();
        eventIds = eventIds == '' ? id : eventIds + '+' + id;
      }
      else if (type == 'entity:civicrm_contact') {
        var id = $('.location-content > .views-field-id .field-content', $(this)).text();
        contactIds = contactIds == '' ? 'contact' + id : contactIds + '+contact' + id;
      }
    });
    if (contactIds != '' || eventIds != '') {
      window.open("/event-location/" + eventIds + contactIds);
    }
    else {
      window.open("/contact-map");
    }
  });

  function getclientlocation () {
    // If the browser supports W3C Geolocation API.
    if (navigator.geolocation) {

      var $currentlocation = $('input[name*="current_location"]');
      var $latElement = $('input[name*="center_lat"]');
      var $lngElement = $('input[name*="center_long"]');
      // Get the geolocation from the browser.
      navigator.geolocation.getCurrentPosition(

        // Success handler for getCurrentPosition()
        function (position) {
          var lat = position.coords.latitude;
          var lng = position.coords.longitude;
          var accuracy = position.coords.accuracy / 1000;

          // Display a success message.
          var locationString = Drupal.t('Browser location: @lat,@lng Accuracy: @accuracy m', {'@lat': lat, '@lng': lng, '@accuracy': accuracy});
          console.log(locationString);
          $latElement.val(lat);
          $lngElement.val(lng);
        },

        // Error handler for getCurrentPosition()
        function (error) {

          // Alert with error message.
          switch (error.code) {
            case error.PERMISSION_DENIED:
              console.log(Drupal.t('No location data found. Reason: PERMISSION_DENIED.'));
              break;

            case error.POSITION_UNAVAILABLE:
              console.log(Drupal.t('No location data found. Reason: POSITION_UNAVAILABLE.'));
              break;

            case error.TIMEOUT:
              console.log(Drupal.t('No location data found. Reason: TIMEOUT.'));
              break;

            default:
              console.log(Drupal.t('No location data found. Reason: Unknown error.'));
              break;
          }
          $currentlocation.attr('readonly', true);
        },

        // Options for getCurrentPosition()
        {
          enableHighAccuracy: true,
          timeout: 5000,
          maximumAge: 6000
        }
      );
    }
  }

})(jQuery, Drupal, navigator);
