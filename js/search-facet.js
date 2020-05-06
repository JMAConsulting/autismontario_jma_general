(function ($, Drupal, drupalSettings) {
  $(document).ready(function() {
    $('.facet-empty').parent().hide();

    if (drupalSettings.path.currentLanguage != 'en') {
      $('.facet-item__value').each(function() {
        if ($(this).text() === 'Service Listing') {
          $(this).text('Répertoire des services');
        }
      });
    }
  });

})(jQuery, Drupal, drupalSettings);
