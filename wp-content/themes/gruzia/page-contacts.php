<?php
/*
Template Name: Контакты
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

<div class="contacts-content">
	<div class="container">
		<div class="columns">
			<div class="column column_left">
				<h2>Наши контакты</h2>

				<?php if (have_rows('contacts', 'option')) : ?>
					<?php while (have_rows('contacts', 'option')) : the_row(); ?>
						<?php if ($phone = get_sub_field('phone_delivery_t')) : ?>
							<div class="row">
								<div class="key">Тирасполь (доставка)</div>
								<div class="val"><?php echo '<a href="tel:' . $phone . '">' . $phone . '</a>' ?></div>
								<div class="line"></div>
							</div>
						<?php endif; ?>
						<?php if ($phone = get_sub_field('phone_delivery_b')) : ?>
							<div class="row">
								<div class="key">Бендеры (доставка)</div>
								<div class="val"><?php echo '<a href="tel:' . $phone . '">' . $phone . '</a>' ?></div>
								<div class="line"></div>
							</div>
						<?php endif; ?>
						<?php if ($phone = get_sub_field('phone_reserv_t_center')) : ?>
							<div class="row">
								<div class="key">Тирасполь Центр (ресторан)</div>
								<div class="val"><?php echo '<a href="tel:' . $phone . '">' . $phone . '</a>' ?></div>
								<div class="line"></div>
							</div>
						<?php endif; ?>
						<?php if ($phone = get_sub_field('phone_reserv_t_balka')) : ?>
							<div class="row">
								<div class="key">Тирасполь Балка (ресторан)</div>
								<div class="val"><?= $phone; ?></div>
								<div class="line"></div>
							</div>
						<?php endif; ?>
						<?php if ($email = get_sub_field('email')) : ?>
							<div class="row">
								<div class="key">Эл. почта</div>
								<div class="val"><?= $email; ?></div>
								<div class="line"></div>
							</div>
						<?php endif; ?>
					</div>
				<?php endwhile; ?>
			<?php endif; ?>
			<form class="column column_right" method="post">
				<h2>Связаться с нами</h2>

				<div class="input_rows">
					<label>
						<input type="text" name="name" placeholder="Имя&thinsp;*" required>
						<div class="input_error" style="display: none">Не верное значение</div>
					</label>
					<label>
						<input type="text" name="phone" placeholder="Телефон&thinsp;*" required>
						<div class="input_error" style="display: none">Не верное значение</div>
					</label>
					<label>
						<input type="email" name="email" placeholder="E-mail">
						<div class="input_error" style="display: none">Не верное значение</div>
					</label>
				</div>

				<label class="label_textarea">
					<textarea name="message" cols="30" rows="10" placeholder="Ваше сообщение&thinsp;*" required></textarea>
					<div class="input_error" style="display: none">Не верное значение</div>
				</label>

				<button type="submit">Отправить</button>
			</form>
		</div>
		
		<?= get_the_content(); ?>

	</div>
</div>

<?php

	}
}

get_footer();