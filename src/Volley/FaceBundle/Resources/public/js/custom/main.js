!function (a) {
    "use strict";
    var b = {
        initialised: !1,
        version: 1,
        mobile: !1,
        container: a("#portfolio-item-container"),
        blogContainer: a("#blog-item-container"),
        portfolioElAnimation: !0,
        init: function () {
            console.log(1111111);
            if (!this.initialised) {
                this.initialised = !0, this.queryLoad(), this.checkMobile(), this.pageHeaderTitleAlign(), this.menuHover(), this.stickyMenu(), this.mobileMenuDropdownFix(), this.navbarBtnClassToggle(), this.headerSearchFormFix(), this.headerSearchFormClose(), this.toggleBtn(), this.toggleOverlayClick(), this.fullHeight(), this.sideMenuCollapse(), this.ratings(), this.collapseArrows(), this.scrollToTopAnimation(), this.scrollToClass(), this.menuOnClick(), this.productZoomImage(), this.filterColorBg(), this.selectBox(), this.boostrapSpinner(), this.dateTimePicker(), this.tooltip(), this.popover(), this.servicesHover(), this.countTo(), this.progressBars(), this.registerKnob(), this.flickerFeed(), this.attachBg(), this.parallax(), this.twitterFeed(), this.tabLavaHover(), this.videoBg(), a.fn.owlCarousel && this.owlCarousels(), a.fn.magnificPopup && (this.newsletterPopup(), this.lightBox()), a.fn.mediaelementplayer && this.mediaElement(), a.fn.noUiSlider && this.priceSlider();
                var b = this;
                "function" == typeof imagesLoaded && (imagesLoaded(b.container, function () {
                    b.isotopeActivate(), b.isotopeFilter(), b.infiniteScroll(a("#portfolio-item-container"), ".portfolio-item")
                }), imagesLoaded(b.blogContainer, function () {
                    b.blogMasonry(), b.infiniteScroll(a("#blog-item-container"), ".entry")
                }))
            }
        },
        queryLoad: function () {
            var b = this;
            a.fn.queryLoader2 && a("body").queryLoader2({
                barColor: "#2a2a2a",
                backgroundColor: "rgba(255, 255, 255, 0.1)",
                percentage: !0,
                barHeight: 2,
                minimumTime: 400,
                fadeOutTime: 200,
                onComplete: function () {
                    a(".boss-loader-overlay").fadeOut(400, function () {
                        a(this).remove()
                    }), b.scrollAnimations()
                }
            })
        },
        checkMobile: function () {
            /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ? this.mobile = !0 : this.mobile = !1
        },
        navbarBtnClassToggle: function () {
            a("#main-navbar-container").on("show.bs.collapse hide.bs.collapse", function (b) {
                a(".navbar-toggle").toggleClass("opened")
            })
        },
        toggleBtn: function () {
            var b = this;
            a(".btn-toggle").on("click", function (c) {
                a(this).toggleClass("opened"), a(this).hasClass("side-menu-btn") && b.toggleClassSideMenu(), c.preventDefault()
            })
        },
        toggleOverlayClick: function () {
            a(".boss-menu-overlay").on("click", function (b) {
                a(".side-menu, .btn-toggle.side-menu-btn").toggleClass("opened"), b.preventDefault()
            })
        },
        toggleClassSideMenu: function () {
            a(".side-menu").toggleClass("opened")
        },
        fullHeight: function () {
            a(".fullheight").each(function () {
                var b = a(window).height();
                a(this).css("height", b)
            })
        },
        pageHeaderTitleAlign: function () {
            if (a(".page-header-welcome.fullheight")) {
                var b = a(window).height(), c = a("#header").find(".navbar").outerHeight(), d = a(".page-header-welcome.fullheight").find("h1").height(), e = (b - (c + d + 150)) / 2;
                a(".page-header-welcome.fullheight").find("h1").css("padding-top", e)
            }
        },
        menuHover: function () {
            Modernizr.mq("only all and (min-width: 768px)") && !Modernizr.touch && a.fn.hoverIntent && a("#header").find(".navbar-nav").hoverIntent({
                over: function () {
                    var b = a(this);
                    b.find("ul, div").length && (b.addClass("open"), b.find(".dropdown-toggle").addClass("disabled"))
                }, out: function () {
                    var b = a(this);
                    b.hasClass("open") && (b.removeClass("open"), b.find(".dropdown-toggle").removeClass("disabled"))
                }, selector: "li", timeout: 145, interval: 55
            })
        },
        mobileMenuDropdownFix: function () {
            (Modernizr.mq("only all and (max-width: 767px)") || Modernizr.touch) && a(".navbar-nav").find(".dropdown-toggle").on("click", function (b) {
                var c = a(this).closest("li");
                c.siblings().removeClass("open").find("li").removeClass("open"), c.toggleClass("open"), b.preventDefault(), b.stopPropagation()
            })
        },
        stickyMenu: function () {
            a.fn.waypoint && a(window).width() >= 992 && a(".sticky-menu").waypoint("sticky", {
                stuckClass: "fixed",
                offset: -300
            })
        },
        destroyStickyMenu: function () {
            a.fn.waypoint && a(window).width() <= 991 && a(".sticky-menu").waypoint("unsticky")
        },
        headerSearchFormFix: function () {
            a("[data-target='#header-search-form']").on("click", function (b) {
                a(".sticky-menu").hasClass("fixed") && a("#header-search-form").toggleClass("fixed"), b.preventDefault()
            })
        },
        headerSearchScrollFix: function () {
            if (a("#header-search-form").hasClass("fixed")) {
                var b = a(window).scrollTop();
                300 >= b && a("#header-search-form").removeClass("fixed")
            }
        },
        headerSearchFormClose: function () {
            a("body").on("click", function (b) {
                a("#header-search-form").hasClass("in") && !a(b.target).closest("#header-search-form").length && (a("#header-search-form").collapse("hide").removeClass("fixed"), b.preventDefault())
            })
        },
        sideMenuCollapse: function () {
            a(".side-menu").find(".navbar-nav").find("a").on("click", function (b) {
                a(this).siblings("ul").length && (a(this).siblings("ul").slideToggle(400, function () {
                    a(this).closest("li").toggleClass("open")
                }), b.preventDefault())
            })
        },
        sideMenuScrollbar: function () {
            if (a.fn.niceScroll) {
                var b, c = a(".side-menu");
                if (b = c.hasClass("navbar-default") ? "#7a7a7a" : c.hasClass("navbar-inverse") ? "#9a9a9a" : "#505050", c.data("railalign"))var d = c.data("railalign");
                a(".side-menu-wrapper").niceScroll({
                    zindex: 9999,
                    autohidemode: !0,
                    background: "rgba(0,0,0, 0.03)",
                    cursorcolor: b,
                    cursorwidth: "6px",
                    cursorborder: "1px solid transparent",
                    cursorborderradius: "4px",
                    railalign: d
                })
            }
        },
        collapseArrows: function () {
            a(".category-widget-btn").on("click", function (b) {
                var c = a(this), d = c.closest("li");
                d.hasClass("open") ? d.find("ul").slideUp(400, function () {
                    d.removeClass("open")
                }) : d.find("ul").slideDown(400, function () {
                    d.addClass("open")
                }), b.preventDefault()
            })
        },
        twitterFeed: function () {
            a.fn.tweet && a(".twitter-feed-widget").length && a(".twitter-feed-widget").tweet({
                modpath: "./js/twitter/",
                avatar_size: "",
                count: 3,
                username: "eonythemes",
                loading_text: "searching twitter...",
                join_text: "",
                retweets: !1,
                template: '<div class="twitter-icon"><i class="fa fa-twitter"></i></div><div class="tweet-content">{text}{time}</div>'
            })
        },
        tabLavaHover: function () {
            a.fn.lavalamp && (a(".nav-tabs-lava").lavalamp({
                setOnClick: !0,
                duration: 500,
                autoUpdate: !0
            }), a(".nav-tabs-border").lavalamp({setOnClick: !0, duration: 300, autoUpdate: !0}))
        },
        ratings: function () {
            a.each(a(".ratings-result"), function () {
                var b = a(this), c = b.closest(".ratings").width(), d = a(this).data("result"), e = c / 100 * d;
                a(this).css("width", e)
            })
        },
        owlCarousels: function () {
            a(".owl-carousel.shop-arrivals-carousel-sm").owlCarousel({
                loop: !1,
                margin: 20,
                responsiveClass: !0,
                nav: !0,
                navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
                dots: !1,
                autoplay: !0,
                autoplayTimeout: 1e4,
                responsive: {0: {items: 1}, 480: {items: 2}, 768: {items: 3}}
            }), a(".owl-carousel.shop-popular-carousel-lg").owlCarousel({
                loop: !1,
                margin: 20,
                responsiveClass: !0,
                nav: !0,
                navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
                dots: !1,
                autoplay: !0,
                autoplayTimeout: 1e4,
                responsive: {0: {items: 1}, 480: {items: 2}, 768: {items: 3}, 992: {items: 4}}
            }), a(".owl-carousel.shop-trend-carousel-lg").owlCarousel({
                loop: !1,
                margin: 20,
                responsiveClass: !0,
                nav: !0,
                navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
                dots: !1,
                autoplay: !0,
                autoplayTimeout: 1e4,
                responsive: {0: {items: 1}, 480: {items: 2}, 768: {items: 3}, 992: {items: 4}}
            }), a(".owl-carousel.shop-blog-carousel-lg").owlCarousel({
                loop: !1,
                margin: 20,
                responsiveClass: !0,
                nav: !0,
                navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
                dots: !1,
                autoplay: !0,
                autoplayTimeout: 1e4,
                responsive: {0: {items: 1}, 480: {items: 2}, 768: {items: 3}, 992: {items: 4}}
            }), a(".owl-carousel.shop-latest-carousel").owlCarousel({
                loop: !1,
                margin: 20,
                responsiveClass: !0,
                nav: !0,
                navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
                dots: !1,
                autoplay: !0,
                autoplayTimeout: 1e4,
                responsive: {0: {items: 1}, 480: {items: 2}, 768: {items: 3}, 992: {items: 4}, 1200: {items: 5}}
            }), a(".owl-carousel.shop-popular-carousel").owlCarousel({
                loop: !1,
                margin: 20,
                responsiveClass: !0,
                nav: !0,
                navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
                dots: !1,
                autoplay: !0,
                autoplayTimeout: 1e4,
                responsive: {0: {items: 1}, 480: {items: 2}, 768: {items: 3}, 992: {items: 4}, 1200: {items: 5}}
            }), a(".owl-carousel.shop-latest-carousel-sm").owlCarousel({
                loop: !1,
                margin: 30,
                responsiveClass: !0,
                nav: !0,
                navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
                dots: !1,
                autoplay: !0,
                autoplayTimeout: 1e4,
                responsive: {0: {items: 1}, 480: {items: 2}, 768: {items: 3}, 992: {items: 4}}
            }), a(".owl-carousel.shop-popular-carousel-sm").owlCarousel({
                loop: !1,
                margin: 30,
                responsiveClass: !0,
                nav: !0,
                navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
                dots: !1,
                autoplay: !0,
                autoplayTimeout: 1e4,
                responsive: {0: {items: 1}, 480: {items: 2}, 768: {items: 3}, 992: {items: 4}}
            }), a(".owl-carousel.team-carousel-index9").owlCarousel({
                loop: !1,
                margin: 25,
                responsiveClass: !0,
                nav: !0,
                navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
                dots: !1,
                responsive: {0: {items: 1}, 480: {items: 2}, 768: {items: 3}, 992: {items: 4}}
            }), a(".owl-carousel.our-clients-smaller").owlCarousel({
                loop: !1,
                margin: 10,
                responsiveClass: !0,
                nav: !1,
                navText: ['<i class="fa fa-long-arrow-left">', '<i class="fa fa-long-arrow-right">'],
                dots: !0,
                responsive: {0: {items: 2}, 480: {items: 3}, 768: {items: 4}, 992: {items: 3}, 1200: {items: 3}}
            }), a(".owl-carousel.portfolio-showcase-carousel").owlCarousel({
                loop: !1,
                margin: 0,
                responsiveClass: !0,
                nav: !0,
                navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
                dots: !1,
                responsive: {0: {items: 1}, 480: {items: 2}, 768: {items: 3}, 992: {items: 4}, 1500: {items: 5}}
            }), a(".owl-carousel.our-clients-small").owlCarousel({
                loop: !1,
                margin: 10,
                responsiveClass: !0,
                nav: !1,
                navText: ['<i class="fa fa-long-arrow-left">', '<i class="fa fa-long-arrow-right">'],
                dots: !1,
                responsive: {0: {items: 2}, 480: {items: 3}, 768: {items: 4}, 992: {items: 3}, 1200: {items: 3}}
            }), a(".owl-carousel.home-blogposts-carousel").owlCarousel({
                loop: !0,
                margin: 15,
                responsiveClass: !0,
                nav: !0,
                navText: ['<i class="fa fa-long-arrow-left">', '<i class="fa fa-long-arrow-right">'],
                dots: !1,
                autoplay: !0,
                autoplayTimeout: 15e3,
                responsive: {0: {items: 1}, 480: {items: 2}, 768: {items: 2}, 992: {items: 3}}
            }), a(".owl-carousel.home-clients-carousel").owlCarousel({
                loop: !0,
                margin: 10,
                responsiveClass: !0,
                nav: !1,
                navText: ['<i class="fa fa-long-arrow-left">', '<i class="fa fa-long-arrow-right">'],
                dots: !1,
                autoplay: !0,
                autoplayTimeout: 8e3,
                responsive: {0: {items: 2}, 480: {items: 3}, 768: {items: 4}, 992: {items: 3}}
            }), a(".owl-carousel.home-team-carousel").owlCarousel({
                loop: !0,
                margin: 20,
                responsiveClass: !0,
                nav: !0,
                navText: ['<i class="fa fa-long-arrow-left">', '<i class="fa fa-long-arrow-right">'],
                dots: !1,
                autoplay: !0,
                autoplayTimeout: 1e4,
                responsive: {0: {items: 1}, 480: {items: 2}, 768: {items: 3}, 992: {items: 4}, 1200: {items: 5}}
            }), a(".owl-carousel.our-clients").owlCarousel({
                loop: !1,
                margin: 10,
                responsiveClass: !0,
                nav: !0,
                navText: ['<i class="fa fa-long-arrow-left">', '<i class="fa fa-long-arrow-right">'],
                dots: !1,
                responsive: {0: {items: 2}, 480: {items: 3}, 768: {items: 4}, 992: {items: 5}, 1200: {items: 6}}
            }), a(".owl-carousel.magazine-top-carousel-lg").owlCarousel({
                loop: !0,
                margin: 0,
                responsiveClass: !0,
                nav: !0,
                navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
                dots: !1,
                autoplay: !0,
                autoplayTimeout: 12e3,
                responsive: {0: {items: 1}, 600: {items: 2}, 992: {items: 3}, 1200: {items: 4}}
            }), a(".owl-carousel.magazine-top-carousel").owlCarousel({
                loop: !0,
                margin: 0,
                responsiveClass: !0,
                nav: !1,
                navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
                dots: !0,
                autoplay: !0,
                autoplayTimeout: 12e3,
                responsive: {0: {items: 1}, 600: {items: 2}, 992: {items: 3}}
            }), a(".owl-carousel.mpopular-posts").owlCarousel({
                loop: !0,
                margin: 30,
                responsiveClass: !0,
                nav: !0,
                navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
                dots: !1,
                autoplay: !0,
                autoplayTimeout: 1e4,
                responsive: {0: {items: 1}, 600: {items: 2}, 992: {items: 3}}
            }), a(".owl-carousel.mdontmiss-posts").owlCarousel({
                loop: !0,
                margin: 30,
                responsiveClass: !0,
                nav: !0,
                navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
                dots: !1,
                autoplay: !0,
                autoplayTimeout: 13e3,
                responsive: {0: {items: 1}, 600: {items: 2}, 992: {items: 3}}
            }), a(".owl-carousel.mbigger-posts").owlCarousel({
                loop: !0,
                margin: 0,
                responsiveClass: !0,
                nav: !0,
                navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
                dots: !1,
                autoplay: !0,
                autoplayTimeout: 15e3,
                items: 1
            }), a(".owl-carousel.mmostrated-posts").owlCarousel({
                loop: !0,
                margin: 30,
                responsiveClass: !0,
                nav: !0,
                navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
                dots: !1,
                autoplay: !0,
                autoplayTimeout: 13e3,
                responsive: {0: {items: 1}, 600: {items: 2}, 992: {items: 3}}
            }), a(".owl-carousel.home-blog-carousel").owlCarousel({
                loop: !0,
                margin: 0,
                responsiveClass: !0,
                nav: !1,
                navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
                dots: !0,
                autoplay: !0,
                autoplayTimeout: 12e3,
                responsive: {0: {items: 1}, 600: {items: 2}, 992: {items: 3}, 1500: {items: 4}}
            }), a(".owl-carousel.team-member-carousel").owlCarousel({
                loop: !1,
                margin: 25,
                responsiveClass: !0,
                nav: !0,
                navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
                dots: !1,
                autoplay: !0,
                autoplayTimeout: 1e4,
                responsive: {0: {items: 1}, 480: {items: 2}, 768: {items: 3}, 992: {items: 4}, 1200: {items: 5}}
            }), a(".owl-carousel.team-member-carousel-sm").owlCarousel({
                loop: !1,
                margin: 20,
                responsiveClass: !0,
                nav: !0,
                navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
                dots: !1,
                autoplay: !0,
                autoplayTimeout: 1e4,
                responsive: {0: {items: 1}, 480: {items: 2}, 768: {items: 3}, 992: {items: 4}}
            }), a(".owl-carousel.aboutus-slider").owlCarousel({
                loop: !1,
                margin: 0,
                responsiveClass: !0,
                nav: !1,
                navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
                dots: !0,
                items: 1
            }), a(".owl-carousel.aboutus-carousel").owlCarousel({
                loop: !0,
                margin: 0,
                stagePadding: 50,
                responsiveClass: !0,
                nav: !1,
                navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
                dots: !1,
                autoplay: !0,
                autoplayTimeout: 1e4,
                responsive: {0: {items: 1}, 480: {items: 2}, 992: {items: 3, stagePadding: 100}}
            }), a(".owl-carousel.testimonial-slider").owlCarousel({
                loop: !1,
                margin: 0,
                responsiveClass: !0,
                nav: !1,
                navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
                dots: !0,
                items: 1
            }), a(".owl-carousel.testimonial-carousel").owlCarousel({
                loop: !0,
                margin: 10,
                responsiveClass: !0,
                nav: !0,
                navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
                dots: !1,
                autoplay: !0,
                autoplayTimeout: 9e3,
                responsive: {0: {items: 1}, 992: {items: 2}}
            }), a(".owl-carousel.testimonial-carousel2").owlCarousel({
                loop: !0,
                margin: 10,
                responsiveClass: !0,
                nav: !1,
                navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
                dots: !0,
                autoplay: !0,
                autoplayTimeout: 9e3,
                responsive: {0: {items: 1}, 992: {items: 2}}
            }), a(".owl-carousel.product-gallery-lg").owlCarousel({
                loop: !1,
                margin: 6,
                responsiveClass: !0,
                nav: !1,
                dots: !1,
                autoplay: !0,
                autoplayTimeout: 1e4,
                responsive: {0: {items: 3}, 480: {items: 4}, 768: {items: 5}, 992: {items: 6}}
            }), a(".owl-carousel.related-products-carousel").owlCarousel({
                loop: !1,
                margin: 20,
                responsiveClass: !0,
                nav: !0,
                navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
                dots: !1,
                autoplay: !0,
                autoplayTimeout: 1e4,
                responsive: {0: {items: 1}, 480: {items: 2}, 768: {items: 3}, 992: {items: 4}, 1200: {items: 5}}
            }), a(".owl-carousel.product-gallery-sm").owlCarousel({
                loop: !1,
                margin: 6,
                responsiveClass: !0,
                nav: !1,
                dots: !1,
                autoplay: !0,
                autoplayTimeout: 1e4,
                responsive: {0: {items: 3}, 480: {items: 4}, 768: {items: 4}, 1200: {items: 5}}
            }), a(".owl-carousel.related-products-carousel2").owlCarousel({
                loop: !1,
                margin: 20,
                responsiveClass: !0,
                nav: !0,
                navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
                dots: !1,
                autoplay: !0,
                autoplayTimeout: 1e4,
                responsive: {0: {items: 1}, 480: {items: 2}, 768: {items: 3}, 992: {items: 3}, 1200: {items: 4}}
            }), a(".owl-carousel.portfolio-related-carousel").owlCarousel({
                loop: !1,
                margin: 30,
                responsiveClass: !0,
                nav: !0,
                navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
                dots: !1,
                autoplay: !0,
                autoplayTimeout: 1e4,
                responsive: {0: {items: 1}, 480: {items: 2}, 768: {items: 3}, 992: {items: 4}}
            }), a(".owl-carousel.portfolio-other-carousel").owlCarousel({
                loop: !1,
                margin: 30,
                responsiveClass: !0,
                nav: !0,
                navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
                dots: !1,
                responsive: {0: {items: 1}, 480: {items: 2}, 768: {items: 3}, 992: {items: 4}, 1200: {items: 5}}
            }), a(".owl-carousel.portfolio-similiar-carousel").owlCarousel({
                loop: !1,
                margin: 30,
                responsiveClass: !0,
                nav: !0,
                navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
                dots: !1,
                responsive: {0: {items: 1}, 480: {items: 2}, 768: {items: 3}, 992: {items: 4}}
            }), a(".owl-carousel.portfolio-popular-carousel").owlCarousel({
                loop: !1,
                margin: 2,
                responsiveClass: !0,
                nav: !0,
                navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
                dots: !1,
                responsive: {0: {items: 1}, 480: {items: 2}, 768: {items: 3}, 992: {items: 4}}
            }), a(".owl-carousel.portfolio-rated-carousel").owlCarousel({
                loop: !1,
                margin: 15,
                responsiveClass: !0,
                nav: !0,
                navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
                dots: !1,
                responsive: {0: {items: 1}, 480: {items: 2}, 768: {items: 3}, 992: {items: 4}}
            }), a(".owl-carousel.portfolio-liked-carousel").owlCarousel({
                loop: !1,
                margin: 0,
                responsiveClass: !0,
                nav: !0,
                navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
                dots: !1,
                responsive: {0: {items: 1}, 480: {items: 2}, 768: {items: 3}, 992: {items: 4}}
            }), a(".owl-carousel.our-partners").owlCarousel({
                loop: !1,
                margin: 10,
                responsiveClass: !0,
                nav: !0,
                navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
                dots: !1,
                responsive: {0: {items: 2}, 480: {items: 3}, 768: {items: 4}, 992: {items: 5}, 1200: {items: 6}}
            }), a(".owl-carousel.blog-related-carousel").owlCarousel({
                loop: !1,
                margin: 15,
                responsiveClass: !0,
                nav: !0,
                navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
                dots: !1,
                responsive: {0: {items: 1}, 480: {items: 2}, 1200: {items: 3}}
            }), a(".owl-carousel.wishlist-suggestion-carousel").owlCarousel({
                loop: !1,
                margin: 20,
                responsiveClass: !0,
                nav: !0,
                navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
                dots: !1,
                autoplay: !0,
                autoplayTimeout: 1e4,
                responsive: {0: {items: 1}, 480: {items: 2}, 768: {items: 3}, 992: {items: 4}, 1200: {items: 5}}
            })
        },
        scrollTopBtnAppear: function () {
            var b = a(window).scrollTop(), c = a("#scroll-top");
            b >= 300 ? c.addClass("fixed") : c.removeClass("fixed")
        },
        scrollToAnimation: function (b, c, d) {
            var e = a(this).attr("href"), f = !1;
            if (a(e).length)var g = a(e), h = c ? g.offset().top + c : g.offset().top; else {
                if ("#header" !== e && "#top" !== e && "#wrapper" !== e)return;
                h = 0, f = !0
            }
            (e || f) && (a("html, body").animate({scrollTop: h}, b || 1200), d.preventDefault())
        },
        scrollToTopAnimation: function () {
            var b = this;
            a("#scroll-top").on("click", function (a) {
                b.scrollToAnimation.call(this, 1200, 0, a)
            })
        },
        scrollToClass: function () {
            var b = this;
            a(".scroll-btn, .section-btn, .scrollto").on("click", function (c) {
                var d = a(this).data("offset");
                b.scrollToAnimation.call(this, 1200, d, c)
            })
        },
        menuOnClick: function () {
            var b = this;
            a(".navbar-nav").find("a").on("click", function (c) {
                var d = a(this).attr("href");
                -1 !== d.indexOf("#") && a(d) && b.scrollToAnimation.call(this, 1200, 0, c)
            })
        },
        priceSlider: function () {
            a("#price-range").noUiSlider({
                range: [0, 1e3],
                start: [100, 900],
                handles: 2,
                connect: !0,
                serialization: {to: [a("#price-range-low"), a("#price-range-high")]}
            })
        },
        filterColorBg: function () {
            a(".filter-color-box").each(function () {
                var b = a(this), c = b.data("bgcolor");
                b.css("background-color", c)
            })
        },
        productZoomImage: function () {
            a.fn.elevateZoom && (a("#product-zoom").elevateZoom({
                responsive: !0,
                zoomType: "inner",
                borderColour: "#e1e1e1",
                zoomWindowPosition: 1,
                zoomWindowOffetx: 30,
                cursor: "crosshair",
                zoomWindowFadeIn: 400,
                zoomWindowFadeOut: 250,
                lensBorderSize: 3,
                lensOpacity: 1,
                lensColour: "rgba(255, 255, 255, 0.5)",
                lensShape: "square",
                lensSize: 200,
                scrollZoom: !0
            }), a(".product-gallery").find("a").on("click", function (b) {
                var c = a("#product-zoom").data("elevateZoom"), d = a(this).data("image"), e = a(this).data("zoom-image");
                c.swaptheimage(d, e), b.preventDefault()
            }))
        },
        selectBox: function () {
            a.fn.selectbox && a(".selectbox").selectbox({effect: "fade"})
        },
        boostrapSpinner: function () {
            a.fn.TouchSpin && (a(".vertical-spinner").TouchSpin({verticalbuttons: !0}), a(".horizontal-spinner").TouchSpin())
        },
        dateTimePicker: function () {
            a.fn.datetimepicker && (a(".form-datetime").datetimepicker({
                weekStart: 1,
                todayBtn: 1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                forceParse: 0,
                showMeridian: 1
            }), a(".form-date").datetimepicker({
                weekStart: 1,
                todayBtn: 1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                minView: 2,
                forceParse: 0
            }), a(".form-time").datetimepicker({
                weekStart: 1,
                todayBtn: 1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 1,
                minView: 0,
                maxView: 1,
                forceParse: 0
            }))
        },
        tooltip: function () {
            a.fn.tooltip && a(".add-tooltip").tooltip()
        },
        popover: function () {
            a.fn.popover && a(".add-popover").popover({trigger: "focus"})
        },
        servicesHover: function () {
            a(".service-hover").on("mouseover", function () {
                a.each(a(this).find(".service-icon, .service-title, p"), function () {
                    var b = a(this).data("hover-anim");
                    a(this).addClass("animated " + b)
                })
            }).on("mouseleave", function () {
                a.each(a(this).find(".service-icon, .service-title, p"), function () {
                    var b = a(this).data("hover-anim");
                    a(this).removeClass("animated " + b)
                })
            })
        },
        countTo: function () {
            a.fn.countTo ? a.fn.waypoint ? a(".count").waypoint(function () {
                a(this).countTo()
            }, {
                offset: function () {
                    return a(window).height() - 100
                }, triggerOnce: !0
            }) : a(".count").countTo() : a(".count").each(function () {
                var b = a(this), c = b.data("to");
                b.text(c)
            })
        },
        newsletterPopup: function () {
            document.getElementById("newsletter-popup-form") && jQuery.magnificPopup.open({
                items: {src: "#newsletter-popup-form"},
                type: "inline"
            }, 0)
        },
        lightBox: function () {
            a(".popup-gallery").magnificPopup({
                delegate: ".zoom-item",
                type: "image",
                closeOnContentClick: !1,
                closeBtnInside: !1,
                mainClass: "mfp-with-zoom mfp-img-mobile",
                image: {
                    verticalFit: !0, titleSrc: function (a) {
                        return a.el.attr("title") + '&nbsp;&nbsp;<a class="image-source-link" href="' + a.el.attr("href") + '" target="_blank">source &rarr;</a>'
                    }
                },
                gallery: {enabled: !0},
                zoom: {
                    enabled: !0, duration: 400, opener: function (a) {
                        return a.find("img")
                    }
                }
            }), a(".popup-image").magnificPopup({
                type: "image",
                closeOnContentClick: !0,
                closeBtnInside: !1,
                fixedContentPos: !0,
                mainClass: "mfp-with-zoom mfp-img-mobile",
                image: {verticalFit: !0},
                zoom: {enabled: !0, duration: 400}
            }), a(".popup-iframe").magnificPopup({
                disableOn: 700,
                type: "iframe",
                mainClass: "mfp-fade",
                removalDelay: 160,
                preloader: !1,
                fixedContentPos: !1
            })
        },
        videoBg: function () {
            if (!this.mobile) {
                if (!a.fn.mb_YTPlayer)return;
                a(".player").mb_YTPlayer()
            }
        },
        progressBars: function () {
            var b = this;
            a.fn.waypoint ? a(".progress-animate").waypoint(function () {
                if (a(this).hasClass("circle-progress"))b.animateKnob(); else {
                    var c = a(this), d = a(this).data("width"), e = c.find(".progress-text, .progress-tooltip");
                    c.css({width: d + "%"}, 400), setTimeout(function () {
                        e.fadeIn(400, function () {
                            c.removeClass("progress-animate")
                        })
                    }, 100)
                }
            }, {
                offset: function () {
                    return a(window).height() - 10
                }
            }) : a(".progress-animate").each(function () {
                var b = a(this), c = a(this).data("width"), d = b.find(".progress-text");
                b.css({width: c + "%"}, 400), d.fadeIn(500)
            })
        },
        registerKnob: function () {
            a.fn.knob && (a(".knob").knob({bgColor: "#eaeaea"}), a(".knob.whitebg").knob({bgColor: "#fff"}))
        },
        animateKnob: function () {
            a.fn.knob && a(".knob").each(function () {
                var b = a(this), c = b.closest(".progress-animate"), d = b.data("animateto"), e = b.data("animatespeed");
                b.animate({value: d}, {
                    duration: e, easing: "swing", progress: function () {
                        b.val(Math.round(this.value)).trigger("change")
                    }, complete: function () {
                        c.removeClass("progress-animate")
                    }
                })
            })
        },
        mediaElement: function () {
            a("video, audio").mediaelementplayer()
        },
        scrollAnimations: function () {
            "function" == typeof WOW && new WOW({boxClass: "wow", animateClass: "animated", offset: 0}).init()
        },
        flickerFeed: function () {
            a.fn.jflickrfeed && (a("ul.flickr-widget-two").jflickrfeed({
                limit: 8,
                qstrings: {id: "54297118@N03"},
                itemTemplate: '<li><a href="{{image}}" title="{{title}}"><img src="{{image_s}}" alt="{{title}}" /></a></li>'
            }), a("ul.flickr-widget-three").jflickrfeed({
                limit: 15,
                qstrings: {id: "54297118@N03"},
                itemTemplate: '<li><a href="{{image}}" title="{{title}}"><img src="{{image_s}}" alt="{{title}}" /></a></li>'
            }))
        },
        attachBg: function () {
            var b = a("[data-bgattach]");
            a.each(b, function () {
                a(this).data("bgattach") && a(this).css("background-image", "url(" + a(this).data("bgattach") + ")")
            })
        },
        parallax: function () {
            this.mobile || "object" != typeof skrollr || skrollr.init({forceHeight: !1}), this.mobile && a(".parallax, .parallax-fixed").css("background-attachment", "initial")
        },
        isotopeActivate: function () {
            if (a.fn.isotope) {
                var b = this.container, c = b.data("layoutmode");
                b.isotope({itemSelector: ".portfolio-item", layoutMode: c ? c : "masonry", transitionDuration: 0})
            }
        },
        isotopeReinit: function () {
            a.fn.isotope && (this.container.isotope("destroy"), this.isotopeActivate())
        },
        isotopeFilter: function () {
            var b = this, c = a("#portfolio-filter");
            c.find("a").on("click", function (d) {
                var e = a(this), f = e.attr("data-filter");
                c.find(".active").removeClass("active"), b.container.isotope({
                    filter: f,
                    transitionDuration: "0.8s"
                }), e.closest("li").addClass("active"), d.preventDefault()
            })
        },
        blogMasonry: function () {
            var a = this.blogContainer;
            a.isotope({itemSelector: ".entry", lasyoutMode: "fitRows", masonry: {gutter: 15}, transitionDuration: 0})
        },
        blogMasonryRefresh: function () {
            this.blogContainer.isotope("layout")
        },
        infiniteScroll: function (b, c) {
            a.fn.infinitescroll && (b.infinitescroll({
                navSelector: "#page-nav",
                nextSelector: "#page-nav a:first",
                itemSelector: c,
                loading: {
                    msgText: "Loading Posts...",
                    finishedMsg: "No more post to load.",
                    img: "//eonythemes.com/themes/t/images/load.GIF"
                }
            }, function (c) {
                b.isotope("appended", a(c)).isotope("layout")
            }), a("#infinite-trigger").length && (a(window).unbind(".infscr"), a("#infinite-trigger").on("click", function (a) {
                b.infinitescroll("retrieve"), a.preventDefault()
            }), a(document).ajaxError(function (b, c, d) {
                404 == c.status && a("a#infinite-trigger").addClass("disabled")
            })))
        }
    };
    b.init(), a(window).on("load", function () {
        b.sideMenuScrollbar(), b.blogContainer.length && b.blogMasonryRefresh()
    }), a(window).on("scroll", function () {
        b.scrollTopBtnAppear(), b.headerSearchScrollFix()
    }), a.event.special.debouncedresize ? a(window).on("debouncedresize", function () {
        b.fullHeight(), b.destroyStickyMenu()
    }) : a(window).on("resize", function () {
        b.fullHeight(), b.destroyStickyMenu()
    });
    var c = a(".preview-panel"), d = a("#preview-panel-btn"), e = a("#preview-panel-carousel"), f = a("#color-scheme"), g = a("#wrapper"), h = a(".preview-color-box"), i = a(".preview-layout-box"), j = a(".preview-pattern-box");
    d.on("click", function (a) {
        c.toggleClass("open"), a.preventDefault()
    }), c.on("click", function (a) {
        a.stopPropagation()
    }), a('body:not(".preview-panel")').on("click", function (a) {
        c.hasClass("open") && c.removeClass("open")
    }), e.find(".carousel-indicators  li").on("click", function () {
        var b = a(this).data("slide-to");
        e.carousel(b), e.find(".carousel-indicators li.active").removeClass("active"), a(this).addClass("active")
    }), h.on("click", function (b) {
        if (!a(this).hasClass("active")) {
            a(".preview-color-box.active").removeClass("active");
            var c = a(this).data("stylesheet");
            f.attr("href", "css/colors/" + c), a(this).addClass("active"), a.cookie("stylesheet", c, {expires: 1})
        }
        b.preventDefault()
    }), i.on("click", function (c) {
        if (!a(this).hasClass("active")) {
            a(".preview-layout-box.active").removeClass("active");
            var d = a(this).data("layout");
            "boxed" === d ? g.removeClass("boxed-long").addClass("boxed") : "boxed-long" === d ? g.removeClass("boxed").addClass("boxed-long") : g.removeClass("boxed boxed-long"), a.fn.isotope && b.isotopeReinit(), a(this).addClass("active"), a.cookie("layout", d, {expires: 1})
        }
        c.preventDefault()
    }), j.on("click", function (b) {
        if (!a(this).hasClass("active")) {
            a(".preview-pattern-box.active").removeClass("active");
            var c = a(this).data("pattern");
            a("body").removeClass().addClass(c), a(this).addClass("active"), a.cookie("pattern", c, {expires: 1})
        }
        b.preventDefault()
    }), a(function () {
        a.cookie("stylesheet") && f.attr("href", "css/colors/" + a.cookie("stylesheet")), a.cookie("layout") && (a(".preview-color-box.active").removeClass("active"), "boxed" === a.cookie("layout") ? g.removeClass("boxed-long").addClass("boxed") : "boxed-long" === a.cookie("layout") ? g.removeClass("boxed").addClass("boxed-long") : g.removeClass("boxed boxed-long"), b.isotopeReinit()), a.cookie("pattern") && a("body").removeClass().addClass(a.cookie("pattern")), a("#preview-reset-btn").on("click", function (b) {
            a.cookie("stylesheet") && a.removeCookie("stylesheet"), a.cookie("layout") && a.removeCookie("layout"), a.cookie("pattern") && a.removeCookie("pattern"), location.reload(), b.preventDefault()
        })
    })
}(jQuery), function (a) {
    "function" == typeof define && define.amd ? define(["jquery"], a) : "object" == typeof exports ? module.exports = a(require("jquery")) : a(jQuery)
}(function (a) {
    function b(a) {
        return h.raw ? a : encodeURIComponent(a)
    }

    function c(a) {
        return h.raw ? a : decodeURIComponent(a)
    }

    function d(a) {
        return b(h.json ? JSON.stringify(a) : String(a))
    }

    function e(a) {
        0 === a.indexOf('"') && (a = a.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, "\\"));
        try {
            return a = decodeURIComponent(a.replace(g, " ")), h.json ? JSON.parse(a) : a
        } catch (b) {
        }
    }

    function f(b, c) {
        var d = h.raw ? b : e(b);
        return a.isFunction(c) ? c(d) : d
    }

    var g = /\+/g, h = a.cookie = function (e, g, i) {
        if (arguments.length > 1 && !a.isFunction(g)) {
            if (i = a.extend({}, h.defaults, i), "number" == typeof i.expires) {
                var j = i.expires, k = i.expires = new Date;
                k.setMilliseconds(k.getMilliseconds() + 864e5 * j)
            }
            return document.cookie = [b(e), "=", d(g), i.expires ? "; expires=" + i.expires.toUTCString() : "", i.path ? "; path=" + i.path : "", i.domain ? "; domain=" + i.domain : "", i.secure ? "; secure" : ""].join("")
        }
        for (var l = e ? void 0 : {}, m = document.cookie ? document.cookie.split("; ") : [], n = 0, o = m.length; o > n; n++) {
            var p = m[n].split("="), q = c(p.shift()), r = p.join("=");
            if (e === q) {
                l = f(r, g);
                break
            }
            e || void 0 === (r = f(r)) || (l[q] = r)
        }
        return l
    };
    h.defaults = {}, a.removeCookie = function (b, c) {
        return a.cookie(b, "", a.extend({}, c, {expires: -1})), !a.cookie(b)
    }
});