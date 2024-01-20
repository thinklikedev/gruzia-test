<?php
include THEME_PATH . '/qr/header.php';

$cookie_orders = qr_get_orders();
$orders = [];

foreach ($cookie_orders as $order_id => $order_items) {
	$products = get_posts([
		'post_status' => 'publish',
		'post_type' => 'product',
		'include' => array_keys($order_items),
		'tax_query' => [
			[
				'taxonomy' => 'product_class',
				'field' => 'slug',
				'terms' => ['qr-menu']
			]
		]
	]);

	if (count($products) < count($order_items)) {
		remove_qr_order($order_id);
	} else {
		$orders[$order_id] = $products;
	}
}

?>

<header>
	<h1>Мои заказы</h1>
	<div class="menu-helper orders active">Очистить</div>
</header>

<div class="page orders">
	<?php if ($orders) : ?>
		<div class="products">
			<?php
			$index = 0;
			foreach ($orders as $order_id => $products) :
				$index++;
				$total = 0;
				$orders_html = '';

				foreach ($products as $key => $product) {
					setup_postdata($GLOBALS['post'] = $product);
					global $product;
					$quantity = $cookie_orders[$order_id][$product->get_id()];
					$price = $product->get_price() * $quantity;
					$total += $price;

					// if ($key == 4 && isset($products[5])) {
					// 	$orders_html .= '<li style="color:grey">+ Ещё 2 товара</li>';
					// 	break;
					// }

					$orders_html .= "<li>* ".$product->get_name()." ($quantity ШТ) - {$price} руб</li>";
				}
				?>

				<div class="product-item" data-id="<?= $order_id; ?>">
					<div class="order-data">
						<div class="order-name">
							<div>Заказ #<span><?= $index; ?> (<?= date('Y-m-d', $order_id / 1000); ?>)</span></div>
							<span class="order-total">ИТОГО: <?= $total; ?> РУБ</span>
						</div>
						<ul>
							<?= $orders_html; ?>
						</ul>
						<div class="order-delete">×</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php $display = $orders ? 'display: none' : ''; ?>
	<div class="content-empty" style="<?= $display; ?>">
		<img src="<?= get_template_directory_uri(); ?>/qr/icons/orders-empty.png" alt="">
		<div class="empty-message">Заказов не создано.</div>
	</div>
</div>

<footer>
<?php include THEME_PATH . '/qr/footer.php'; ?>