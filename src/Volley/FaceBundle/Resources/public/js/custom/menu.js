$(document).ready(function () {
        var hovered = 0;
        $(".navbar .nav.navbar-nav li.dropdown")
            .hover(function () {
                    "use strict";
                    $(this).addClass('open');
                },
                function () {
                    "use strict";
                    $(this).removeClass('open');
                });
    }
);