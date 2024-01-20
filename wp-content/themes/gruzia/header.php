<?php global $post; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="utf-8">
<meta name="keywords" content="Ресторан, суши, бургер, пицца, грузия">

<?php if (is_product()) : ?>
	<!-- <meta property="og:image" content="<?php the_post_thumbnail_url('medium'); ?>" /> -->
<?php else : ?>
	<!-- <meta property="og:image" content="<?= get_template_directory_uri() . "/images/logo.svg"; ?>" /> -->
<?php endif; ?>
<?php wp_head(); ?>

<meta name="yandex-verification" content="f1ca4418b8501262" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />


<link rel="shortcut icon" type="image/x-icon" href="<?= get_template_directory_uri() . "/images/favicon.ico"; ?>" />
<link rel="apple-touch-icon" sizes="180x180" href="<?= get_template_directory_uri() . "/images/apple-touch-icon.png"; ?>">
<link rel="icon" type="image/png" sizes="32x32" href="<?= get_template_directory_uri() . "/images/favicon-32x32.png"; ?>">
<link rel="icon" type="image/png" sizes="16x16" href="<?= get_template_directory_uri() . "/images/favicon-16x16.png"; ?>">

<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css"
  />

<script>
	$ = jQuery;
</script>
</head>

<body <?php body_class('home-page') ?>>
	<input type="checkbox" class="nav-toggle" hidden>

	<div class="cart-wrapper" style="opacity: 0.0001;pointer-events: none">
		<?php woocommerce_mini_cart(); ?>
	</div>

	<div class="wrapper">
		<header class="header">
			<div class="container">
				<button class="nav-mobile-btn is-closed" data-toggle="offcanvas">
					<span class="hamb-top"></span>
					<span class="hamb-middle"></span>
					<span class="hamb-bottom"></span>
				</button>

				<?php $count = WC()->cart->get_cart_contents_count(); ?>

				<?php
				add_filter('wp_nav_menu_items', function($items, $args) {
					if ($args->container_id != 'nav-desc') {
						return $items;
					}
					$cats_list = '';

					$categories = get_terms(['taxonomy' => 'product_cat', 'parent' => 0, 'exclude' => [91, 15]]);
					
					foreach ($categories as $cat) {
					    if ($cat->slug == 'akcii') {
					        continue;
					    }
						$cats_list .= '<li><a  class="line-bounce" href="'. get_term_link($cat) .'">'. $cat->name . '</a></li>';
					}

					return 
						'<li class="nav-logo">
							<a href="/gruzia-test/">
								<img src="'. get_template_directory_uri() ."/images/logo.svg". '" alt="">
							</a>
						</li>
						<ul class="nav-menu">
							<li class="menu-dropdown">
								<a class="line-bounce" href="'. get_term_link($GLOBALS['curr_cat']) .'">Меню ресторана</a>
								<div class="menu-categories"><ul>'. $cats_list .'</ul></div>
							</li>'
						. $items . 
						'</ul><li class="account-button">
							<a href="/my-account">'. file_get_contents(image_dir('profile.svg')) .'</a>
						</li>
						<li class="cart-button">' .
							file_get_contents(image_dir('cart.svg')) .
							'<span>'. WC()->cart->get_cart_contents_count() .'</span>
						</li>';
				}, 10, 2);

				wp_nav_menu([
					'theme_location' => 'main-menu',
					'container' => 'nav',
					'container_id' => 'nav-desc',
					'container_class' => 'navbar',
					'menu_class' => 'nav navbar-nav',
					'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
					'depth'           => 2,
					'walker'          => new Main_Menu_Nav()
				]);
				?>

			</div>
		</header>
		
		<?php $filed = 'slider_images' . ''; ?>
		<?php if ($image_ids = get_field($filed, $page_id)) : ?>
			<div class="main-slider">
				<div class="owl-carousel">
					<?php foreach ($image_ids as $image_id) : ?>
							<a href="<?= get_the_content(null, false, $image_id); ?>" class="slider-item" style="background-image: url('<?= wp_get_attachment_image_url($image_id, 'full'); ?>');">
									<li class="nav-logo">
									<?= file_get_contents(image_dir('logo.svg'),false); ?>
									</li>
									<p class="owl-title">Ресторан <span>Georgia</span> - ресторан с традиционной грузинской кухней в Тирасполе</p>
									<input type="button" onclick="location.href='https://gruzia.md'" value="Зарезервировать стол" class="btn btn-reserv">
									<div id="section10" class="demo">
										<div class="owl-scroll"><span></span></div>
									</div>
							</a>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="widget-mobile-menu">
			<div class="side-panel side-panel_no-languages">
				<div class="side-panel__content">
					<button type="button" id="hide-side-panel-btn" class="button">✕</button>

					<div class="menu">
						<div class="menu__head">
		                    <a href="/">
		                       <img src="/wp-content/themes/gruzia/images/logo.svg" alt="">
		                    </a>
		                </div>

						<div class="menu__section">
							<div class="menu__title">Страницы</div>
							<?php
							wp_nav_menu([
								'theme_location' => 'mobile-menu',
								'container' => false,
								'menu_class' => 'menu__list',
								'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
								'depth'           => 2,
								'walker'          => new Mobile_Menu_Nav()
							]);
							?>
						</div>
					</div>

					<div class="contacts">
	                    <div class="contacts__head">Контакты</div>
                        
	                    <?php if (have_rows('contacts', 'option')) : ?>
							<?php while (have_rows('contacts', 'option')) : the_row(); ?>
								<div class="mobile-contact first">
				                    <div class="address"><i class="fa fa-map-marker"></i>г. Тирасполь, пер. Набережный, 3</div>
				                    <?php if ($phone = get_sub_field('phone_delivery_t')) : ?>
										<a href="tel:<?= str_replace([' ', '+', '-', '(', ')'], '', $phone); ?>"><i class="fa fa-phone"></i><?= $phone; ?></a>
									<?php endif; ?>
									<?php if ($phone = get_sub_field('phone_reserv_t_center')) : ?>
										<a href="tel:<?= str_replace([' ', '+', '-', '(', ')'], '', $phone); ?>"><i class="fa fa-phone"></i><?= $phone; ?></a>
									<?php endif; ?>
			                    </div>

							<?php endwhile; ?>
						<?php endif; ?>

	                    <ul class="socials-box footer-socials pull-left">
							<li>
								<a href="https://www.instagram.com/georgia_tiraspol/">
									<div class="social-circle-border"><i class="fa fa-instagram"></i></div>
								</a>
							</li>
						</ul>
					</div>

					<a href="https://kastamd.app.link/94Zb2qgDkab">
						<!-- <img class="app-banner" src="/wp-content/themes/restaurant/images/app.png" alt=""> -->
					</a>
				</div>
			</div>
		</div>
		<div class="page_wrapper">