<?php
include THEME_PATH . '/qr/header.php';

global $post;

$referer = $_SERVER['HTTP_REFERER'];
$referer_uri = trim(parse_url($referer, PHP_URL_PATH), '/');
$cat_sku = str_replace('qr-menu/', '', $referer_uri);
$menu_name = $referer_uri == 'qr-menu' ? 'Меню' : (@get_term_by('sku', $cat_sku, 'product_cat')->name ?: 'Меню');
$menu_uri = strpos($referer, 'gruzia.md/qr-menu') ? $referer : '/qr-menu/';
$in_cart = qr_get_cart_item_quantity($product->get_id());
$content_parts = explode("\r\n", $product->get_description());
?>

<header>
	<h1><a href="<?= $menu_uri; ?>" class="back-menu"><?php qr_icon('arrow'); ?></a> <?= $menu_name; ?></h1>
</header>

<div class="page">
	<div class="single-product">
		<img class="single-image" src="<?= get_the_post_thumbnail_url($product->get_id(), 'large') ?: wc_placeholder_img_src('large'); ?>" alt="">

		<h1><?= $product->get_name(); ?></h1>

		<?php if ($weight = $product->get_short_description()) : ?>
			<div class="product_weight"><?= $weight; ?></div>
		<?php endif; ?>

		<?php foreach ($content_parts as $paragraph) : ?>
			<?php if ($paragraph = trim($paragraph)) : ?>
				<p><?= $paragraph; ?></p>
			<?php endif; ?>
		<?php endforeach; ?>

		<div class="product-actions" data-id="<?= $product->get_id(); ?>">
			<div class="price"><?= $product->get_price(); ?> руб</div>
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

<footer>
<?php include THEME_PATH . '/qr/footer.php'; ?>