<?php
$cart_quant = qr_get_cart_items_quantity();
$orders_quant = qr_get_orders_quantity();

?>
	<ul>
		<li class="<?= strpos($GLOBALS['current_uri'], 'qr-menu') !== false ? 'active' : ''; ?>">
			<a href="/qr-menu/">
				<?php qr_icon('menu'); ?>
				<span class="name">Меню</span>
			</a>
		</li>
		<li class="<?= strpos($GLOBALS['current_uri'], 'qr-special') !== false ? 'active' : ''; ?>">
			<a href="/qr-special/">
				<?php qr_icon('special'); ?>
				<span class="name">Акции</span>
			</a>
		</li>
		<li class="cart <?= $GLOBALS['current_uri'] == 'qr-cart' ? 'active' : ''; ?>">
			<a href="/qr-cart/">
				<?php qr_icon('cart'); ?>
				<span class="name">Корзина</span>
				<span class="counter" style="<?= $cart_quant ? '' : 'display: none'; ?>"><?= $cart_quant; ?></span>
			</a>
		</li>
		<li class="orders <?= $GLOBALS['current_uri'] == 'qr-orders' ? 'active' : ''; ?>">
			<a href="/qr-orders/">
				<?php qr_icon('orders'); ?>
				<span class="name">Мои заказы</span>
				<span class="counter" style="<?= $orders_quant ? '' : 'display: none'; ?>"><?= $orders_quant; ?></span>
			</a>
		</li>
	</ul>

	<script src="/wp-includes/js/jquery/jquery.min.js"></script>
	<script src="<?= get_template_directory_uri(); ?>/qr/js/scripts.js?v=1.126"></script>
</footer>

</body>
</html>