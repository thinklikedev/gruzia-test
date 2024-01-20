<?php include THEME_PATH . '/qr/header.php'; ?>

<header>
	<h1><a href="/qr-special/" class="back-menu"><?php qr_icon('arrow'); ?></a> Акции</h1>
</header>

<div class="page">
	<div class="single-product promotion">
		<img class="single-image" src="<?= get_the_post_thumbnail_url($post->ID, 'thumbnail') ?: wc_placeholder_img_src('large'); ?>" alt="">

		<h1><?= $post->post_title; ?></h1>

		<?= $post->post_content; ?>
	</div>
</div>

<footer>
<?php include THEME_PATH . '/qr/footer.php'; ?>