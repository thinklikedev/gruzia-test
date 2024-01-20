<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>

<div class="breadcrumbs_wrapper container">
	<div class="breadcrumbs">
		<ul>
			<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
				<a href="/" itemprop="url"><span itemprop="title">Ресторан Грузия</span></a>
			</li>
			<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><?php the_title(); ?></li>
		</ul>

		<h1><?php the_title(); ?></h1>
	</div>
</div>

<div class="page-login">
	<div class="container">
		<?php woocommerce_output_all_notices(); ?>
		
		<div class="account-buttons">
			<button class="login-button active"><?php esc_html_e( 'Login', 'woocommerce' ); ?></button>
			<button class="registr-button"><?php esc_html_e( 'Register', 'woocommerce' ); ?></button>
		</div>

		<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

			<div class="my-account-form" id="customer_login">

		<?php endif; ?>

				<form class="woocommerce-form form-login active" method="post">
					<h2><?php esc_html_e( 'Login', 'woocommerce' ); ?></h2>

					<?php do_action( 'woocommerce_login_form_start' ); ?>

					<p class="form-row validate-required">
						<input type="text" class="input-text" name="username" id="username" placeholder="<?php esc_html_e( 'Username or email address', 'woocommerce' ); ?>" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required>
					</p>

					<p class="form-row validate-required">
						<input type="text" class="input-text" name="password" id="password" placeholder="<?php esc_html_e( 'Password', 'woocommerce' ); ?>" required>
					</p>

					<?php do_action( 'woocommerce_login_form' ); ?>

					<p class="form-row form-login__buttons">
						<label class="woocommerce-form-login__rememberme">
							<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'Remember me', 'woocommerce' ); ?></span>
						</label>
						<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
						<button type="submit" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>"><?php esc_html_e( 'Log in', 'woocommerce' ); ?></button>
					</p>
					<p class="lost_password">
						<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'woocommerce' ); ?></a>
					</p>

					<?php do_action( 'woocommerce_login_form_end' ); ?>

				</form>

		<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

				<form method="post" class="woocommerce-form form-register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >
					<h2><?php esc_html_e( 'Register', 'woocommerce' ); ?></h2>

					<?php do_action( 'woocommerce_register_form_start' ); ?>

					<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<label for="reg_username"><?php esc_html_e( 'Username', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
							<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
						</p>

					<?php endif; ?>

					<p class="form-row validate-required">
						<input type="text" class="input-text" name="email" id="reg_email" placeholder="<?php esc_html_e( 'Email address', 'woocommerce' ); ?>" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" required>
					</p>

					<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

						<p class="form-row validate-required">
							<input type="text" class="input-text" name="password" id="reg_password" placeholder="<?php esc_html_e( 'Password', 'woocommerce' ); ?>" required autocomplete="new-password">
						</p>

					<?php else : ?>

						<p><?php esc_html_e( 'A link to set a new password will be sent to your email address.', 'woocommerce' ); ?></p>

					<?php endif; ?>

					<?php //do_action( 'woocommerce_register_form' ); ?>

					<p class="woocommerce-form-row form-login__buttons form-row">
						<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
						<button type="submit" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"><?php esc_html_e( 'Register', 'woocommerce' ); ?></button>
					</p>

					<?php do_action( 'woocommerce_register_form_end' ); ?>

				</form>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
