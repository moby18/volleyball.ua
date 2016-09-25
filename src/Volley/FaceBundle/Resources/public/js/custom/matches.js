$(document).ready(function () {

    var owl = $("#owl-demo");

    owl.owlCarousel({
        loop: true,
        margin: 0,
        nav: false,
        dots: false,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1,
            },
            600: {
                items: 3,
            },
            1000: {
                items: 5,
                loop: false,
                nav: false,
                dots: false,
            }
        }
    });

    // Custom Navigation Events
    $(".next").click(function () {
        owl.trigger('next.owl.carousel');
    });
    $(".prev").click(function () {
        owl.trigger('prev.owl.carousel');
    });

});