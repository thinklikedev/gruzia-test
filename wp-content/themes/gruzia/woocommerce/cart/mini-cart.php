<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;

global $wpdb;

$packages_ids = $wpdb->get_col("SELECT ID FROM {$wpdb->posts} p INNER JOIN {$wpdb->term_relationships} tr ON (p.ID = tr.object_id) WHERE term_taxonomy_id = 91 AND p.post_status = 'publish'");
$packages = [];


foreach ($packages_ids as $product_id) {
	$cart_id = WC()->cart->generate_cart_id($product_id);

	if (isset(WC()->cart->cart_contents[$cart_id])) {
		$packages[$cart_id] = WC()->cart->cart_contents[$cart_id];
		$packages[$cart_id]['package_required'] = get_field('package_required', $product_id);
		unset(WC()->cart->cart_contents[$cart_id]);
	}
}

foreach ($packages as $key => $value) {
	WC()->cart->cart_contents[$key] = $value;
}

$packages = null;

?>

<?php if (!wp_doing_ajax()) : ?>
<div class="cart-block">
	<div class="widget_shopping_cart_content">
<?php endif; ?>
    <div class="cart-header">
        <h5 class="title">Корзина</h5>
        <span class="close-btn">✕</span>
    </div>
    <div class="cart-items">
    	<?php
        $cart_empty = true;

		foreach (WC()->cart->cart_contents as $cart_item_key => $cart_item) {
			$_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
			$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

			if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                $cart_empty = false;
				$permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
				$image = wp_get_attachment_image_url($_product->get_image_id(), 'medium') ?: wc_placeholder_img_src();
				?>
                <div class="cart-item" data-product_id="<?= $_product->get_id(); ?>">
                	<?php if (!@$cart_item['package_required']) : ?>
	                    <div class="remove-btn" title="Удалить" data-cart_item_key="<?= $cart_item_key; ?>">
	                        <i class="fa fa-trash-o"></i>
	                    </div>
	                <?php endif; ?>

                    <?= @$cart_item['package_required'] ? "<span>" : "<a href=\"$permalink\">"; ?>

                    <img src="<?= $image; ?>">
                    <div class="item-title"><?= $_product->get_name(); ?></div>

                    <?= @$cart_item['package_required'] ? "</span>" : "</a>"; ?>
                    <div class="item-actions">
                        <div class="item-price"><?= $_product->get_price(); ?>р</div>
                        <div class="item-quantity">
                        	<div class="quantity-minus <?= @$cart_item['package_required'] ? 'disabled' : ''; ?>" data-cart_item_key="<?= $cart_item_key; ?>"></div>
                        	<div class="quantity-number"><span><?= $cart_item['quantity']; ?></span></div>
                    		<div class="quantity-plus add_btn add_to_cart_button ajax_add_to_cart <?= @$cart_item['package_required'] ? 'disabled' : ''; ?>" data-product_id="<?= $_product->get_id(); ?>"></div>
                    	</div>
                    </div>
                </div>
            <?php
			}
        }

        if ($cart_empty) { ?>
            <div class="cart-item">
                <div class="item-title empty">Ваша корзина пуста</div>
            </div>
        <?php
        }
		?>
    </div>
    <?php if (!$cart_empty) : ?>
        <div class="cart-total">
            <span class="total-label">Сумма заказа:</span>
            <span class="total-value"><?= WC()->cart->get_cart_contents_total(); ?>р</span>
        </div>
    <?php endif; ?>
<?php if (!wp_doing_ajax()) : ?>
	</div>
    <?php if (!$cart_empty) : ?>
        <a href="/checkout/" id="to-checkout">Оформить заказ</a>
    <?php else : ?>
        <a href="/checkout/" id="to-checkout" style="display: none;">Оформить заказ</a>
    <?php endif; ?>
</div>
<?php endif; ?>