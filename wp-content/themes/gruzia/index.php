<?php
get_header();
?>

<div class="breadcrumbs_wrapper container">
	<div class="breadcrumbs">
		<ul>
			<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
				<a href="/" itemprop="url"><span itemprop="title">Ресторан Грузия</span></a>
			</li>
			<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">Новости / Мероприятия</li>
		</ul>

		<h1>Новости / Мероприятия</h1>
	</div>
</div>

<div class="promotions-archive">
	<div class="container">
		<div id="sidebar">

		    <?php
		    // 91 - упаковки
		    $categories = get_terms(['taxonomy' => 'product_cat', 'parent' => 0, 'exclude' => [91, 15]]);
			?>

		    <ul id="catalog-cats">
	    	<?php foreach ($categories as $cat) :
	    		if ($cat->slug == 'akcii') {
				    continue;
			    }
	    		$current = strpos($_SERVER['REQUEST_URI'], "category/{$cat->slug}") !== false;
	    		$tag = $current ? 'span' : 'a';
	    		$icon = wp_get_attachment_image_url(get_field('icon', $cat));
	    		?>
		    	<li>
		    		<?php if (!$current) : ?>
		    			<a href="<?= get_term_link($cat); ?>"><img src="<?= $icon; ?>" alt=""><?= $cat->name; ?></a>
		    		<?php else : ?>
		    			<span><img src="<?= $icon; ?>" alt=""><?= $cat->name; ?></span>
		    		<?php endif; ?>
		    	</li>
		    <?php endforeach; ?>
			</ul>
		</div>

		<div class="promotion-items">
			<?php if (have_posts()) : ?>
				<?php while (have_posts()) : the_post();
					$url = get_field('redirect') ? get_field('promo_view_url') : '';
					?>
					<div class="promotion-item">
						<a href="<?= $url ?: get_the_permalink(); ?>" class="promotion-image">
							<img src="<?php the_post_thumbnail_url('medium_large'); ?>" alt="">
						</a>
						<div class="promotion-info">
							<a href="<?= $url ?: get_the_permalink(); ?>" class="promotion-title"><?php the_title(); ?></a>
							<div class="promotion-content"><?= get_the_content(); ?></div>
							<?php if ($expires = get_field('expires')) : ?>
								<div class="promotion-expires">До <?= $expires; ?></div>
							<?php endif; ?>
						</div>
					</div>
				<?php endwhile; ?>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php
get_footer();