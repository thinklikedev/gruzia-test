<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="breadcrumbs_wrapper container">
	<div class="breadcrumbs">
		<ul>
			<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
				<a href="/" itemprop="url"><span itemprop="title">Ресторан Грузия</span></a>
			</li>
			<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><?= $order->has_status( 'failed' ) ? 'Заказ не оплачен' : 'Заказ принят'; ?></li>
		</ul>

		<h1><?= $order->has_status( 'failed' ) ? 'Заказ не оплачен' : 'Заказ принят'; ?></h1>
	</div>
</div>

<section class="info-block">
	<?php
	if ( $order ) :

		do_action( 'woocommerce_before_thankyou', $order->get_id() );
		?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>

			<h2>Заказ не оплачен</h2>

			<div class="text">К сожалению, ваш заказ не может быть выполнен, так как система обработки платежей не подтвердила вашу транзакцию.</div>

		<?php else : ?>

			<h2>Благодарим Вас за заказ!</h2>

			<div class="text">Заказ #<?= $order->get_order_number(); ?> поступил в обработку, в ближайшее время наш менеджер свяжется с вами.</div>

		<?php endif; ?>

	<?php else : ?>

		<h2>Благодарим Вас за заказ!</h2>

		<div class="text">Заказ поступил в обработку, в ближайшее время наш менеджер свяжется с Вами.</div>

	<?php endif; ?>
</div>