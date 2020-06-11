(function($, Drupal) {
   $(document).ready(function() {
      $('#edit-submit-search-solr-').hide();
      $('#edit-actions').find('#edit-submit-search-solr-').show();
      var simpleSearchLink = "<a href='#' id='simple-search' style='color:#006ba6;float:right;'>" + Drupal.t('Simple Search') + "</a>";
      var advanceSearchLink = "<a href='#' id='advance-search' style='color:#006ba6;float:right;display:none;'>" + Drupal.t('Simple Search') + "</a>";
      $('#block-exposedformsearch-solr-page-1 .form-item-search-api-fulltext').append(simpleSearchLink);
      $('#block-exposedformsearch-solr-page-1 .form-item-search-api-fulltext').append(advanceSearchLink);
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
    });
})(jQuery, Drupal);
