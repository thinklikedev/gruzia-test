<?php
include THEME_PATH . '/qr/header.php';

if ($GLOBALS['qr_category']) {
	$args = [
		'post_status' => 'publish',
		'post_type' => 'product',
		'numberposts' => -1,
		'tax_query' => [
			[
				'taxonomy' => 'product_class',
				'field' => 'slug',
				'terms' => ['qr-menu']
			],
			[
				'taxonomy' => 'product_cat',
				'terms' => $GLOBALS['qr_category']->term_id
			]
		]
	];

	$products = get_posts($args);
} else {
	$products = [];
}

$categories = get_terms([
	'taxonomy' => 'product_cat', 
	'exclude' => [100, 15],
	'meta_query' => [
		'relation' => 'OR',
		[
			'key' => 'qr_hide',
			'value' => 0,
			'compare' => '='
		],
		[
			'key' => 'qr_hide',
			'value' => '',
			'compare' => 'NOT EXISTS'
		]
	],
	'hide_empty' => true
]);

?>

<header>
	<h1 class="menu">
		<a href="/qr-menu/" class="back-menu cat-nav" style="display:none">
			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z"></path></svg>
		</a>
		<span>Меню</span>
	</h1>
	<div class="category active"><?= @$GLOBALS['qr_category']->name ?: ''; ?></div>
	<div class="menu-helper menu">Закрыть</div>
</header>

<div class="categories <?= !$products ? 'opened' : '' ?>">
	<div class="search-field">
		<input type="text" placeholder="Искать">
	</div>
	<h3 class="cat-title">Категории:</h3>

	<ul class="cat-list">
		<?php foreach ($categories as $category) :
			$thumb_id = get_term_meta($category->term_id, 'thumbnail_id', true);
			$image = wp_get_attachment_image_url($thumb_id, 'thumbnail') ?: wc_placeholder_img_src('woocommerce_single', 'medium');
			?>
			<li data-id="<?= $category->term_id; ?>" data-parent="<?= $category->parent; ?>" style="<?= $category->parent ? 'display:none' : ''; ?>">
				<a href="/qr-menu/<?= $category->slug; ?>/">
					<img src="<?= $image; ?>" alt="">
					<span><?= $category->name; ?></span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>

	<h3 class="prod-title">
		Блюда: 
		<div id="circularG">
			<div id="circularG_1" class="circularG"></div>
			<div id="circularG_2" class="circularG"></div>
			<div id="circularG_3" class="circularG"></div>
			<div id="circularG_4" class="circularG"></div>
			<div id="circularG_5" class="circularG"></div>
			<div id="circularG_6" class="circularG"></div>
			<div id="circularG_7" class="circularG"></div>
			<div id="circularG_8" class="circularG"></div>
		</div>
	</h3>

	<ul class="prod-list"></ul>
	<div class="nothing-found">Ничего не найдено.</div>
</div>

<div class="page menu">
	<div class="products">
		<?php foreach ($products as $product) :
			setup_postdata($GLOBALS['post'] = $product);
			global $product;
			$in_cart = qr_get_cart_item_quantity($product->get_id());
			?>
			<div class="product-item">
				<a href="/qr-menu/product-<?= $product->get_slug(); ?>" class="product-image">
					<img src="<?= get_the_post_thumbnail_url($product->get_id(), 'thumbnail') ?: wc_placeholder_img_src('thumbnail'); ?>" alt="" loading="lazy">
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
	<?php $display = $products ? 'display: none' : ''; ?>
	<div class="content-empty" style="<?= $display; ?>">
		<!-- <img src="<?= get_template_directory_uri(); ?>/qr/icons/cart-black.png" alt=""> -->
		<div class="empty-message">Здесь пока пусто</div>
	</div>
</div>

<div class="open-menu open-menu-bottom"></div>
<div class="open-menu open-menu-middle"></div>
<div class="open-menu open-menu-upper"></div>
<div class="open-menu open-menu-bulb"></div>
<div class="open-menu open-menu-rod"></div>
<div class="open-menu open-menu-text">Меню</div>

<footer>
<?php include THEME_PATH . '/qr/footer.php'; ?>