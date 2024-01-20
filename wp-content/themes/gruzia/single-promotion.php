<?php
add_filter('body_class', function($classes) {
	return array_merge($classes, ['single-product', 'woocommerce']);
}, 10, 1);
?>

<?php get_header(); ?>
	
<div id="single-product" class="single_promotion">
	<div class="container">
		<!--
		<a href="/akcii/" class="back-to-list">
			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
			<path d="M0.434314 7.43431C0.121895 7.74673 0.121895 8.25327 0.434314 8.56569C0.746734 8.87811 1.25327 8.87811 1.56569 8.56569L0.434314 7.43431ZM6.56569 3.56569C6.8781 3.25327 6.8781 2.74673 6.56569 2.43431C6.25327 2.1219 5.74673 2.1219 5.43431 2.43431L6.56569 3.56569ZM1.56569 8.56569L6.56569 3.56569L5.43431 2.43431L0.434314 7.43431L1.56569 8.56569Z" fill="#ebebeb"/>
			<path d="M1 8H13" stroke="#ebebeb" stroke-width="1.6" stroke-linecap="round"/>
			<path d="M1.56572 7.43434C1.2533 7.12192 0.746764 7.12192 0.434345 7.43434C0.121926 7.74676 0.121926 8.2533 0.434345 8.56572L1.56572 7.43434ZM5.43431 13.5657C5.74673 13.8781 6.25327 13.8781 6.56569 13.5657C6.8781 13.2533 6.8781 12.7467 6.56569 12.4343L5.43431 13.5657ZM0.434345 8.56572L5.43431 13.5657L6.56569 12.4343L1.56572 7.43434L0.434345 8.56572Z" fill="#ebebeb"/>
			</svg>
			Вернуться к списку
		</a>
		-->
		<div class="single-product-data">
			<div class="woocommerce-product-gallery" style="opacity: 1;">
			    <div class="image_wrapper">
			        <img src="<?= the_post_thumbnail_url('large'); ?>" alt="">
			    </div>
			</div>
			<div class="single-content">
				<div class="summary entry-summary">
					<ul class="tags"><li><a href="/akcii/">Акции</a></li></ul>
			    	<h1 class="product_title entry-title"><?php the_title(); ?></h1>
			    	<div class="product_descr"><?php the_content(); ?></div>

			    	<?php
			    	$label = get_field('promo_view_label');
			    	$url = get_field('promo_view_url');
			    	?>

			    	<?php if ($label && $url) : ?>
			    		<a href="<?= $url; ?>" class="view_promotion"><?= $label; ?></a>
			    	<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>