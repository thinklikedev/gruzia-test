<?php
include THEME_PATH . '/qr/header.php';

$cart_items = array_filter(qr_get_cart_items(), 'is_numeric');

if ($cart_items) {
	$products = get_posts([
		'post_status' => 'publish',
		'post_type' => 'product',
		'include' => array_keys($cart_items),
		'tax_query' => [
			[
				'taxonomy' => 'product_class',
				'field' => 'slug',
				'terms' => ['qr-menu']
			]
		]
	]);
} else {
	$products = [];
}

$total = 0;

?>

<header>
	<h1>Корзина</h1>
	<div class="menu-helper cart active">Очистить</div>
</header>

<div class="page cart" style="padding-bottom: calc(15% + 67px);">
	<?php if ($products) : ?>
		<div class="products">
			<?php foreach ($products as $product) :
				setup_postdata($GLOBALS['post'] = $product);
				global $product;
				$in_cart = qr_get_cart_item_quantity($product->get_id());
				$total += $product->get_price() * $in_cart;
				?>
				<div class="product-item">
					<a href="/qr-menu/product-<?= $product->get_slug(); ?>" class="product-image">
						<img src="<?= get_the_post_thumbnail_url($product->get_id(), 'thumbnail') ?: wc_placeholder_img_src('thumbnail'); ?>" alt="">
					</a>
					<div class="product-data">
						<a href="/qr-menu/product-<?= $product->get_slug(); ?>" class="product-name"><?php the_title(); ?></a>
						<div class="product-actions" data-id="<?= $product->get_id(); ?>">
							<div class="product-price"><?= $product->get_price(); ?> руб</div>
							<div class="product-add-to-cart" style="<?= $in_cart ? 'display:none' : ''; ?>">В корзину<img src="<?= get_template_directory_uri(); ?>/qr/icons/cart.png"></div>
							<div class="product-quantity" style="<?= $in_cart ? '' : 'display:none'; ?>">
								<div class="product-quantity-btns">
									<?php qr_icon('take'); ?>
									<input type="text" value="<?= $in_cart; ?>" disabled>
									<?php qr_icon('add'); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php $display = $products ? 'display: none' : ''; ?>
	<div class="content-empty" style="<?= $display; ?>;top: calc(50vh - 128px)">
		<img src="<?= get_template_directory_uri(); ?>/qr/icons/cart-black.png" alt="">
		<div class="empty-message">Корзина пуста.</div>
	</div>
</div>

<footer>
	<div class="cart-total">
		<div class="cart-price">
			<label>Итого:</label>
			<span><?= $total; ?> руб</span>
		</div>
		<button <?= $products ? '' : 'disabled'; ?>>Заказать</button>
	</div>
<?php include THEME_PATH . '/qr/footer.php'; ?>