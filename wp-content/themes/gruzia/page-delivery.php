<?php
/*
Template Name: Доставка
*/

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

<div class="delivery-page">
	<div class="container">
		<div class="delivery-content">
			<div class="delivery-description">
				<?= get_the_content(); ?>
			</div>
			<table>
				<thead>
					<tr>
						<th>Город</th>
						<th>Телефон</th>
						<th>Стоимость</th>
						<th>Бесплатная доставка</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach (get_field('shipping_methods') as $method) : ?>
						<tr>
							<td><?= $method['city']; ?></td>
							<td><?= $method['phone']; ?></td>
							<td><?= $method['price']; ?>р</td>
							<td><?= $method['free_from']; ?>р</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php

	}
}

get_footer();