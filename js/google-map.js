(function ($, Drupal, navigator) {

  "use strict";

  if (!$('.geolocation-location').length && $('.geolocation-location').length !== -1 && $('.view-id-search_solr_.view-display-id-attachment_1').length) {
    $('.view-id-search_solr_.view-display-id-attachment_1').hide();
  }

  getclientlocation();

  function getclientlocation () {
    // If the browser supports W3C Geolocation API.
    if (navigator.geolocation) {

      var $latElement = $('input[name="center_lat"]');
      var $lngElement = $('input[name="center_long"]');
      var $currentlocation = $('#edit-current-location');

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

          $currentlocation.attr('disabled', true);
        },

        // Options for getCurrentPosition()
        {
          enableHighAccuracy: true,
          timeout: 5000,
          maximumAge: 6000
        }
      );

    }
    else {
      $('#edit-current-location').attr('disabled', true);
      console.log(Drupal.t('No location data found. Your browser does not support the W3C Geolocation API.'));
    }
  }

})(jQuery, Drupal, navigator);
