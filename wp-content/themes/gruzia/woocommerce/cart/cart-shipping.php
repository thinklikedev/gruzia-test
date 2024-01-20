<?php
/**
 * Shipping Methods Display
 *
 * In 2.1 we show methods per package. This allows for multiple methods per order if so desired.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-shipping.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

$formatted_destination    = isset( $formatted_destination ) ? $formatted_destination : WC()->countries->get_formatted_address( $package['destination'], ', ' );
$has_calculated_shipping  = ! empty( $has_calculated_shipping );
$show_shipping_calculator = ! empty( $show_shipping_calculator );
$calculator_text          = '';
?>
<?php if ( $available_methods ) :
	$field = [
		'type' => 'select',
		'options' => [],
		'input_class' => ['shipping_method'],
		'custom_attributes' => ['data-index' => $index]
	];
	?>
	<?php foreach ( $available_methods as $key => $method ) : ?>
		<?php $field['options'][$method->id] = $method->get_label(); ?>
	<?php endforeach;

	woocommerce_form_field( 'shipping_method['.$index.']', $field, $chosen_method );
endif;
?>

<?php if ( $show_package_details ) : ?>
	<?php echo '<p class="woocommerce-shipping-contents"><small>' . esc_html( $package_details ) . '</small></p>'; ?>
<?php endif; ?>