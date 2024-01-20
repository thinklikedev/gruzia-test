<?php

// add_filter('body_class', function($classes) {
// 	$classes[] = 'woocommerce';
// 	return $classes;
// }, 10, 1);

$images = get_field('images');
$tabs_gallery = get_field('tabs_gallery');

if ($images || $tabs_gallery) {
    wp_enqueue_style('magnific-popup-css', get_template_directory_uri() . '/css/magnific_popup.css');
	wp_enqueue_script('magnific-popup-js', get_template_directory_uri() . '/js/jquey.magnific_popup.js', [], false, 1);
}

get_header();

$categories = get_terms(['taxonomy' => 'product_cat', 'parent' => 0, 'exclude' => [91, 15]]);

foreach ($categories as $key => $category) {
	if ($category->slug == 'akcii') {
		unset($categories[$key]);
	}
}

$prod_args = [
	'post_type' => 'product',
	'post_status' => 'publish',
];

$prod_ids = get_field('popular_products');

if ($prod_ids) {
	$prod_args['post__in'] = (array)$prod_ids;
} else {
	$prod_args['meta_key'] = 'total_sales';
    $prod_args['orderby'] = 'meta_value_num';
    $prod_args['posts_per_page'] = 10;
}

$popular_products = new WP_Query($prod_args);

$promotions = new WP_Query([
	'post_type' => 'promotion',
	'post_status' => 'publish',
    'posts_per_page' => 5
]);


if (have_posts()) {
	while (have_posts()) {
		the_post();

		$page_id = get_the_ID();
?>

<section class="front-page">

	<?php if ($categories) :
		$curr_cat = @array_shift(get_terms(['taxonomy' => 'product_cat', 'number' => 1, 'parent' => 0, 'exclude' => [91, 15]]));
		?>
		<div class="container categories-title max-mobile-sm <?= (is_mobile() && !$image_ids) ? 'no-slides' : ''; ?>">
			<h2 class="block-title">Меню ресторана</h2>
		</div>

		<div id="category-slider" class="category-slider">
			<div class="category-slider-wrapper <?= (is_mobile() && !$image_ids) ? 'no-slides' : ''; ?>">
				<div class="container owl-carousel">
					<?php foreach ($categories as $cat) :
						$thumb_id = get_term_meta($cat->term_id, 'thumbnail_id', true);
						$image = wp_get_attachment_image_url($thumb_id, 'category') ?: wc_placeholder_img_src('category');
						?>
						<a href="<?= get_term_link($cat); ?>" class="category_item">
							<div class="category_image">
			                	<img src="<?= $image; ?>">
			                </div>
			                <span class="category_name"><?= $cat->name; ?></span>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<div class="page-content">
		<?php if ($popular_products->have_posts()) : ?>
			<div class="popular-products <?= (is_mobile() && !$image_ids) ? 'no-slides' : ''; ?>">
				<div class="container">
					<h2 class="block-title"><span>Популярные блюда</span><a class="line-bounce" href="#">перейти к категории -&gt; </a></h2>

				    <div class="container">
				        <div class="products-grid">
							<?php while ($popular_products->have_posts()) : $popular_products->the_post();
								global $product;
								?>
								<?php wc_get_template_part('content', 'product'); ?>
							<?php endwhile; ?>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<?php if ($promotions->have_posts()) : ?>
			<div class="promotions">
				<div class="container">
					<h2 class="block-title"><span>Акции</span><a class="line-bounce" href="/akcii/">перейти к категории -&gt; </a></h2>

				    <div class="promotions-slider owl-carousel">
				        <?php while ($promotions->have_posts()) : $promotions->the_post();
							$image = get_the_post_thumbnail_url(get_the_ID(), 'large') ?: wc_placeholder_img_src('large');
							?>
							<div class="promotion">
								<div class="item-bg-box product-item goods_img">
									<div class="image">
								        <a href="<?php the_permalink(); ?>">
								            <img src="<?= $image; ?>" alt="">
								        </a>
						    		</div>
									<div class="info" style="width:100%;">
										<h3 class="section-title-border text-uppercase shotlink">
									        <a href="<?php the_permalink(); ?>">
									        	<?php echo file_get_contents(image_dir('promotion.svg')); ?>
									        	<?php the_title(); ?>
									        </a>
									    </h3>
									    <div class="promotion-text">
									    	<?= the_content(); ?>
									    </div>
									</div>
								</div>
							</div>
						<?php endwhile; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<div class="gallery">
			<div class="container">
				<?php if ($images) : ?>
					<h2 class="block-title"><span>Галерея</span><a class="line-bounce" href="/akcii/">перейти к галереи -&gt; </a></h2>

					<div class="restaurant-images-wrapper">
						<div class="restaurant-images owl-carousel">
							<?php foreach ($images as $image_url) :
								$image_url = wp_get_attachment_image_url($image_url, 'large');
								?>
								<a class="image-wrapper" href="<?= $image_url; ?>">
									<img src="<?= $image_url; ?>" alt="">
								</a>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<div class="map">
			<div class="container">
				<h2 class="block-title"><span>Контакты</span><a class="line-bounce" href="/akcii/">перейти к контактам -&gt; </a></h2>
				<iframe class="contacts-map br-none" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2295.2024725851215!2d29.613198811105896!3d46.83441508596308!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40c90312964a13dd%3A0xa790f6664838f947!2sPARK%20HOTEL!5e0!3m2!1sru!2s!4v1676971345349!5m2!1sru!2s" width="100%" height="400" allowfullscreen="allowfullscreen"></iframe>
			</div>
		</div>
		<?php if ($seo_descr = get_field('seo_descr', $page_id)) : ?>
			<div class="page-description container">
				<div class="page-descr-content">
					<?= $seo_descr; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php

	}
}

get_footer();