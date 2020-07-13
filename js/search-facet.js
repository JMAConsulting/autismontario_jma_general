(function ($, Drupal, drupalSettings) {
  $(document).ready(function() {
    $('.facet-empty').parent().hide();

    if (drupalSettings.path.currentLanguage != 'en') {
      $('.facet-item__value').each(function() {
        if ($(this).text() === 'Service Listing') {
          $(this).text('Répertoire des services');
        }
      });
      $('#edit-container-container-2 .details-title').html($('#edit-container-container-2 .details-title').html().replace('Proximity', 'Proximité'));
      $('#edit-container-container-0 .details-title').html($('#edit-container-container-0 .details-title').html().replace('Camp Session', 'Session de camp'));
    }
  });

})(jQuery, Drupal, drupalSettings);
