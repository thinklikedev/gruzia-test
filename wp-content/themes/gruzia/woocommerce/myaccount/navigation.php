<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_account_navigation' );

add_filter('woocommerce_account_menu_item_classes', function($classes, $endpoint) {
    $new_classes = ["nav-$endpoint"];
    if (in_array('is-active', $classes)) $new_classes[] = 'is-active';
    
    return $new_classes;
}, 10, 2);
?>

<div id="sidebar">
    <ul id="catalog-cats">
        <?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) :
            $classes = wc_get_account_menu_item_classes( $endpoint );
            $icon = file_get_contents(image_dir("$endpoint.svg"));
            $label = $label == 'Адреса' ? 'Адрес' : $label;
            ?>
            <li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
                <?php if (strpos($classes, 'is-active') === false) : ?>
                    <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?= $icon; ?><?php echo esc_html( $label ); ?></a>
                <?php else : ?>
                    <span><?= $icon; ?><?php echo esc_html( $label ); ?></span>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>
