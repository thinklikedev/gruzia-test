<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
const THEME_PATH = __DIR__;
$GLOBALS['current_uri'] = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), '/');

require_once 'includes/acf/index.php';
require_once 'includes/menus/index.php';
require_once 'includes/post-type/index.php';

function add_theme_supports() {
    add_theme_support('woocommerce');
    add_theme_support('menus');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
}


add_action('after_setup_theme', 'add_theme_supports');

add_action('wp_enqueue_scripts', 'gruzia_scripts');

function gruzia_scripts() {
    $ver = '1.0';
    
    wp_enqueue_script('scripts', get_template_directory_uri() . '/js/scripts.js', [], $ver, 1);
    wp_enqueue_script('carousel', get_template_directory_uri() . '/js/owl.carousel.min.js');

    wp_enqueue_style('style', get_template_directory_uri() . '/css/style.css', [], $ver);
    wp_enqueue_style('responsive', get_template_directory_uri() . '/css/responsive.css', [], $ver);
    wp_enqueue_style('carousel', get_template_directory_uri() . '/css/owl.carousel.css');
    wp_enqueue_style('font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css');
}




remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
add_action('woocommerce_before_shop_loop_item_title', 'custom_product_thumbnail', 10);

remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
add_action('woocommerce_single_product_summary', 'unique_block_price_add_to_cart', 10);

function custom_product_thumbnail() {
    global $post;
    ?>
    <div class="image">
        <a href="<?php the_permalink(); ?>">
            <img src="<?= get_the_post_thumbnail_url(get_the_ID(), 'medium') ?: wc_placeholder_img_src('medium'); ?>" alt="" title="<?php the_title(); ?>" loading="lazy">
        </a>
    </div>
<?php
}

function unique_block_price_add_to_cart() {
    echo '<div class="product-actions">';
    woocommerce_template_single_price();
    woocommerce_template_single_add_to_cart();
    echo '</div>';
}

function woocommerce_custom_shop_loop_item_title() { ?>
    <h3 class="section-title-border text-uppercase shotlink">
        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </h3>
<?php
}

function woocommerce_custom_loop_item_descr() {
    echo '<div class="item-descr">';
    echo get_the_content();
    echo '</div>';

    if ($excerpt = get_the_excerpt()) {
        echo '<div class="item-weight">' . $excerpt . '</div>';
    }
}

add_filter( 'intermediate_image_sizes', function($sizes) {
    return array_filter($sizes, function($val) {
        return 'medium_large' !== $val;
    });
});

add_action('after_setup_theme', function() {
    remove_image_size('2048x2048');
    remove_image_size('1536x1536');

    add_image_size('category', 254, 167, true);
    add_image_size('medium', 330, 263, true);
    add_image_size('large', 1024, 816, true);
}, 11);


add_action('wp_ajax_remove_cart_item', 'custom_remove_cart_item');
add_action('wp_ajax_nopriv_remove_cart_item', 'custom_remove_cart_item');

function custom_remove_cart_item() {
    $cart_item_key = $_POST['key'];
    $quantity = $_POST['quantity'];
    WC()->cart->set_quantity($cart_item_key, $quantity, true);
    do_action('woocommerce_cart_item_removed', $cart_item_key, null);

    ob_start();

    woocommerce_mini_cart();

    $mini_cart = ob_get_clean();

    $data = array(
        'fragments' => apply_filters(
            'woocommerce_add_to_cart_fragments',
            array(
                'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>'
            )
        ),
        'cart_hash' => WC()->cart->get_cart_hash()
    );

    wp_send_json($data);
}

add_filter('woocommerce_add_to_cart_fragments', function($fragments) {
    $fragments['.cart-button span'] = '<span>' . WC()->cart->get_cart_contents_count() . '</span>';

    if (isset($_POST['product_id'])) {
        foreach (WC()->cart->get_cart() as $key => $value) {
            $product_id = $value['data']->get_id();

            if ($product_id == $_POST['product_id']) {
                $classes = [
                    ".post-$product_id:not(#single-product) .quantity-number span",
                    "#single-product.post-$product_id .single-content .quantity-number span",
                    ".cart-item[data-product_id=$product_id] .quantity-number span"
                ];
                $fragments[implode(', ', $classes)] = '<span>'. $value['quantity'] .'</span>';
            }
        }
    }

    return $fragments;
});

function custom_breadcrumbs() {
    if (isset($GLOBALS['custom_breadcrumbs'])) {
        echo $GLOBALS['custom_breadcrumbs'];
        return;
    }
    ob_start();
    ?>
    <div class="site-map <?= is_product() ? 'single' : ''; ?>">
        <div class="container breadcrumbs">
            <div class="row">
                <span id="dle-speedbar">
                    <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
                        <a href="/menu/" itemprop="url">
                            <span itemprop="title">Меню ресторана</span>
                        </a>
                    </span>


                    <?php if (is_product()) :
                        global $product;

                        $cats = get_the_terms($product->get_id(), 'product_cat');

                        foreach ($cats as $cat) : ?>
                            <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
                                <a href="<?= get_term_link($cat->term_id); ?>" itemprop="url">
                                    <span itemprop="title"><?= $cat->name; ?></span>
                                </a>
                            </span>
                        <?php
                        break;
                        endforeach;
                    endif; ?>

                    <?php the_title(); ?>
                </span>
            </div>
        </div>
    </div>
    <?
    $GLOBALS['custom_breadcrumbs'] = ob_get_clean();
    echo $GLOBALS['custom_breadcrumbs'];
}

add_action('woocommerce_single_product_summary', 'woocommerce_template_single_weight', 7);

function woocommerce_template_single_weight() {
    global $post;

    if ($post->post_excerpt) {
        echo '<div class="product_weight">'. $post->post_excerpt .'</div>';
    }
}

add_action('woocommerce_single_product_summary', 'woocommerce_template_single_description', 8);

function woocommerce_template_single_description() {
    global $product;

    echo '<div class="product_descr">';

    the_content();

    if ($GLOBALS['has_extra_prods']) {
        echo '<a href="#" class="show-extra-products">Список добавок</a>';
    }

    echo '</div>';
}

add_filter('wc_add_to_cart_message_html', 'change_added_to_cart_message', 10, 3);

function change_added_to_cart_message($message, $products, $show_qty) {
    return "Вы отложили “". get_the_title(array_key_first($products)) ."” в свою корзину.";
}

add_action('wp_ajax_clear_cart', 'custom_clear_cart');
add_action('wp_ajax_nopriv_clear_cart', 'custom_clear_cart');

function custom_clear_cart() {
    WC()->cart->empty_cart();

    ob_start();

    woocommerce_mini_cart();

    $mini_cart = ob_get_clean();

    $data = array(
        'fragments' => apply_filters(
            'woocommerce_add_to_cart_fragments',
            array(
                'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
            )
        ),
        'cart_hash' => WC()->cart->get_cart_hash()
    );

    wp_send_json($data);
}

/* WooCommerce: The Code Below Removes Checkout Fields */
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
function custom_override_checkout_fields( $fields ) {
    unset($fields['order']['order_comments']);

    return $fields;
}

add_filter('woocommerce_billing_fields', function($fields) {
    unset($fields['billing_last_name']);
    unset($fields['billing_company']);
    unset($fields['billing_address_2']);
    unset($fields['billing_postcode']);
    unset($fields['billing_state']);
    unset($fields['billing_email']);
    
    foreach ($fields as $key => $field) {
        $key == 'billing_phone' ? $fields[$key]['priority'] = 11 : '';
        $key == 'billing_city' ? $fields[$key]['priority'] = 12 : '';
    }
    
    return $fields;
}, 10, 1);

add_filter('woocommerce_save_account_details_required_fields', function($fields) {
    unset($fields['account_last_name']);

    return $fields;
});

add_action('template_redirect', 'redirect_to_main_page_if_cart_empty');

function redirect_to_main_page_if_cart_empty() {
    global $wp;

    if (is_page(wc_get_page_id('checkout')) && wc_get_page_id('checkout') !== wc_get_page_id('cart') && WC()->cart->is_empty() && empty($wp->query_vars['order-pay']) && !isset($wp->query_vars['order-received']) && !is_customize_preview() && apply_filters('woocommerce_checkout_redirect_empty_cart', true)) {
        wc_add_notice( __( 'Checkout is not available whilst your cart is empty.', 'woocommerce' ), 'notice' );
        wp_safe_redirect('/');
        exit;
    }
}

add_action('template_redirect', function() {
    if (!is_account_page()) { 
        wc_clear_notices();
    }
});

add_action('wp_ajax_new_callback', 'custom_create_callback');
add_action('wp_ajax_nopriv_new_callback', 'custom_create_callback');

function custom_create_callback() {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    $errors = [];

    if (!$name) {
       $errors[] = 'name'; 
    }
    if (!$phone) {
       $errors[] = 'phone'; 
    }
    if ($email && !is_email($email)) {
       $errors[] = 'email'; 
    }
    if (!$message) {
       $errors[] = 'message'; 
    }

    if (empty($errors)) {
        $title = 'Имя: "'.$name.'", Телефон: "'.$phone.'"';

        if ($email) {
            $title .= ', E-mail: "'.$email.'"';
        }

        $callback_id = wp_insert_post([
            'post_type'   => 'callback',
            'post_title'  => $title,
            'post_status' => 'publish'
        ]);

        update_field('name', $name, $callback_id);
        update_field('phone', $phone, $callback_id);
        update_field('email', $email, $callback_id);
        update_field('message', $message, $callback_id);
    }

    wp_send_json_success($errors);
}

add_action('woocommerce_after_checkout_validation', function($data, $errors) {
    foreach ($errors->errors as $key => $error) {
        $errors->errors[$key] = str_replace(' для выставления счета', '', $error);
    }
}, 10, 2);

add_filter('woocommerce_upsell_display_args', function($args) {
    $args['posts_per_page'] = 5;

    return $args;
});

add_filter('wp_sitemaps_add_provider', function($provider) {
    if (get_class($provider) == 'WP_Sitemaps_Users') {
        return null;
    }
    return $provider;
}, 10, 1);

add_filter('woocommerce_account_menu_items', function($items) {
    unset($items['dashboard']);

    return $items;
});

add_action('init', function() {
    preg_match('|my-account/(\d+)|', $_SERVER['REQUEST_URI'], $page);

    if (trim($_SERVER['REQUEST_URI'], '/') == 'my-account') {
        $_SERVER['REQUEST_URI'] = '/my-account/orders/';
    } elseif (@$page[1]) {
        $_SERVER['REQUEST_URI'] = "/my-account/orders/$page[1]/";
    }
}, 1);

add_filter('woocommerce_get_order_item_totals', function($total_rows) {
    unset($total_rows['cart_subtotal']);

    return $total_rows;
}, 10, 1);

add_action( 'template_redirect', 'hide_job_post' );

function hide_job_post() {
  if (is_singular('job')) {
    wp_redirect(home_url(), 301);
    exit;
  }
}

add_filter('woocommerce_min_password_strength', 'change_woocommerce_min_password_strength');

function change_woocommerce_min_password_strength($strength) {
    return 1;
}

add_filter('document_title_parts', 'change_page_title');

function change_page_title($title_parts) {
    $title_parts['tagline'] = '';

    return $title_parts;
}

add_action('woocommerce_order_status_changed', 'send_new_order_to_telegram', 10, 1);

function send_new_order_to_telegram($order_id) {
    $telegram_sent = get_post_meta($order_id, 'telegram_sent', true);

    if (!$telegram_sent) {
        $order = wc_get_order($order_id);

        if ($order->get_status() != 'processing') {
            return;
        }

        $shipping = $order->get_shipping_method();
        $payment = $order->get_payment_method() == 'agroprom' ? 'Агропромбанк (Онлайн)' : 'Наличными';
        $address = $order->get_address();

        if ($address['city'] == 1) {
            $address['city'] = 'Тирасполь';
        } elseif ($address['city'] == 2) {
            $address['city'] = 'Бендеры';
        } elseif ($address['city'] == 3) {
            $address['city'] = 'Парканы';
        } else {
            $address['city'] = false;
        }

        $detail_address = $address['city'] ? "$address[city], $address[address_1]" : $address['address_1'];

        $text = "<b>Заведение:</b> Georgia (Сайт)
<b>Оплата:</b> ". $payment ."
<b>Тип доставки:</b> ". $shipping."
<b>Адрес:</b> $detail_address
<b>Телефон:</b> $address[phone]
<b>Продукты:</b>
";

        foreach ($order->get_items() as $item_id => $item) {
            $text .= ' * ' . $item->get_name() . ' шт:' . $item->get_quantity() . "
";
        }

        $text .= "<b>Сумма:</b> " . $order->get_total() . ',00';

        update_post_meta($order_id, 'telegram_sent', 1);
    }
}

add_action('wp_ajax_load_more', 'load_more_products');
add_action('wp_ajax_nopriv_load_more', 'load_more_products');

function load_more_products() {
    $per_page = get_option('woocommerce_catalog_columns') * get_option('woocommerce_catalog_rows');

    $args = [
        'post_status' => 'publish',
        'paged' => $_POST['page'],
        'paginate' => true,
        'order' => 'ASC',
        'meta_key' => '_price',
        'orderby' => 'meta_value_num',
        'posts_per_page' => $per_page
    ];

    if ($_POST['category']) {
        $args['category'] = [$_POST['category']];
    }

    $result = wc_get_products($args);

    ob_start();

    foreach ($result->products as $product) {
        @setup_postdata($GLOBALS['post'] =& get_post($product->get_id()));
        $GLOBALS['product'] = $product;
        do_action( 'woocommerce_shop_loop' );
        wc_get_template_part( 'content', 'product' );
    }
    wp_reset_postdata();

    $content = ob_get_clean();

    $args = [
        'total'   => $result->max_num_pages,
        'current' => $_POST['page'],
        'base'    => $_POST['link'] . '%#%/'
    ];

    ob_start();
    wc_get_template( 'loop/pagination.php', $args );
    $pagination = ob_get_clean();

    wp_send_json_success([
        'content' => $content,
        'pagination' => $pagination ?: '<div class="load-more"></div>',
    ]);

    exit;
}

add_filter('woocommerce_currency_symbol', 'change_existing_currency_symbol', 10, 2);

function change_existing_currency_symbol($currency_symbol, $currency) {
    switch($currency) {
        case 'PRB': $currency_symbol = 'р'; break;
    }
    return $currency_symbol;
}

add_filter('woocommerce_shipping_rate_cost', function($rate, $shipping) {
    if ($shipping->get_method_id() == 'flat_rate') {
        $free_from = get_option('woocommerce_flat_rate_'. $shipping->get_instance_id() .'_settings')['free_from'];

        if ((int)$free_from && WC()->cart->get_cart_contents_total() >= $free_from) {
            $rate = 0;
        }
    }

    return $rate;
}, 10, 2);

add_filter('woocommerce_shipping_instance_form_fields_flat_rate', function($fields) {
    $fields['free_from'] = [
        'title' => 'Бесплатно от',
        'type' => 'number',
        'default' => 0
    ];

    return $fields;
});

add_filter('woocommerce_customer_get_billing_country', function($country) {
    return 'MD';
}, 10, 1);

if (!is_admin() || wp_doing_ajax()) {
    add_action('pre_get_posts', 'filter_package_products');
}

function filter_package_products($query) {
    if ($query->is_main_query() || $query->get('post_type') == 'product') {
        $tax_query = $query->get('tax_query') ?: [];
        $tax_query[] = [
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => ['packages'],
            'operator' => 'NOT IN'
        ];

        if (!empty($_REQUEST['tag_id'])) {
            $tax_query[] = [
                'taxonomy' => 'product_tag',
                'field'    => 'term_id',
                'terms'     =>  $_REQUEST['tag_id'],
                'operator'  => 'IN'
            ];
        }

        $query->set('tax_query', $tax_query);
    }
}

add_action('woocommerce_ajax_added_to_cart', 'set_package_for_product', 10, 1);
add_action('woocommerce_cart_item_removed', 'set_package_for_product', 10, 1);

function set_package_for_product($item) {
    global $wpdb;

    // Получаем product_id упаковок
    $packages_ids = $wpdb->get_col("SELECT ID FROM {$wpdb->posts} p INNER JOIN {$wpdb->term_relationships} tr ON (p.ID = tr.object_id) WHERE term_taxonomy_id = 91 AND p.post_status = 'publish'");
    $apply_packages = [];
    $prods_applyed = [];

    foreach ($packages_ids as $id) {
        $package_cats = get_field('package_cat', $id) ?? [];
        $exclude = get_field('package_exclude', $id) ?? [];
        
        $include = get_field('package_include', $id) ?? [];
        $total_quantity = 0;
        $cart_id = WC()->cart->generate_cart_id($id);

        foreach (WC()->cart->get_cart() as $cart_item_key => $values) {
            $product_id = $values['data']->get_id();

            if (in_array($product_id, $prods_applyed)) {
                continue;
            }

            if ($include && !in_array($product_id, $include)) {
                continue;
            }
            /*
            if (!$include && in_array($product_id, $exclude)) {
                continue;
            }
            */

            $term_ids = $wpdb->get_col("SELECT term_taxonomy_id FROM {$wpdb->term_relationships} WHERE object_id = $product_id");

            $add_packages = array_intersect($package_cats, $term_ids) ? $values['quantity'] : 0;
            $total_quantity += $add_packages;

            if ($add_packages) {
                $prods_applyed[] = $product_id;
            }
        }

        if ($cart_id !== $item) {
            $in_cart = WC()->cart->find_product_in_cart($cart_id);

            if ($in_cart) {
                WC()->cart->set_quantity($cart_id, $total_quantity, true);
            } else {
                WC()->cart->add_to_cart($id, $total_quantity);
            }
        }
    }
}

add_action('init', function() {
    $GLOBALS['curr_cat'] = @array_shift(get_terms(['taxonomy' => 'product_cat', 'number' => 1, 'parent' => 0, 'exclude' => [100, 15]]));
});

add_filter('pre_get_document_title', function($title) {
    if (is_archive() && !is_tax()) {
        $title = str_replace(['Купить ', 'Архив'], '', $title);
    }

    return $title;
}, 20);

add_filter('woocommerce_update_order_review_fragments', function($fragments) {
    $text = "Подтвердить заказ (" . WC()->cart->get_total(false) . 'р)';

    $fragments['#payment button'] = apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $text ) . '" data-value="' . esc_attr( $text ) . '">' . esc_html( $text ) . '</button>' );

    return $fragments;
});

add_action('admin_menu', function() {
    remove_meta_box('tagsdiv-product_tag', 'product', 'side');
});

function output_tags($post) {
    $term_ids = array_map(function($term) {
        return $term->term_id;
    }, get_the_terms($post->ID, 'product_cat') ?: []);

    $metki = get_terms('product_tag', ['hide_empty' => 0]);   
 
    $id_metok_posta = array_map(function($term) {
        return $term->term_id;
    }, get_the_terms($post->ID, 'product_tag') ?: []);

    foreach ($metki as $key => $metka) {
        $for_cats_str = get_term_meta($metka->term_id)['for_cats'][0];
        $for_cats = unserialize($for_cats_str);
        
        if (!array_intersect($term_ids, $for_cats)) unset($metki[$key]);
    }
 
    // начинаем выводить HTML
    echo '<div id="taxonomy-product_tag" class="categorydiv">';
    echo '<input type="hidden" name="tax_input[product_tag][]" value="0" />';
    echo '<ul>';
    // запускаем цикл для каждой из меток
    foreach( $metki as $metka ){
        $checked = "";
        // если ID метки содержится в массиве присвоенных меток поста, то отмечаем чекбокс
        if ( in_array( $metka->term_id, $id_metok_posta ) ) {
            $checked = " checked='checked'";
        }
        // ID чекбокса (часть) и ID li-элемента
        $id = 'product_tag-' . $metka->term_id;
        echo "<li id='{$id}'>";
        echo "<label><input type='checkbox' name='tax_input[product_tag][]' id='in-$id'". $checked ." value='$metka->slug' /> $metka->name</label><br />";
        echo "</li>";
    }
    echo '</ul></div>'; // конец HTML
}

add_action('wp_ajax_get_product_by_tag', 'ajax_get_product_by_tag');
add_action('wp_ajax_nopriv_get_product_by_tag', 'ajax_get_product_by_tag');

function ajax_get_product_by_tag() {
    $per_page = get_option('woocommerce_catalog_columns') * get_option('woocommerce_catalog_rows');
    $args = [
        'post_status' => 'publish',
        'paginate' => true,
        'order' => 'ASC',
        'meta_key' => '_price',
        'orderby' => 'meta_value_num',
        'posts_per_page' => $per_page,
    ];

    if ($_POST['category']) {
        $args['category'] = [$_POST['category']];
    }

    $result = wc_get_products($args);

    ob_start();

    woocommerce_product_loop_start();

    foreach ($result->products as $product) {
        @setup_postdata($GLOBALS['post'] =& get_post($product->get_id()));
        $GLOBALS['product'] = $product;
        do_action( 'woocommerce_shop_loop' );
        wc_get_template_part( 'content', 'product' );
    }
    wp_reset_postdata();

    woocommerce_product_loop_end();

    $content = ob_get_clean();

    $args = [
        'total'   => $result->max_num_pages,
        'current' => 1,
        'base'    => $_POST['link'] . 'page/%#%/'
    ];

    ob_start();
    wc_get_template('loop/pagination.php', $args);
    $pagination = ob_get_clean();

    wp_send_json_success([
        '#products-archive .products-grid' => $content,
        '.load-more' => $pagination
    ]);

    exit;

}

add_action('admin_menu', function() {
    add_meta_box('tagsdiv-product_tag', 'Метки', 'output_tags', 'product', 'side');
});

add_action('admin_head', 'admin_custom_css');

function admin_custom_css() {
    echo '<style>#col-right #the-list td.description p{display: none;}</style>';
}

function image_dir($uri) {
    return get_template_directory() . '/images/' . ltrim($uri, '/');
}

function image_url($uri) {
    return get_template_directory_uri() . '/images/' . ltrim($uri, '/');
}

function is_mobile() {
    if (!isset($GLOBALS['is_mobile'])) {
        $useragent = $_SERVER['HTTP_USER_AGENT'];

        $GLOBALS['is_mobile'] = preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4));
    }

    return $GLOBALS['is_mobile'];
}

// QR menu
require_once THEME_PATH . '/qr/functions.php';

/**
 * Function for `woocommerce_shop_loop_item_title` action-hook.
 * 
 * @return void
 */
add_action( 'woocommerce_before_shop_loop_item_title', 'wp_ajax_add_title_to_product_woocommerce_shop_loop_item_title_action' ,99);
function wp_ajax_add_title_to_product_woocommerce_shop_loop_item_title_action(){
    global $product;

    $atts =  $product->get_attribute( 'pa_tip' );
    
    if(empty($atts)) return;
    
    $atts = explode(",", $atts );
    
    echo "<div class=\"filter-attr\">";
    foreach($atts as $attr) {
        echo "<span class=\"attr\">$attr</span>";
    }
    echo "</div>";
    
}


/**
 * Function for `wp_ajax_filter_by_attr` action-hook.
 * 
 * @return json
 */
add_action( 'wp_ajax_filter_by_attr',        'filter_by_attr_callback' ); // For logged in users
add_action( 'wp_ajax_nopriv_filter_by_attr', 'filter_by_attr_callback' ); // For anonymous users

function filter_by_attr_callback(){

    //remove_action( 'woocommerce_before_shop_loop_item_title', 'wp_ajax_add_title_to_product_woocommerce_shop_loop_item_title_action' ,99);

    $args = [
        'post_status' => 'publish',
        'order' => 'ASC',
        'paginate' => true,
        'page' => $_POST['page'] ?? '',
        'meta_key' => '_price',
        'orderby' => 'meta_value_num',
        'limit' => get_option('posts_per_page')
    ];

    if($_POST['pa_tip']) {
        $args['tax_query'] = array(
            array(
                'taxonomy'         => 'pa_tip',
                'field'            => 'slug',
                'terms'            =>  explode("," , $_POST['pa_tip']),
                'operator'         => 'IN',
            )
        );
        $args['limit'] = -1;

    }

    if ($_POST['category']) {
        $args['category'] = [$_POST['category']];
    }
    
    $result = wc_get_products($args);

    ob_start();

    foreach ($result->products as $product) {
        setup_postdata($GLOBALS['post'] = get_post($product->get_id()));
        $GLOBALS['product'] = $product;
        do_action( 'woocommerce_shop_loop' );
        wc_get_template_part( 'content', 'product' );
    }
    wp_reset_postdata();

    $content = ob_get_clean();
    
    $args = [
        'total'   => $result->max_num_pages,
        'current' => $_POST['page'],
        'base'    => $_POST['link'] . '%#%/'
    ];
    
    ob_start();
    wc_get_template( 'loop/pagination.php', $args );
    $pagination = ob_get_clean();

    wp_send_json_success([
        'content' => $content,
        'pagination' => $pagination,
        'isset_more' => $result->max_num_pages > $_POST['page']
    ]);

    die;
}
