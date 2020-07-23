(function($, Drupal) {
   $(document).ready(function() {
      $('#block-exposedformsearch-solr-page-1 #edit-container-container-0').insertAfter($('#edit-container-container-1 #edit-custom-898--wrapper'));
      $('#block-exposedformsearch-solr-page-1 #edit-container-container-4--2').insertAfter($('#edit-container-container-1--2 #edit-custom-898--2--wrapper'));
      var simpleSearchLink = "<a href='#' id='simple-search' style='color:#006ba6;float:right;display:none;'>" + Drupal.t('Simple Search') + "</a>";
      var advanceSearchLink = "<a href='#' id='advance-search' style='color:#006ba6;float:right;'>" + Drupal.t('Advanced Search') + "</a>";
      $('#block-exposedformsearch-solr-page-1 .form-item-search-api-fulltext').append(simpleSearchLink);
      $('#block-exposedformsearch-solr-page-1 .form-item-search-api-fulltext').append(advanceSearchLink);
      $('#block-exposedformsearch-solr-page-1 #edit-container-container-1').hide();

      $('.geolocation-latlng').each(function (e) {
        var link = '<a target="_blank" href="https://www.google.com/maps?saddr=My+Location&daddr=' + $(this).text().replace(' ', '') + '"><img alt="Directions" src="//www.gstatic.com/images/icons/material/system/2x/directions_gm_blue_20dp.png"></a>';
        $(this).html(link);
      });

      if (!$('#block-mappedlocation, #block-location').is(':visible')) {
        $('div.attachment-before').hide();
      }

      $('label[for=edit-custom-897-2]').prepend('<span class="provider-icon icon_videoconferencing-img" title="' + Drupal.t('Online') + '"></span>');
      $('label[for=edit-custom-897-3]').prepend('<span class="provider-icon icon_local_travel-img" title="' + Drupal.t('Travels to nearby areas') + '"></span>');
      $('label[for=edit-custom-897-4]').prepend('<span class="provider-icon icon_remote_travel-img" title="' + Drupal.t('Travels to remote areas') + '"></span>');
      $('#block-legend').addClass('block-facet--links');

      // Ensure that the magnifying glass shows.
      if (!$('.path-search').length) {
        $('#edit-submit-search-solr-').hide();
        $('#edit-actions').find('#edit-submit-search-solr-').show();
      }

      $('#simple-search').on('click', function(e) {
        $('#block-exposedformsearch-solr-page-1 #edit-container-container-1').hide();
        $('#advance-search').show();
        $(this).hide();
      });
      $('#advance-search').on('click', function(e) {
        $('#block-exposedformsearch-solr-page-1 #edit-container-container-1').show();
        $('#simple-search').show();
        $(this).hide();
      });
      $('#edit-submit-search-solr-').on('click', function(e) {
        if ($('#advance-search').is(':visible')) {
          $('#edit-container-container-1 input').each(function (e) {
            $(this).val('');
            $(this).prop('checked', false);
          });
          $('#edit-container-container-1 select option').each(function (e) {
            $(this).prop('selected', false);
          });
        }
      });
    });
})(jQuery, Drupal);
