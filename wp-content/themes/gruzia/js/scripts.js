(function($) {
    "use strict";

    $(document).ready(function() {

        // Page Loader // --------------------------
        setTimeout(function() {
            $('body').addClass('loaded');
        }, 3000);

        var arrowHtml = '<img src="/wp-content/themes/gruzia/images/arrow.svg">';


        // Pasta Price Item Slide  // --------------------------
        //     START   //
        var sync1 = $("#item-images");
        var sync2 = $("#item-thumbs");
        var slidesPerPage = 4; //globaly define number of elements per page
        var syncedSecondary = true;

        sync1.owlCarousel({
            items: 1,
            loop: true,
            smartSpeed: 1000,
            nav: true,
            dots: false,
            responsiveRefreshRate: 200,
            responsive: {
                0: {
                    items: 1
                },
            },
            navText: ["<i class='owl-prev-icon owl-button-icons'></i>", "<i class='owl-next-icon owl-button-icons'></i>"],
        }).on('changed.owl.carousel', syncPosition);

        sync2
            .on('initialized.owl.carousel', function() {
                sync2.find(".owl-item").eq(0).addClass("current");
            })

            .owlCarousel({
                items: slidesPerPage,
                nav: false,
                dots: false,
                smartSpeed: 600,
                slideSpeed: 500,
                responsive: {
                    0: {
                        items: 4
                    },
                    479: {
                        items: 4
                    },
                    768: {
                        items: 4
                    },
                    979: {
                        items: 5
                    },
                    1199: {
                        items: 5
                    },
                },
                responsiveRefreshRate: 100,

            }).on('changed.owl.carousel', syncPosition2);

        function syncPosition(el) {
            //if you set loop to false, you have to restore this next line
            //var current = el.item.index;

            //if you disable loop you have to comment this block
            var count = el.item.count - 1;
            var current = Math.round(el.item.index - (el.item.count / 2) - .5);

            if (current < 0) {
                current = count;
            }
            if (current > count) {
                current = 0;
            }

            //end block

            sync2
                .find(".owl-item")
                .removeClass("current")
                .eq(current)
                .addClass("current");
            var onscreen = sync2.find('.owl-item.active').length - 1;
            var start = sync2.find('.owl-item.active').first().index();
            var end = sync2.find('.owl-item.active').last().index();

            if (current > end) {
                sync2.data('owl.carousel').to(current, 300, true);
            }
            if (current < start) {
                sync2.data('owl.carousel').to(current - onscreen, 300, true);
            }
        }

        function syncPosition2(el) {
            if (syncedSecondary) {
                var number = el.item.index;
                sync1.data('owl.carousel').to(number, 400, true);
            }
        }

        sync2.on("click", ".owl-item", function(e) {
            e.preventDefault();
            var number = $(this).index();
            sync1.data('owl.carousel').to(number, 600, true);
        });
        //  END  //


        // Old School Clock // --------------------------
        // https://css-tricks.com/css3-clock/
        setInterval(function() {
            var seconds = new Date().getSeconds();
            var sdegree = seconds * 6;
            var srotate = "rotate(" + sdegree + "deg)";

            $("#sec").css({
                "-moz-transform": srotate,
                "-webkit-transform": srotate,
                "transform": srotate
            });

        }, 1000);

        setInterval(function() {
            var hours = new Date().getHours();
            var mins = new Date().getMinutes();
            var hdegree = hours * 30 + (mins / 2);
            var hrotate = "rotate(" + hdegree + "deg)";

            $("#hour").css({
                "-moz-transform": hrotate,
                "-webkit-transform": hrotate,
                "transform": hrotate
            });

        }, 1000);

        setInterval(function() {
            var mins = new Date().getMinutes();
            var mdegree = mins * 6;
            var mrotate = "rotate(" + mdegree + "deg)";

            $("#min").css({
                "-moz-transform": mrotate,
                "-webkit-transform": mrotate,
                "transform": mrotate
            });

        }, 1000);


        // Adds ios class to html tag // --------------------------
        var deviceAgent = navigator.userAgent.toLowerCase();
        var agentID = deviceAgent.match(/(iphone|ipod|ipad)/);
        if (agentID) {

            $('.video-background').addClass('ios');

        };


        // Footer Button to Top // --------------------------
        //
        $('.scrollTopButton').on("click", function() {
            $("body,html").animate({
                scrollTop: 0
            }, 1200);
            return false;
        });

        // Header background image //
        $(".header-background").each(function() {
            var attr = $(this).attr('data-image-src');
            var $item = $(this);

            if (typeof attr !== typeof undefined && attr !== false) {
                $(this).css('background', 'url(' + attr + ') no-repeat');
            }
            $item.css({
                'background-position': 'center',
                'background-size': 'cover'
            });
        });

        $(".promotions .promotions-slider").owlCarousel({
            loop: false,
            nav: true,
            navText: [arrowHtml, arrowHtml],
            dots: false,
            autoplay: false,
            smartSpeed: 800,
            stagePadding: 110,
            responsive: {
                0: {
                    items: 1,
                    stagePadding: 70,
                    margin: 6
                },
                400: {
                    items: 1,
                    stagePadding: 80,
                    margin: 8
                },
                480: {
                    items: 1,
                    stagePadding: 100,
                    margin: 10
                },
                630: {
                    items: 1,
                    stagePadding: 130,
                    margin: 10
                },
                768: {
                    items: 2,
                    stagePadding: 80,
                    margin: 10
                },
                830: {
                    items: 2,
                    stagePadding: 100,
                    margin: 10
                },
                992: {
                    items: 2,
                    stagePadding: 130,
                    margin: 10,
                },
                1200: {
                    items: 3,
                    stagePadding: 82,
                    margin: 10,
                },
                1480: {
                    items: 3,
                    stagePadding: 110,
                    margin: 18,
                }
            },
        });

        $(".main-slider .owl-carousel").owlCarousel({
            loop: true,
            nav: false,
            dots: true,
            autoplay: true,
            autoplayTimeout: 12000,
            smartSpeed: 800,
            items: 1
        });

        $(".category-slider .owl-carousel").owlCarousel({
            nav: true,
            dots: false,
            loop: false,
            rewindNav: false,
            autoplay: false,
            smartSpeed: 800,
            navText: [arrowHtml, arrowHtml],
            responsive: {
                300: {
                    items: 1,
                    stagePadding: 62,
                    margin: 10,
                },
                350: {
                    items: 1,
                    stagePadding: 76,
                    margin: 11,
                },
                380: {
                    items: 1,
                    stagePadding: 84,
                    margin: 11,
                },
                415: {
                    items: 2,
                    stagePadding: 36,
                    margin: 11,
                },
                480: {
                    items: 2,
                    stagePadding: 62,
                    margin: 11,
                },
                576: {
                    items: 2,
                    stagePadding: 82,
                    margin: 12,
                },
                630: {
                    items: 3,
                    stagePadding: 58,
                    margin: 11,
                },
                768: {
                    items: 4,
                    stagePadding: 46,
                    margin: 12,
                },
                992: {
                    items: 5,
                    stagePadding: 56,
                    margin: 14,
                },
                1480: {
                    items: 5,
                    stagePadding: 82,
                    margin: 17,
                }
            },
        });

        $(".restaurant-images.owl-carousel").owlCarousel({
            nav: true,
            dots: false,
            rewindNav: false,
            navText: [arrowHtml, arrowHtml],
            autoplay: false,
            smartSpeed: 800,
            items: 4,
            margin: 17,
            responsive: {
                0: {
                    items: 1,
                    stagePadding: 65,
                    margin: 12
                },
                400: {
                    items: 1,
                    stagePadding: 90,
                    margin: 12
                },
                480: {
                    items: 3,
                    stagePadding: 30,
                    margin: 12
                },
                540: {
                    items: 3,
                    stagePadding: 30,
                    margin: 15
                },
                768: {
                    items: 3,
                    stagePadding: 110,
                    margin: 15
                },
                992: {
                    items: 3,
                    stagePadding: 130,
                },
                1200: {
                    items: 3,
                    stagePadding: 120,
                },
                1540: {
                    items: 4,
                    stagePadding: 110,
                }
            },
        });
    });

    var trigger = $('.nav-mobile-btn'),
        overlay = $('.overlay');

    $('.nav-mobile-btn, .widget-mobile-menu #hide-side-panel-btn').click(function() {
        if ($('.widget-mobile-menu .side-panel').hasClass('is-show')) {
            $('#hide-side-panel-btn').css('visibility', 'hidden');
            $('.widget-mobile-menu .side-panel, .widget-mobile-menu .header-overlay').removeClass('is-show');
            $('html').removeClass('side-panel-is_show');
        } else {
            $('#hide-side-panel-btn').css('visibility', 'visible');
            $('.widget-mobile-menu .side-panel, .widget-mobile-menu .header-overlay').addClass('is-show');
            $('html').addClass('side-panel-is_show');
        }
    });

    $('.widget-mobile-menu .header-overlay').click(function() {
        $('#hide-side-panel-btn').css('visibility', 'hidden');
        $('.widget-mobile-menu .side-panel, .widget-mobile-menu .header-overlay').removeClass('is-show');
        $('html').removeClass('side-panel-is_show');
    });

    $('#menu-mobilnoe-menyu > li').click(function(e) {
        var closestLi = $(e.target).closest('li');
        var isTopLi = $(e.target).is('#menu-mobilnoe-menyu > li') || closestLi.is('#menu-mobilnoe-menyu > li');
        var hasChild = closestLi.find('.dropdown-icon').length;

        if (isTopLi && hasChild) {
            e.stopPropagation();
            e.preventDefault();

            $(this).toggleClass('is-opened');
        }
    });

    $('#footer-top .block.col-sm-3').click(function() {
        if ($(window).width() <= 767) {
            $(this).toggleClass('is-closed');
        }
    });

    $(document).on('click', '.page-login .account-buttons button:not(.active)', function() {
        $('.page-login .woocommerce-form').removeClass('active');
        $('.page-login .account-buttons button').removeClass('active');
        $(this).addClass('active');

        if ($(this).is('.login-button')) {
            $('.page-login .form-login').addClass('active');
        } else {
            $('.page-login .form-register').addClass('active');
        }
    });

    $(document).on('click', '.load-more button', function() {
        if ($(this).hasClass('loading')) {
            return;
        }

        var matchPage = window.location.href.match(/page\/([\d]+)\//i);
        var page = matchPage === null ? 2 : parseInt(matchPage[1]) + 1;
        var matchCategory = window.location.href.match(/category\/([^\/]+)/i);
        var category = matchCategory ? matchCategory[1] : null;
        var url = location.protocol + '//' + location.host + location.pathname;
        var tagIdsStr = new URLSearchParams(window.location.search).get('tag_id');
        var tagIds = tagIdsStr === null ? [] : tagIdsStr.split(',');

        if (url.match(/page/)) {
            url = url.replace(/page.*/, 'page/');
        } else {
            url = url + 'page/';
        }

        $(this).addClass('loading');

        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            method: 'post',
            data: {
                page: page,
                category: category,
                link: url,
                tag_id: tagIds,
                action: 'load_more'
            },
            success: function(resp) {
                if (resp.success) {
                    $('.products-list .products-grid').append(resp.data.content);
                    $('.woocommerce-pagination').remove();
                    $('.load-more').replaceWith(resp.data.pagination);

                    if (matchPage === null) {
                        url += page + '/';
                    } else {
                        url = window.location.href.replace(/page\/\d+/i, 'page/' + page);
                    }

                    if (tagIds.length) {
                        url = new URL(url);
                        url.searchParams.set('tag_id', tagIds.join(','));
                    }

                    window.history.replaceState({}, "", url.toString());
                }
            }
        });
    });

    var extraProdsShown = false;

    $('body').click(function(e) {
        var isClosebtn = $(e.target).is('.extra-products .close');
        var isShowPopup = $(e.target).is('.show-extra-products');
        var isPopupWrapper = $(e.target).is('.extra-products-popup');
        var isPopupChild = $(e.target).closest('.extra-products-popup').length > 0;
        var isCheckoutSubmit = $(e.target).is('#place_order');

        if (isClosebtn || (!isShowPopup && !isPopupWrapper && !isPopupChild && !isCheckoutSubmit)) {
            $('.extra-products-popup').fadeOut(250);
        }
    });

    $(document).on('click', '.extra-prods-cats li', function() {
        var dataCatId = $(this).attr('data-cat-id');

        $('.ep-item').css('display', 'none');
        $('.ep-item[data-cat-id="' + dataCatId + '"]').css('display', 'flex');

        $('.extra-prods-cats li').removeClass('active');
        $(this).addClass('active');
    });

    $(document).on('click', '#place_order', function(e) {
        if ($('.extra-products-popup').length && !extraProdsShown) {
            e.preventDefault();

            extraProdsShown = true;
            $('.extra-products-popup').fadeIn(250);
        }
    });

    $('.gallery-tabs li').click(function() {
        var i = $(this).data('index');

        $('.gallery-tabs li').removeClass('active');
        $('.gallery-images-block').css('display', 'none');

        $(this).addClass('active');
        $('.gallery-images-block[data-index=' + i + ']').css('display', 'flex');

        return false;
    });

    if (jQuery('.gallery-image-item, .restaurant-images .image-wrapper').length) {
        jQuery('.gallery-image-item, .restaurant-images .image-wrapper').magnificPopup({
            type: 'image',
            gallery: {
                enabled: true
            },
            mainClass: 'mfp-with-zoom',
            image: {
                titleSrc: function(item) {
                    return item.el.find('img').attr('alt');
                }
            },
            zoom: {
                enabled: true,
                duration: 300,
                easing: 'ease-in-out',
                opener: function(openerElement) {
                    return openerElement.is('img') ? openerElement : openerElement.find('img');
                }
            }
        });
    }

    var fullHeight = $('.page-descr-content').height();

    if ($(window).width() <= 415 && fullHeight > 130) {
        $('.page-descr-content').css('max-height', '109px');
        $('.page-description').addClass('is_closed');
    } else if ($(window).width() <= 575 && fullHeight > 140) {
        $('.page-descr-content').css('max-height', '120px');
        $('.page-description').addClass('is_closed');
    } else if ($(window).width() <= 767 && fullHeight > 130) {
        $('.page-descr-content').css('max-height', '109px');
        $('.page-description').addClass('is_closed');
    } else if ($(window).width() > 767 && fullHeight > 140) {
        $('.page-descr-content').css('max-height', '120px');
        $('.page-description').addClass('is_closed');
    }

    $('.page-descr-content').removeClass('opened');

    $(document).on('click', '.page-description.is_closed', function() {
        $('.page-descr-content').css('max-height', fullHeight + 'px');

        $(this).removeClass('is_closed');
        $(this).addClass('is_opened');
    });

    $(document).on('click', '.page-description.is_opened', function() {
        if ($(window).width() <= 415) {
            $('.page-descr-content').css('max-height', '109px');
        } else if ($(window).width() <= 575) {
            $('.page-descr-content').css('max-height', '120px');
        } else if ($(window).width() <= 767) {
            $('.page-descr-content').css('max-height', '109px');
        } else {
            $('.page-descr-content').css('max-height', '120px');
        }

        $(this).addClass('is_closed');
        $(this).removeClass('is_opened');
    });

    $(document).on('click', '.quantity-minus', function(e) {
        e.preventDefault();

        var cartItemKey = $(this).data('cart_item_key');
        var quantity = +$(this).next('.quantity-number').find('span').html() - 1;

        removeFromCart(cartItemKey, quantity);
    });

    $(document).on('click', '.take_btn', function(e) {
        e.preventDefault();

        var cartItemKey = $(this).data('cart_item_key');
        var quantity = $(this).next('input').val() - 1;

        removeFromCart(cartItemKey, quantity);
    });

    function removeFromCart(cartItemKey, quantity) {
        if (quantity >= 0) {
            $.ajax({
                type: 'POST',
                url: '/wp-admin/admin-ajax.php',
                data: {
                    action: 'remove_cart_item',
                    key: cartItemKey,
                    quantity: quantity
                },
                dataType: 'json',
                success: function(response) {
                    if (response.error) {
                        return;
                    }

                    if (response.fragments) {
                        $.each(response.fragments, function(key) {
                            $(key)
                                .addClass('updating')
                                .fadeTo('400', '0.6')
                                .block({
                                    message: null,
                                    overlayCSS: {
                                        opacity: 0.6
                                    }
                                });
                        });

                        $.each(response.fragments, function(key, value) {
                            $(key).replaceWith(value);
                            $(key).stop(true).css('opacity', '1').unblock();
                        });

                        $(document.body).trigger('wc_fragments_loaded');
                    }

                    $('.quantity-minus[data-cart_item_key=' + cartItemKey + ']').next('.quantity-number').find('span').html(quantity);

                    $('body').trigger('removed_from_cart2');
                },
            });
        }
    }

    $(document).on('click', '.clear_cart', function(e) {
        e.preventDefault();

        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            type: 'POST',
            data: {
                action: 'clear_cart'
            },
            dataType: 'json',
            success: function(response) {
                if (response.error) {
                    return;
                }

                if (response.fragments) {
                    $.each(response.fragments, function(key) {
                        $(key)
                            .addClass('updating')
                            .fadeTo('400', '0.6');
                    });

                    $.each(response.fragments, function(key, value) {
                        $(key).replaceWith(value);
                        $(key).stop(true).css('opacity', '1').unblock();
                    });

                    $(document.body).trigger('wc_fragments_loaded');
                }
            },
        });
    });

    $(document).on('submit', '.contacts-content form.column_right', function(e) {
        e.preventDefault();

        if ($('.contacts-content .column_right button').hasClass('loading')) {
            return;
        }

        var name = $('.column_right input[name=name]').val().trim();
        var phone = $('.column_right input[name=phone]').val().trim();
        var email = $('.column_right input[name=email]').val().trim();
        var message = $('.column_right textarea').val().trim();

        $('.contacts-content .column_right button').addClass('loading');

        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            method: 'POST',
            data: {
                action: 'new_callback',
                name: name,
                phone: phone,
                email: email,
                message: message
            },
            dataType: 'json',
            success: function(resp) {
                if (resp.data.length) {
                    $.each(resp.data, function(key, item) {
                        $('.column_right input[name=' + item + '] + .input_error').css('display', 'block');
                    });

                    $([document.documentElement, document.body]).animate({
                        scrollTop: $(".column_right").offset().top - 80
                    }, 400)
                } else {
                    var colRight = $('.contacts-content .column_right');
                    colRight.height(colRight.height());
                    colRight.html('<div class="message_sent">Сообщение отправлено.</div>');
                }

                $('.contacts-content .column_right button').removeClass('loading');
            }
        });
    });

    $('.column_right input, .column_right textarea').on('input', function() {
        $(this).next('.input_error').css('display', 'none');
    });

    $('.cart-quantity__action').click(function() {
        var direction = $(this).data('direction');
        var value = $('.single-product .cart-quantity__value').val();

        value = direction == 'plus' ? +value + 1 : value - 1;
        value = value > 1 ? value : 1;
        $('.single-product .cart-quantity__value').val(value);
    });

    var timer = 0;

    $(document).on('added_to_cart removed_from_cart removed_from_cart2', function(e, fragments, hash, btn) {
        $('.cart-button').addClass('added_new');

        if (timer) {
            clearTimeout(timer);
        }

        timer = setTimeout(function() {
            $('.cart-button').removeClass('added_new');
        }, 1200);

        if ($('.cart-items .item-actions').length) {
            $('#to-checkout').css('display', 'block');
        } else {
            $('#to-checkout').css('display', 'none');
        }

        if ($('body.woocommerce-checkout').length) {
            $(document.body).trigger('update_checkout');
        }
    });

    $(document).on('added_to_cart', function(e, fragments, hash, btn) {
        if ($('#single-product').length && $('.extra-products-popup').length && !extraProdsShown) {
            extraProdsShown = true;
            $('.extra-products-popup').fadeIn(250);
        }
    });

    $('.show-extra-products').click(function(e) {
        e.preventDefault();
        $('.extra-products-popup').fadeIn(250);
    });

    $('.cart-button').click(function() {
        $('head').append('<style class="cart-style">.cart-wrapper {pointer-events:all !important;opacity:1 !important;}.cart-block {transform: translate(0, 0) !important;}</style>');
    });

    $(document).on('click', '.cart-header .close-btn, .cart-wrapper', function(e) {
        if ($(e.target).is('.cart-header .close-btn, .cart-wrapper')) {
           $('.cart-style').remove();
        }
    });

    var scrollCart = 0;

    $(document).on('click', '.quantity-minus, .quantity-plus', function() {
        scrollCart = $('.widget_shopping_cart_content').scrollTop();
    });

    $(document).on('added_to_cart removed_from_cart', function() {
        $('.widget_shopping_cart_content').scrollTop(scrollCart);
    });

    $(document).on('click', '.cart-item .remove-btn', function() {
        var cartItemKey = $(this).data('cart_item_key');

        removeFromCart(cartItemKey, 0);
    });

    $(window).on( 'pageshow' , function(e) {
        setTimeout(function() {
            $(document.body).trigger( 'wc_fragment_refresh' );
        }, 10);
    });

    var prodByTagLoading = false;

    $('.product-tags li').click(function() {
        if (prodByTagLoading) return;
        prodByTagLoading = true;

        $('.products-grid > .product')
            .fadeTo('100', '0.9')
            .block({ message: null, overlayCSS: {opacity: 0.5} });

        var matchCategory = window.location.href.match(/category\/([^\/]+)/i);
        var category = matchCategory ? matchCategory[1] : null;
        var url = location.protocol + '//' + location.host + location.pathname;
        url = url.replace(/page\/.*$/i, '');
        var tagIds = [];
        var tagId = $(this).data('tag-id');

        $('.product-tags li.active').each(function(el) {
            if (tagId != $(this).data('tag-id')) {
                tagIds.push($(this).data('tag-id'));
            }
        });

        if (!$(this).is('.active')) tagIds.push(tagId);

        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            method: 'post',
            data: {action: 'get_product_by_tag', tag_id: tagIds, link: url, category},
            dataType: 'json',
            success: function(resp) {
                $('.woocommerce-pagination').remove();

                $.each(resp.data, function(selector, html) {
                    $(selector).replaceWith(html);
                });

                $('.product-tags li').removeClass('active');

                $.each(tagIds, function(key, tagId) {
                    $('.product-tags li[data-tag-id="'+ tagId +'"]').addClass('active');
                });

                if (tagIds.length) {
                    var urlObj = new URL(url);
                    urlObj.searchParams.set('tag_id', tagIds.join(','));

                    window.history.replaceState({}, "", urlObj.toString());
                } else {
                    window.history.replaceState({}, "", url);
                }

                prodByTagLoading = false;
            }
        });
    });

    $('.footer-row h5').click(function() {
        $(this).toggleClass('is-closed');
    })

    /* (ANCHOR / Якорь) Scroll Button in main slider START */
    $('.owl-scroll span').on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({ scrollTop: $('#category-slider').offset().top}, 500, 'linear');
    });
    /* (ANCHOR / Якорь) Scroll Button in main slider END */

    /* Filter By Attr start*/
    $('.filterAttr a').click(function(e) {
        e.preventDefault();
        let _currTarget = $(e.target);
        let attr = [];
        _currTarget.toggleClass('active');
        $('.filterAttr a.active').each(function(index,item){
            attr[index] = $(item).data('attr');
        });
  
        if ($('.loading-overlay').hasClass('loading')) {
          return;
        }
          
        var matchPage = window.location.href.match(/page\/([\d]+)/i);
        var page = matchPage === null ? 2 : parseInt(matchPage[1]);
        var matchCategory = $('body')[0].classList.toString().match(/term-([^\s]+)/i);
        var category = matchCategory ? matchCategory[1] : null;
        var link = location.protocol + '//' + location.host + location.pathname + 'page/';
        
        if (link.match(/page/)) {
          link = link.replace(/page.*/, 'page/');
        } else {
          link = link + 'page/';
        }
        
        attr = attr.toString();
  
        $('.loading-overlay').css('display','flex');
  
        $.ajax({
          url: '/wp-admin/admin-ajax.php',
          method: 'post',
          data: {page: page, category: category, link: link, pa_tip: attr, action: 'filter_by_attr'},
          success: function(resp) {
            if (resp.success) {
              $('.products-grid').html(resp.data.content);
              // $('.woocommerce-pagination').remove();
              // $('.products-grid').append(resp.data.pagination);
  
              if (!resp.data.isset_more) {
                $('.woocommerce .load-more').css('display', 'none');
              }
              $('.loading-overlay').css('display','none');
             
            }
          }
        });
      });
    /* Filter By Attr end*/
    
}(jQuery));