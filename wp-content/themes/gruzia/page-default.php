<?php
/*
Template Name: Дефолтная страница
*/

$images = get_field('images');
$tabs_gallery = get_field('tabs_gallery');

if ($images || $tabs_gallery) {
    wp_enqueue_style('magnific-popup-css', get_template_directory_uri() . '/css/magnific_popup.css');
	wp_enqueue_script('magnific-popup-js', get_template_directory_uri() . '/js/jquey.magnific_popup.js', [], false, 1);
}

get_header();

if (have_posts()) {
	while (have_posts()) {
		the_post();
?>

<div class="breadcrumbs_wrapper container">
	<div class="breadcrumbs">
		<ul>
			<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
				<a href="/" itemprop="url"><span itemprop="title">Ресторан Грузия</span></a>
			</li>
			<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><? the_title(); ?></li>
		</ul>

		<h1><? the_title(); ?></h1>
	</div>
</div>

<div class="restaurant">
	<div class="container">
		<div class="restaurant-content">
			<?php the_content(); ?>
		</div>

		<?php
		if ($contacts_block = get_field('contacts_block')) : ?>
			<div class="restaurant-phones rows-<?= count($contacts_block); ?>">
				<?php foreach ($contacts_block as $contact_row) : ?>
					<div class="restaurant-phone">
						<h3><?= $contact_row['label']; ?></h3>
						<?php foreach ($contact_row['contacts'] as $contact) : ?>
							<p><?= $contact['contact']; ?></p>
						<?php endforeach; ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif ?>

		<?php if ($images) : ?>
			<h2 class="block-title">Галерея</h2>

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

		<?php if ($tabs_gallery) : ?>
			<ul class="nav nav-pills gallery-tabs">
				<?php foreach ($tabs_gallery as $key => $gallery) : ?>
					<li class="<?= $key == 0 ? 'active' : ''; ?>" data-index="<?= $key; ?>">
						<a href="#"><?= $gallery['label']; ?></a>
					</li>
				<?php endforeach; ?>
			</ul>


			<div class="gallery-images">
				<?php foreach ($tabs_gallery as $key => $gallery) : ?>
					<div class="gallery-images-block" data-index="<?= $key; ?>" style="<?= $key > 0 ? 'display:none' : ''; ?>">
						<?php foreach ($gallery['images'] as $image_id) :
							$image_url = wp_get_attachment_image_url($image_id, 'large');
							?>
							<a class="gallery-image-item" href="<?= $image_url; ?>">
								<img src="<?= $image_url; ?>" alt="">
							</a>
						<?php endforeach; ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php

	}
}

get_footer();