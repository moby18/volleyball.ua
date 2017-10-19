jQuery(document).ready(function () {
    setTimeout(function () {
        if ($.cookie('social_modal_showed') != 'yes') {
            jQuery('#social').modal('show');
            $.cookie('social_modal_showed', 'yes', {path: '/', expires: 7});
        }
    }, 10000);
});
