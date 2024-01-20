<?php
/**
 * Checkout billing information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-billing.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 * @global WC_Checkout $checkout
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="woocommerce-billing-fields">

	<?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>

	<div class="woocommerce-billing-fields__field-wrapper">
		<?php
		$fields = $checkout->get_checkout_fields( 'billing' );

		foreach ( $fields as $key => $field ) {
			$field['placeholder'] = $field['label'];
			$field['label'] = false;

			if ($key == 'billing_country') {
				continue;
			}
			if ($key == 'billing_city') {
				$field['type'] = 'select';
				$field['options'] = [
					0 => 'Город',
					1 => 'Тирасполь',
					2 => 'Бендеры',
					3 => 'Парканы'
				];
			}
			if ($key == 'billing_address_1') {
				$field['type'] = 'textarea';
			}

			woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );

			if ($key == 'billing_city') {
				wc_cart_totals_shipping_html();
				
				if ( WC()->cart->needs_payment() ) :
					if ( WC()->cart->needs_payment() ) {
						$available_gateways = WC()->payment_gateways()->get_available_payment_gateways();
						WC()->payment_gateways()->set_current_gateway( $available_gateways );
					} else {
						$available_gateways = array();
					}

					$field = [];
					$field['label'] = '';
					$field['required'] = 1;
					$field['class'] = ['form-row-wide', 'address-field'];
					$field['label'] = '';
					$field['priority'] = 12;
					$field['placeholder'] = 'Оплата';
					$field['type'] = 'select';
					$field['options'] = [];

					if ( ! empty( $available_gateways ) ) {
						foreach ( $available_gateways as $gateway ) {
							$field['options'][$gateway->id] = $gateway->title;
						}

						woocommerce_form_field( 'payment_method', $field, $checkout->get_value( 'payment_method' ) );
					} else {
						$field['options'][0] = 'Нет доступных методов оплаты';
					}
				endif;
			}
		}
		?>
	</div>

	<?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>
</div>

<?php if ( ! is_user_logged_in() && $checkout->is_registration_enabled() ) : ?>
	<div class="woocommerce-account-fields">
		<?php if ( ! $checkout->is_registration_required() ) : ?>

			<p class="form-row form-row-wide create-account">
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
					<input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" id="createaccount" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true ); ?> type="checkbox" name="createaccount" value="1" /> <span><?php esc_html_e( 'Create an account?', 'woocommerce' ); ?></span>
				</label>
			</p>

		<?php endif; ?>

		<?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

		<?php if ( $checkout->get_checkout_fields( 'account' ) ) : ?>

			<div class="create-account">
				<?php foreach ( $checkout->get_checkout_fields( 'account' ) as $key => $field ) : ?>
					<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
				<?php endforeach; ?>
				<div class="clear"></div>
			</div>

		<?php endif; ?>

		<?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>
	</div>
<?php endif; ?>
