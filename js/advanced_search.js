(function($, Drupal) {
   $(document).ready(function() {
      $('#block-exposedformsearch-solr-page-1 #edit-container-container-0--2').insertAfter($('#edit-container-container-1--2 #edit-custom-898--3--wrapper'));
      $('#block-exposedformsearch-solr-page-1 #edit-container-container-4--2').insertAfter($('#edit-container-container-1--2 #edit-custom-898--2--wrapper'));
      $('#edit-field-geolocation-2-proximity-wrapper--3').insertAfter($('#edit-container-container-2--2 .details-wrapper'));
      $('#edit-field-geolocation-2-proximity-wrapper--3 legend').hide();

      var simpleSearchLink = "<a href='#' id='simple-search' style='color:#006ba6;float:right;display:none;'>" + Drupal.t('Simple Search') + "</a>";
      var advanceSearchLink = "<a href='#' id='advance-search' style='color:#006ba6;float:right;'>" + Drupal.t('Advanced Search') + "</a>";
      $('#block-exposedformsearch-solr-page-1 .form-item-search-api-fulltext').append(simpleSearchLink);
      $('#block-exposedformsearch-solr-page-1 .form-item-search-api-fulltext').append(advanceSearchLink);
      $('#block-exposedformsearch-solr-page-1 #edit-container-container-1--2').hide();
      $('.geolocation-latlng').each(function (e) {
        var link = '<a target="_blank" href="https://www.google.com/maps?saddr=My+Location&daddr=' + $(this).text().replace(' ', '') + '"><img alt="Directions" src="//www.gstatic.com/images/icons/material/system/2x/directions_gm_blue_20dp.png"></a>';
        $(this).html(link);
      });

      if (!$('#block-mappedlocation, #block-location').is(':visible')) {
        $('div.attachment-before').hide();
      }

      $('#block-legend h2').css({"content": "", "display": "block", "width": "265px", "padding-top": "0.5em", "border-bottom": "2px solid #abad00"});
      $('#block-legend').css({"padding": "1.5em 2em", "border-radius": "4px", "border": "solid 1px #dcdcdc", "background-color": "white","box-shadow": "0 0 20px -5px rgba(0,0,0,0.1),0 0 20px -5px rgba(0,0,0,0.1)", "margin-bottom": "1.25em"});
      $('#block-legend table td').css({"padding": "unset", "border": "none"});
      $('table td').css({"background": "none !important"});
      $('.view-content:eq(1)').prepend($('#block-legend'));
      // Ensure that the magnifying glass shows.
      if (!$('.path-search').length) {
        $('#edit-submit-search-solr-').hide();
        $('#edit-actions').find('#edit-submit-search-solr-').show();
      }

      $('#simple-search').on('click', function(e) {
        $('#block-exposedformsearch-solr-page-1 #edit-container-container-1--2').hide();
        $('#advance-search').show();
        $(this).hide();
      });
      $('#advance-search').on('click', function(e) {
        $('#block-exposedformsearch-solr-page-1 #edit-container-container-1--2').show();
        $('#simple-search').show();
        $(this).hide();
      });
      $('#edit-submit-search-solr-').on('click', function(e) {
        if ($('#advance-search').is(':visible')) {
          $('#edit-container-container-1--2 input').each(function (e) {
            $(this).val('');
            $(this).prop('checked', false);
          });
          $('#edit-container-container-1--2 select option').each(function (e) {
            $(this).prop('selected', false);
          });
        }
      });
    });
})(jQuery, Drupal);
