<?php
$context = stream_context_create([
    "ssl" => [
        "verify_peer" => false,
        "verify_peer_name" => false,
    ],
]);
?>
</div>
	<!-- подвал -->
	<footer id="footer" class="footer-background">
		<?php if (have_rows('contacts', 'option')) : ?>
			<?php while (have_rows('contacts', 'option')) : the_row(); ?>
				<div class="footer-row">
					<div class="container">
						<h5 class="min-tablet">Служба доставки:</h5>
						<h5 class="max-mobile">Связь с нами<span class="dropdown-icon"></span></h5>
						<ul>
							<?php if ($phone = get_sub_field('phone_delivery_t')) : ?>
								<li>
									<span>Тирасполь:</span>
									<?= file_get_contents(image_url('phone.svg'),false,$context) . '<a href="tel:' . $phone . '">' . $phone . '</a>'?>
								</li>
							<?php endif; ?>
							<?php if ($phone = get_sub_field('phone_delivery_b')) : ?>
								<li>
									<span>Бендеры:</span>
									<?= file_get_contents(image_url('phone.svg'),false,$context) . $phone; ?>
								</li>
							<?php endif; ?>
						</ul>
					</div>
				</div>
			<?php endwhile; ?>
		<?php endif; ?>
		<div class="footer-row">
			<div class="container">
				<div class="column">
					<div id="footer-logo">
						<img src="<?= image_url('logo.svg'); ?>" alt="">
						<!-- <div class="logo-description">Ресторан Грузия осуществляет доставку по г. Тирасполь, Бендеры и прилегающим к ним селам</div> -->
						<div class="logo-description">Ресторан Грузия - ресторан с традиционной грузинской кухней в Тирасполе</div>
					</div>
				</div>
				<div class="column">
					<h5>О нас<span class="dropdown-icon"></span></h5>
					<ul>
						<li><a href="/restaurant/">Наш ресторан</a></li>
						<li><a href="<?= get_term_link($GLOBALS['curr_cat']); ?>">Меню ресторана</a></li>
						<li><a href="/banket/">Банкетные залы</a></li>
						<li><a href="/contacts/">Контакты</a></li>
					</ul>
				</div>
				<div class="column">
					<h5>Полезное<span class="dropdown-icon"></span></h5>
					<ul>
						<li><a href="/news/">Новости / Мероприятия</a></li>
						<li><a href="https://job.hi-tech.md/?company=Ресторан" target="_blank">Вакансии</a></li>
						<li><a href="/akcii/">Акции</a></li>
						<!-- <li><a href="/delivery/">Доставка</a></li> -->
					</ul>
				</div>
				<?php if (have_rows('work_time', 'option')) : ?>
					<div class="column">
						<h5 class="is-closed">График работы<span class="dropdown-icon"></span></h5>
						<ul>
							<?php while (have_rows('work_time', 'option')) : the_row(); ?>
								<?php if ($work_mode = get_sub_field('work_mode')) : ?>
									<li>Режим работы: <span><i class="fa  fa-clock-o"></i> <?= $work_mode; ?></span></li>
								<?php endif; ?>
								<?php if ($taking_orders = get_sub_field('taking_orders')) : ?>
									<li>Прием заказов: <span><i class="fa  fa-clock-o"></i> <?= $taking_orders; ?></span></li>
								<?php endif; ?>
								<?php if ($order_delivery = get_sub_field('order_delivery')) : ?>
									<li>Доставка заказов: <span><i class="fa  fa-clock-o"></i> <?= $order_delivery; ?></span></li>
								<?php endif; ?>
							<?php endwhile; ?>
						</ul>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<div class="footer-row">
			<div class="container">
				<div class="column">
					<h5>Наше приложение:</h5>
					<a href="https://gruzia.md/" target="_blank" class="gpl">
						<img src="https://gruzia.md/wp-content/themes/gruzia/images/google_pl.png">
					</a>
					<a class="max-mobile aps" href="https://gruzia.md/" target="_blank">
						<img src="https://gruzia.md/wp-content/themes/gruzia/images/appstore.png">
					</a>
				</div>
				<div class="column">
					<a href="https://gruzia.md/" target="_blank" class="aps">
						<img src="https://gruzia.md/wp-content/themes/gruzia/images/appstore.png">
					</a>
				</div>
				<div class="column">
					<?php if (have_rows('socials', 'option')) : ?>
						<h5 class="min-tablet">Мы в соцсетях:</h5>
						<h5 class="is-closed max-mobile">Мы в соцсетях<span class="dropdown-icon"></span></h5>
						<ul>
							<?php while (have_rows('socials', 'option')) : the_row();
								$instagram = array_filter(array_map('trim', explode(',', get_sub_field('instagram'))));
								$facebook = array_filter(array_map('trim', explode(',', get_sub_field('facebook'))));
								?>
								<?php foreach ($instagram as $url) : ?>
									<li>
										<a href="<?= $url; ?>" target="_blank">
											<?= file_get_contents(image_dir('instagram.svg'),false,$context); ?>
										</a>
									</li>
								<?php endforeach; ?>
								<?php foreach ($facebook as $url) : ?>
									<li>
										<a href="<?= $url; ?>" target="_blank">
											<?= file_get_contents(image_dir('facebook.svg'),false,$context); ?>
										</a>
									</li>
								<?php endforeach; ?>
	                  <?php endwhile; ?>
						</ul>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</footer>
</div>

<?php wp_footer(); ?>
<?php $ym_id = 0; ?>
<!-- Yandex.Metrika counter -->
<script >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(<?= $ym_id; ?>, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true
   });
</script>

<noscript><div><img src="https://mc.yandex.ru/watch/<?= $ym_id; ?>" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
</body>
</html>