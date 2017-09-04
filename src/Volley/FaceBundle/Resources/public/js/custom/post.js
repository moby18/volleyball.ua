function resizeSelect () {
  jQuery('select[id^=\'volley_bundle_facebundle_post_persons\']').select2entity({width: '100%'})
  jQuery('select[id^=\'volley_bundle_facebundle_post_teams\']').select2entity({width: '100%'})
  jQuery('li.select2-search').width('100%');
  jQuery('input.select2-search__field').width('100%');
}

jQuery(document).ready(function() {
    // datepicker
    jQuery('#volley_bundle_facebundle_post_published').datetimepicker({
        //defaultDate:new Date(),
        format:'Y-m-d H:i:s'
    });

    // select2 resize init and resize
    jQuery('a[href="#refs"]').on('click', function (event) {
      resizeSelect()
    })
    jQuery('a[href="#refs"]').on('show.bs.tab', function (event) {
      resizeSelect()
    })
    jQuery('a[href="#refs"]').on('shown.bs.tab', function (event) {
      resizeSelect()
    })
});
