<?php
add_action('init', function() {
    register_taxonomy('product_class', ['product'], [
        'labels'                => [
            'name'              => 'Классификация товара',
            'singular_name'     => 'Классификация',
            'search_items'      => 'Классификации',
            'all_items'         => 'Все классификации',
            'view_item '        => 'Посмотреть классификацию',
            'parent_item'       => 'Родительская категория',
            'parent_item_colon' => 'Родительская категория:',
            'edit_item'         => 'Редактировать классификацию',
            'update_item'       => 'Обновить',
            'add_new_item'      => 'Добавить новую',
            'new_item_name'     => 'Новое имя',
            'menu_name'         => 'Классификация товара',
            'back_to_items'     => '← Назад',
        ],
        'public' => false,
        'show_ui' => true,
        'meta_box_cb' => 'post_categories_meta_box'
    ]);
});

if (!is_qr_menu() && (!is_admin() || @$_POST['action'] == 'load_more' || @$_POST['action'] == 'get_product_by_tag')) {
    add_action('pre_get_posts', 'filter_qr_products');
}

function filter_qr_products($query) {
    if ($query->is_main_query() || $query->get('post_type') == 'product') {
        $tax_query = $query->get('tax_query') ?: [];
        $tax_query[] = [
            'taxonomy' => 'product_class',
            'field'    => 'slug',
            'terms'    => ['delivery_hide'],
            'operator' => 'NOT IN'
        ];

        $query->set('tax_query', $tax_query);
    }
}

if (strpos($GLOBALS['current_uri'], 'qr-menu') === false && !is_admin()) {
    add_filter('terms_clauses', function($clauses) {
        if (strpos($clauses['where'], 'product_cat')) {
            $clauses['where'] .= " AND (SELECT COUNT(tm.meta_value) FROM wp_termmeta tm WHERE tm.term_id = t.term_id AND tm.meta_key = 'qr_category' AND tm.meta_value = 1) < 1";
        }
        return $clauses;
    }, 10, 1);
}

add_action('init', 'qr_menu_pages');

function qr_menu_pages() {
    global $wpdb;
    $uri = $GLOBALS['current_uri'];

    if (strpos($uri, 'qr-menu') !== false && strpos($uri, 'qr-menu/product-') === false) {
        preg_match("|qr-menu/(.+)|", $uri, $cat_slug);
        $GLOBALS['qr_category'] = null;

        if ($slug = trim(@$cat_slug[1])) {
            $GLOBALS['qr_category'] = get_term_by('slug', $slug, 'product_cat');
        }

        include THEME_PATH . '/page-qr-menu.php';
        exit;
    } else if ($uri === 'qr-special') {
        include THEME_PATH . '/qr/page-special.php';
        exit;
    } else if ($uri === 'qr-cart') {
        include THEME_PATH . '/qr/page-cart.php';
        exit;
    } else if ($uri === 'qr-orders') {
        include THEME_PATH . '/qr/page-orders.php';
        exit;
    } else if (strpos($uri, 'qr-menu/product-') !== false) {
        preg_match("|qr-menu/product-(.+)|", $uri, $prod_slug);

        if ($slug = trim(@$prod_slug[1])) {
            $query = $wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE post_type = 'product' AND post_status = 'publish' AND post_name = %s", $slug);

            if ($product_id = $wpdb->get_var($query)) {
                $product = wc_get_product($product_id);
                include THEME_PATH . '/qr/single-product.php';
                exit;
            }
        }
    } else if (strpos($uri, 'qr-special/') !== false) {
        preg_match("|qr-special/(.+)|", $uri, $prom_slug);

        if ($slug = trim(@$prom_slug[1])) {
            $query = $wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE post_type = 'promotion' AND post_status = 'publish' AND post_name = %s", $slug);

            if ($promotion_id = $wpdb->get_var($query)) {
                $post = get_post($promotion_id);
                include THEME_PATH . '/qr/single-promotion.php';
                exit;
            }
        }
    }
}

function is_qr_menu() {
    $uri = $GLOBALS['current_uri'];
    
    return strpos($uri, 'qr-menu') !== false || $uri === 'qr-cart' || $uri === 'qr-orders';
}

function qr_icon($name, $echo = true) {
    $icon = file_get_contents(THEME_PATH . "/qr/icons/$name.svg");

    if ($echo) {
        echo $icon;
    } else {
        return $icon;
    }
}

function qr_get_cart_items() {
    if (!isset($GLOBALS['qr_cart'])) {
        if (isset($_COOKIE['cart_products'])) {
            $GLOBALS['qr_cart'] = json_decode(stripslashes($_COOKIE['cart_products']), true);
        } else {
            $GLOBALS['qr_cart'] = [];
        }
    }

    return $GLOBALS['qr_cart'];
}

function qr_get_cart_items_quantity() {
    $quantity = 0;

    foreach (qr_get_cart_items() as $item_quantity) {
        $quantity += $item_quantity;
    }

    return $quantity;
}

function qr_get_cart_item_quantity($product_id) {
    return qr_get_cart_items()[$product_id] ?? 0;
}

function qr_get_orders() {
    if (!isset($GLOBALS['qr_orders'])) {
        if (isset($_COOKIE['qr_orders'])) {
            $GLOBALS['qr_orders'] = json_decode(stripslashes($_COOKIE['qr_orders']), true);
        } else {
            $GLOBALS['qr_orders'] = [];
        }
    }

    // foreach ($GLOBALS['qr_orders'] as $order_id => $order) {
    //     if (strtotime($order['date']) < time() - (3 * 24 * 60 * 60)) {
    //         unset($GLOBALS['qr_orders'][$order_id]);
    //     }
    // }

    return $GLOBALS['qr_orders'];
}

function qr_get_orders_quantity() {
    return count(qr_get_orders());
}

function remove_qr_order($order_id) {
    $orders = qr_get_orders();

    if (isset($orders[$order_id])) {
        unset($orders[$order_id]);
        setcookie('qr_orders', json_encode($orders), time() + 259200, '/');
    }
}

add_action('wp_ajax_search_qr_menu', 'search_qr_menu_products');
add_action('wp_ajax_nopriv_search_qr_menu', 'search_qr_menu_products');

function search_qr_menu_products() {
    $args = [
        'post_status' => 'publish',
        'post_type' => 'product',
        'posts_per_page' => -1,
        'tax_query' => [
            [
                'taxonomy' => 'product_class',
                'field' => 'slug',
                'terms' => ['qr-menu']
            ]
        ],
        'search_prod_title' => $_POST['search']
    ];

    add_filter('posts_where', 'title_filter', 10, 2);
    $products = new WP_Query($args);
    remove_filter('posts_where', 'title_filter', 10, 2);
    
    $html = '';

    if ($products->have_posts()) {
        while ($products->have_posts()) {
            $products->the_post();
            global $post;

            $html .= 
            '<li>
                <a href="/qr-menu/product-'. $post->post_name .'">
                    <div class="thumb_wrapper">
                        <img src="'. (get_the_post_thumbnail_url(get_the_ID(), 'thumbnail') ?: wc_placeholder_img_src('thumbnail')) .'" alt="">
                    </div>
                    <span>'. get_the_title() .'</span>
                </a>
            </li>';
        }
    }

    wp_send_json_success($html);
    exit;
}

function title_filter( $where, &$wp_query ){
    global $wpdb;
    if ( $search_term = $wp_query->get( 'search_prod_title' ) ) {
        $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $wpdb->esc_like( $search_term ) ) . '%\'';
    }
    return $where;
}