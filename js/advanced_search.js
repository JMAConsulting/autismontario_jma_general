(function($, Drupal) {
   $(document).ready(function() {
      $('#block-exposedformsearch-solr-page-1 #edit-container-container-0').insertAfter($('#edit-container-container-1 #edit-custom-898--wrapper'));
      var simpleSearchLink = "<a href='#' id='simple-search' style='color:#006ba6;float:right;display:none;'>" + Drupal.t('Simple Search') + "</a>";
      var advanceSearchLink = "<a href='#' id='advance-search' style='color:#006ba6;float:right;'>" + Drupal.t('Advanced Search') + "</a>";
      $('#block-exposedformsearch-solr-page-1 .form-item-search-api-fulltext').append(simpleSearchLink);
      $('#block-exposedformsearch-solr-page-1 .form-item-search-api-fulltext').append(advanceSearchLink);
      $('#block-exposedformsearch-solr-page-1 #edit-container-container-1').hide();
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
        if ($('#simple-search').is(':visible')) {
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
