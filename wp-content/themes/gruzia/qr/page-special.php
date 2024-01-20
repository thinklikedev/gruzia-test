<?php
include THEME_PATH . '/qr/header.php';

$promotions = get_posts([
	'post_type' => 'promotion',
	'post_status' => 'publish'
]);

?>

<header>
	<h1>Акции</h1>
</header>

<div class="page">
	<div class="products">
		<?php foreach ($promotions as $promotion) : ?>
			<div class="product-item">
				<a href="/qr-special/<?= $promotion->post_name; ?>/" class="product-image">
					<img src="<?= get_the_post_thumbnail_url($promotion, 'thumbnail') ?: wc_placeholder_img_src('thumbnail'); ?>" alt="">
				</a>
				<div class="product-data">
					<a href="/qr-special/<?= $promotion->post_name; ?>/" class="product-name"><?= $promotion->post_title; ?></a>
					<div class="product-descr"><?= $promotion->post_content; ?></div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>

<footer>
<?php include THEME_PATH . '/qr/footer.php'; ?>