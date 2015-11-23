$(document).ready(function () {
        $(".navbar .nav.navbar-nav.navbar-right").find('li.dropdown')
            .mouseover(function () {
                $(this).addClass('open');
            })
            .mouseout(function () {
                $(this).removeClass('open');
            });
    }
);