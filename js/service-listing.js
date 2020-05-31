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
  $('#edit-container-container-0').insertBefore($('#edit-custom-897--wrapper'));
  $('#edit-container-root').prepend($('.form-item-search-api-fulltext'));
  $(document).ready(function() {
    $.each($('[id^=address-map'), function() {
      var point = new google.maps.LatLng($(this).attr('data-lat'), $(this).attr('data-lng'));
      var map = new google.maps.Map(document.getElementById($(this).attr('id')), {
        zoom: 14,
        fullscreenControl: false,
        zoomControl: false,
        center: point
      });
      var marker = new google.maps.Marker({position: point, map: map, title: $(this).attr('data-marker-title')});
    });
  });

})(jQuery, Drupal);
