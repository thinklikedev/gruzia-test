if (window.innerWidth > 600) {
    var html = '<style>body {margin:0;padding: 0;background:#151515}iframe{width:600px;height:100vh;margin:0 auto;display:block}</style>';
    html += '<iframe src="'+ window.location +'"></iframe>';
    document.querySelector('body').innerHTML = html;
}

(function($) {
    $(function () {
        function getCartProducts() {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; cart_products=`);

            var products = {};

            if (parts.length === 2) {
                products = JSON.parse(decodeURIComponent(parts.pop().split(';').shift()));
            }

            return products;
        }

        function addToCart(productId, quantity = 1, sumUp = false) {
            if (isNaN(quantity) || quantity < 1) {
                console.log('Wrong quantity parameter');
                return;
            }

            let products = getCartProducts();

            if (sumUp && products.hasOwnProperty(productId)) {
                products[productId] += quantity;
            } else {
                products[productId] = quantity;
            }

            let totalQuantity = products[productId];

            products = JSON.stringify(products);
            document.cookie = `cart_products=${products}; path=/; max-age=259200; /`;

            return totalQuantity;
        }

        function removeFromCart(productId, quantity = 0) {
            if (isNaN(quantity)) {
                console.log('Wrong quantity parameter');
                return;
            }

            let products = getCartProducts();

            if (products.hasOwnProperty(productId)) {
                if (quantity == 0) {
                    delete products[productId];
                } else {
                    products[productId] -= quantity;

                    if (products[productId] <= 0) {
                        delete products[productId];
                    }
                }
            }

            let totalQuantity = products.hasOwnProperty(productId) ? products[productId] : 0;

            products = JSON.stringify(products);
            document.cookie = `cart_products=${products}; path=/; max-age=259200; /`;

            return totalQuantity;
        }

        function clearCart() {
            document.cookie = `cart_products={}; path=/; max-age=259200; /`;
        }

        function getOrders() {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; qr_orders=`);

            var orders = {};

            if (parts.length === 2) {
                orders = JSON.parse(decodeURIComponent(parts.pop().split(';').shift()));
            }

            return orders;
        }

        function getLastOrder() {
            var orders = getOrders();

            if (Object.keys(orders).length !== 0) {
                return Object.keys(orders)[Object.keys(orders).length - 1];
            }

            return false;
        }

        function removeOrder(orderId) {
            var orders = getOrders();

            if (orders.hasOwnProperty(orderId)) {
                delete orders[orderId];
                orders = JSON.stringify(orders);
                document.cookie = `qr_orders=${orders}; path=/; max-age=259200; /`;
            }
        }

        function createOrder() {
            var orders = getOrders();
            var orderId = getLastOrder();
            var products = getCartProducts();
            var addToOldOrder = false;

            if (Object.keys(products).length === 0) {
                return false;
            }

            if (orderId) {
                addToOldOrder = confirm('Добавить к предыдущему заказу?');
            }

            var products = getCartProducts();

            if (addToOldOrder) {
                $.each(products, function(id, quantity) {
                    if (orders[orderId].hasOwnProperty(id)) {
                        orders[orderId][id] = orders[orderId][id] + quantity;
                    } else {
                        orders[orderId][id] = quantity;
                    }
                });
            } else {
                orderId = Date.now();
                orders[orderId] = products;
            }

            orders = JSON.stringify(Object.assign({}, orders));
            document.cookie = `qr_orders=${orders}; path=/; max-age=259200;`;

            return true;
        }

        function clearOrders() {
            document.cookie = `qr_orders={}; path=/; max-age=259200; /`;
        }

        function changeCartCounter() {
            var count = 0;

            $.each(getCartProducts(), function(key, val) {
                count += val;
            });

            $('footer .cart .counter').html(count);
            $('footer .cart .counter').css('display', count ? 'block' : 'none');
        }

        function changeOrdersCounter() {
            var count = Object.keys(getOrders()).length;

            $('footer .orders .counter').html(count);
            $('footer .orders .counter').css('display', count ? 'block' : 'none');
        }

        $('.product-add-to-cart, .product-quantity .add').click(function() {
            var parent = $(this).closest('.product-actions');
            var productId = parent.data('id');

            var quantity = addToCart(productId, 1, true);
            $(parent).find('.product-quantity input').val(quantity);

            $(parent).find('.product-add-to-cart').css('display', 'none');
            $(parent).find('.product-quantity').css('display', 'block');

            changeCartCounter();
        });
        $('.product-quantity .take').click(function() {
            var parent = $(this).closest('.product-actions');
            var productId = parent.data('id');

            var quantity = removeFromCart(productId, 1, true);
            $(parent).find('.product-quantity input').val(quantity);

            if (quantity < 1) {
                if ($(this).closest('.cart').length) {
                    $(this).closest('.product-item').remove();

                    if ($('.product-item').length == 0) {
                        $('.content-empty').css('display', 'flex');
                        $('.cart-total button').attr('disabled', true);
                    }
                } else {
                    $(parent).find('.product-add-to-cart').css('display', 'flex');
                    $(parent).find('.product-quantity').css('display', 'none');
                }
            }

            changeCartCounter();
        });

        $('header h1.menu, .open-menu').click(function() {
            if (!$('.categories').is('.opened')) {
                $('body, .categories').addClass('opened');
                $('.menu-helper').html('Закрыть');
                $('.menu-helper').addClass('active');
                $('header .category').removeClass('active');
            }
        });
        $(document).on('click', '.menu-helper.menu', function() {
            $('body, .categories').removeClass('opened');
            $('.menu-helper').removeClass('active');
            $('header .category').addClass('active');
        });

        $(document).on('click', '.menu-helper.cart', function() {
            $('.product-item').remove();
            clearCart();
            $('.cart-price span').html('0 руб');
            $('.cart-total button').attr('disabled', true);
            $('.content-empty').css('display', 'flex');
        });

        $('.cart-total button').click(function() {
            var created = createOrder();

            if (created) {
                clearCart();
                window.location.replace("/qr-orders/");
            }
        });

        $('.order-delete').click(function() {
            var order = $(this).closest('.product-item');
            var orderId = order.data('id');
            
            removeOrder(orderId);
            order.remove();

            if ($('.product-item').length === 0) {
                $('.content-empty').css('display', 'flex');
            }

            $('.product-item').each(function(index) {
                $(this).find('.order-name div span').html(index + 1);
            });

            changeOrdersCounter();
        });

        $(document).on('click', '.menu-helper.orders', function() {
            $('.product-item').remove();
            clearOrders();
            $('.content-empty').css('display', 'flex');
        });

        var diveIn = [];

        $(document).on('click', '.categories .cat-list li', function(e) {
            var id = $(this).data('id');

            if ($('.categories .cat-list li[data-parent='+ id +']').length) {
                e.preventDefault();
                diveIn.push($('.categories .cat-list li:visible'));
                
                $('.categories .cat-list li').css('display', 'none');
                $('.categories .cat-list li[data-parent='+ id +']').css('display', 'block');

                $('.cat-nav + span').html($(this).find('span').html());
                $('.cat-nav').css('display', 'inline-block');
            }
        });

        $('.cat-nav').click(function(e) {
            e.preventDefault();

            if (diveIn) {
                var items = diveIn.pop();

                $('.categories .cat-list li').css('display', 'none');
                items.css('display', 'block');

                // Рандомный id
                var randParentId = items.attr('data-parent');

                if (items.length != $('.categories .cat-list li[data-parent="'+ randParentId +'"]:visible').length || randParentId == 0) {
                    $('.cat-nav + span').html('Меню');
                    $('.cat-nav').css('display', 'none');
                } else {
                    var name = $('.categories .cat-list li[data-id="'+ randParentId +'"]').find('span').html();

                    $('.cat-nav + span').html(name);
                    $('.cat-nav').css('display', 'inline-block');
                }

                if (diveIn.length == 0) {
                    $('.cat-nav + span').html('Меню');
                    $('.cat-nav').css('display', 'none');
                } else {
                    $('.cat-nav + span').html(parent.find('span').html());
                    $('.cat-nav').css('display', 'inline-block');
                }
            }
        });

        function matchName(name, search) {
            name = name.toLowerCase();
            search = search.toLowerCase();

            return name.indexOf(search) !== -1;
        }

        var queriesI = 0;

        $('.search-field input').on('input', function() {
            var search = $(this).val().trim();

            $('.categories .nothing-found').css('display', 'none');

            if (search) {
                var catIds = [];

                $('.categories .cat-list li').each(function() {
                    var name = $(this).find('a > span').html().trim();

                    if (matchName(name, search)) {
                        $(this).css('display', 'block');
                    } else {
                        $(this).css('display', 'none');
                    }
                });

                $('.categories .cat-list').css('display', 'flex');

                if ($('.categories .cat-list li:visible').length) {
                    $('.categories h3.cat-title').html('Категории ('+ $('.categories .cat-list li:visible').length +'):');
                    $('.categories h3.cat-title').css('display', 'inline-block');
                    $('.categories .cat-list').css('padding', '10px 15px 15px');
                } else {
                    $('.categories h3.cat-title').css('display', 'none');
                    $('.categories .cat-list').css('display', 'none');
                }

                setTimeout(function() {
                    queriesI--;
                    searchProducts(search);
                }, 300);
                queriesI++;
            } else {
                $('.categories .cat-list').css({display: 'flex', padding: '10px 15px 18%'});
                $('.categories .cat-list li').css('display', 'none');
                $('.categories .cat-list li[data-parent="0"]').css('display', 'block');
                $('.categories h3').css('display', 'none');
                $('.categories .prod-list').css('display', 'none');

                setTimeout(function() {
                    queriesI--;
                    searchProducts(search);
                }, 300);
                queriesI++;
            }
        });

        function searchProducts(search) {console.log(search + queriesI)
            $('.categories h3.prod-title').html('Блюда: <div id="circularG"><div id="circularG_1" class="circularG"></div><div id="circularG_2" class="circularG"></div><div id="circularG_3" class="circularG"></div><div id="circularG_4" class="circularG"></div><div id="circularG_5" class="circularG"></div><div id="circularG_6" class="circularG"></div><div id="circularG_7" class="circularG"></div><div id="circularG_8" class="circularG"></div></div>');
            
            if (search && queriesI == 0) {
                $('#circularG').css('display', 'block');
                $('.categories h3.prod-title').css('display', 'inline-block');
                $('.categories .prod-list').css('display', 'flex');

                $('.search-field input').attr('disabled', true);

                $.ajax({
                    url: '/wp-admin/admin-ajax.php',
                    method: 'post',
                    data: {action: 'search_qr_menu', search: search},
                    success: function(resp) {
                        $('.prod-list').html(resp.data);

                        $('#circularG').css('display', 'none');
                        $('.search-field input').attr('disabled', false);
                        $('.prod-title').html('Блюда ('+ $('.prod-list li').length +'):');

                        if (!resp.data) {
                            $('.prod-list').css('display', 'none');
                            $('.prod-title').css('display', 'none');
                        }

                        if (!resp.data && $('.categories .cat-list li:visible').length == 0) {
                            $('.categories .nothing-found').css('display', 'block');
                        }

                        jQuery('.search-field input').focus();
                    }
                });
            }
        }
    });
})(jQuery);