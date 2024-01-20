<?php
/**
 * My Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-address.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.6.0
 */

defined( 'ABSPATH' ) || exit;

$customer_id = get_current_user_id();

if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing'  => __( 'Billing address', 'woocommerce' ),
			'shipping' => __( 'Shipping address', 'woocommerce' ),
		),
		$customer_id
	);
} else {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing' => __( 'Billing address', 'woocommerce' ),
		),
		$customer_id
	);
}

$oldcol = 1;
$col    = 1;
?>

<?php if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) : ?>
	<div class="u-columns woocommerce-Addresses col2-set addresses">
<?php endif; ?>

<?php foreach ( $get_addresses as $name => $address_title ) : ?>
	<?php
		$col     = $col * -1;
		$oldcol  = $oldcol * -1;

		$getter  = "get_{$name}";
		$address = array();

		if ( 0 === $customer_id ) {
			$customer_id = get_current_user_id();
		}

		$customer = new WC_Customer( $customer_id );

		if ( is_callable( array( $customer, $getter ) ) ) {
			$address = $customer->$getter();
			unset( $address['email'], $address['tel'] );
		}

		if ($address['city'] == 0) {
			$address['city'] = 'Не выбран';
		} elseif ($address['city'] == 1) {
			$address['city'] = 'Тирасполь';
		} elseif ($address['city'] == 2) {
			$address['city'] = 'Бендеры';
		} elseif ($address['city'] == 3) {
			$address['city'] = 'Парканы';
		}
	?>

	<div class="account-data">
		<h3><?php echo esc_html( $address_title ); ?></h3>

		<table class="shop_table shop_table_responsive my_account_orders">
			<thead>
				<tr>
					<th><span class="nobr">Имя</span></th>
					<th><span class="nobr">Адрес</span></th>
					<th><span class="nobr">Город</span></th>
					<th><span class="nobr">Телефон</span></th>
					<th><span class="nobr">Действие</span></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td data-title="Имя"><span><?= $address['first_name'] ?: '&nbsp;'; ?></span></td>
					<td data-title="Адрес"><span><?= $address['address_1'] ?: '&nbsp;'; ?></span></td>
					<td data-title="Город"><span><?= $address['city'] ?: '&nbsp;'; ?></span></td>
					<td data-title="Телефон"><span><?= $address['phone'] ?: '&nbsp;'; ?></span></td>
					<td data-title="Действие">
						<a class="button" href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', $name ) ); ?>">Изменить</a>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

<?php endforeach; ?>

<?php if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) : ?>
	</div>
	<?php
endif;
