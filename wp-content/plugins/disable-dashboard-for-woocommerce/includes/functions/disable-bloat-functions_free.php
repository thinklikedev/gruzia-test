<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

// Check if WooCommmerce is active and execute following code only if Woo is active
if (wcbloat_is_woo_active()) {

	/* Disable WooCommerce Admin completely: Disable WooCommerce Admin, Analytics tab, Notification bar
/***********************************************************************/
	if (!empty(get_option('wcbloat_admin_disable', 'yes')) && (get_option('wcbloat_admin_disable', 'yes') === 'yes')) {
		add_filter('woocommerce_admin_disabled', '__return_true');

		// fix for WooCommerce Status Meta Box not showing
		if (!empty(get_option('woocommerce_task_list_hidden', 'yes'))) {
			update_option('woocommerce_task_list_hidden', 'yes');
		}
		if (!empty(get_option('woocommerce_task_list_complete', 'yes'))) {
			update_option('woocommerce_task_list_complete', 'yes');
		}
		// end fix

		function wcbloat_remove_reports_text()
		{
?>
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					if (window.location.search.includes('wc-reports')) {
						$("strong:contains('WooCommerce 4.0')")
							.parents('#message').remove();
					}
				});
			</script>
		<?php
		}
		add_action('admin_head', 'wcbloat_remove_reports_text');

		add_filter('woocommerce_admin_features', function (array $features): array {
			$features = [];
			return $features;
		}, 90);
		add_action('admin_enqueue_scripts', function () {
			wp_dequeue_style('wc-admin-app');
			wp_deregister_style('wc-admin-app');
		?>
			<style>
				.woocommerce-layout__header {
					display: none;
				}
			</style>
		<?php
		}, 19);

		function wcbloat_remove_header()
		{
		?>
			<style>
				.woocommerce-layout__header {
					display: none;
				}
			</style>
		<?php
		}
		add_action('admin_head', 'wcbloat_remove_header');

		// Display incompatibility notice on Stripe configuration pages, but first check if offical Stripe plugin is active
		function wcbloat_stripe_incompatibility_admin_notice()
		{
			// Makes sure the plugin is defined before trying to use it
			$need = false;

			if (!function_exists('is_plugin_active_for_network')) {
				require_once(ABSPATH . '/wp-admin/includes/plugin.php');
			}

			// multisite && this plugin is locally activated - Stripe can be network or locally activated 
			if (is_multisite() && is_plugin_active_for_network(plugin_basename(__FILE__))) {
				// this plugin is network activated - Stripe must be network activated 
				$need = is_plugin_active_for_network('woocommerce-gateway-stripe/woocommerce-gateway-stripe.php') ? false : true;
				// this plugin runs on a single site || is locally activated 
			} else {
				$need =  is_plugin_active('woocommerce-gateway-stripe/woocommerce-gateway-stripe.php') ? false : true;
			}

			if ($need === false) {
				if (function_exists('get_current_screen')) {
					$screen = get_current_screen();

					if ($screen->id === 'woocommerce_page_wc-settings' && isset($_GET['section']) && strpos($_GET['section'], 'stripe') !== false && isset($_GET['tab']) && $_GET['tab'] === 'checkout') {
						/* translators: link to plugin configuration screen */
						$notice_text = sprintf(__('The WooCommerce Admin module is turned off. The Stripe plugin needs the WooCommerce Admin module to be on when setting up API keys. To do this, please temporarily enable the WooCommerce Admin in the <a href="%s">Disable Bloat plugin settings</a>. You can switch it back off after configuring Stripe API keys.', 'disable-dashboard-for-woocommerce'), esc_url(admin_url('options-general.php?page=disable-bloat')));

						echo '<div class="notice notice-warning is-dismissible"><p>' . $notice_text . '</p></div>';
					}
				}
			}
		}

		add_action('admin_notices', 'wcbloat_stripe_incompatibility_admin_notice');

		// Stripe notice END


		// Fix for Select2 issue introduced after WooCommerce 8.1 release: https://github.com/woocommerce/woocommerce/issues/40151

		function wcbloat_select2_fix_woo81()
		{
			echo '<style>
			  :root {
			--wp-admin-theme-color: #007cba;
		  }
			</style>';
		}
		add_action('admin_head', 'wcbloat_select2_fix_woo81');
	}

	// Choose which WooCommerce Admin features to disable

	if (!empty(get_option('wcbloat_admin_disable')) && (get_option('wcbloat_admin_disable') == 'disable-wc-admin-features')) {

		$disabled_features = get_option('wcbloat_admin_disable_features');
		if (!empty($disabled_features)) {

			add_filter('woocommerce_admin_get_feature_config', function ($features) use ($disabled_features) {

				$disabled_features_keys = array_flip($disabled_features);

				foreach ($features as $key => $value) {
					if (isset($disabled_features_keys[$key])) {
						$features[$key] = false;
					}
				}

				return $features;
			});
		}
	}


	/* Marketing Hub
/***********************************************************************/

	if (!empty(get_option('wcbloat_marketing_disable', 'yes')) && (get_option('wcbloat_marketing_disable', 'yes') === 'yes')) {
		add_filter('woocommerce_marketing_menu_items', '__return_empty_array');
		add_filter('woocommerce_admin_features', 'wcbloat_disable_features');

		function wcbloat_disable_features($features)
		{
			$marketing = array_search('marketing', $features);
			unset($features[$marketing]);
			return $features;
		}
	}

	/* Connect your store to WooCommerce.com to receive extensions updates and support. message for WooCommerce.com plugins
/***********************************************************************/

	if (!empty(get_option('wcbloat_wc_helper_disable', 'yes')) && (get_option('wcbloat_wc_helper_disable', 'yes') === 'yes')) {
		add_filter('woocommerce_helper_suppress_admin_notices', '__return_true');
	}

	/* Disable WooCommerce Scripts
/***********************************************************************/
	if (!empty(get_option('wcbloat_wc_scripts_disable')) && (get_option('wcbloat_wc_scripts_disable') === 'yes')) {
		add_action('wp_enqueue_scripts', 'wcbloat_disable_woocommerce_scripts', 99);
	}

	function wcbloat_disable_woocommerce_scripts()
	{
		if (function_exists('is_woocommerce')) {
			if (!is_woocommerce() && !is_cart() && !is_checkout() && !is_account_page() && !is_product() && !is_product_category() && !is_shop()) {

				//Dequeue WooCommerce Styles
				wp_dequeue_style('woocommerce-general');
				wp_dequeue_style('woocommerce-layout');
				wp_dequeue_style('woocommerce-smallscreen');
				wp_dequeue_style('woocommerce_frontend_styles');
				wp_dequeue_style('woocommerce_fancybox_styles');
				wp_dequeue_style('woocommerce_chosen_styles');
				wp_dequeue_style('woocommerce_prettyPhoto_css');
				//Dequeue WooCommerce Scripts
				wp_dequeue_script('wc_price_slider');
				wp_dequeue_script('wc-single-product');
				wp_dequeue_script('wc-add-to-cart');
				wp_dequeue_script('wc-checkout');
				wp_dequeue_script('wc-add-to-cart-variation');
				wp_dequeue_script('wc-single-product');
				wp_dequeue_script('wc-cart');
				wp_dequeue_script('wc-chosen');
				wp_dequeue_script('woocommerce');
				wp_dequeue_script('prettyPhoto');
				wp_dequeue_script('prettyPhoto-init');
				wp_dequeue_script('jquery-blockui');
				wp_dequeue_script('jquery-placeholder');
				wp_dequeue_script('fancybox');
				wp_dequeue_script('jqueryui');

				if (!empty(get_option('wcbloat_wc_fragmentation_disable')) && (get_option('wcbloat_wc_fragmentation_disable') === 'yes')) {
					wp_dequeue_script('wc-cart-fragments');
				}
			}
		}
	}

	/* Disable WooCommerce Cart Fragments
/***********************************************************************/
	if (!empty(get_option('wcbloat_wc_fragmentation_disable')) && (get_option('wcbloat_wc_fragmentation_disable') === 'yes')) {
		add_action('wp_enqueue_scripts', 'wcbloat_disable_woocommerce_cart_fragments', 99);
	}

	function wcbloat_disable_woocommerce_cart_fragments()
	{
		if (function_exists('is_woocommerce')) {
			wp_dequeue_script('wc-cart-fragments');
		}
	}

	/* Disable WooCommerce Status Meta Box
/***********************************************************************/
	if (!empty(get_option('wcbloat_wc_status_meta_box_disable')) && (get_option('wcbloat_wc_status_meta_box_disable') === 'yes')) {
		add_action('wp_dashboard_setup', 'wcbloat_disable_woocommerce_status');
	}

	function wcbloat_disable_woocommerce_status()
	{
		remove_meta_box('woocommerce_dashboard_status', 'dashboard', 'normal');
	}

	/* Disable WooCommerce Dashboard Setup Widget
/***********************************************************************/
	if (!empty(get_option('disable_admin_dashboard_setup_widget')) && (get_option('disable_admin_dashboard_setup_widget') === 'yes')) {
		function disable_admin_dashboard_setup_widget()
		{
			remove_meta_box('wc_admin_dashboard_setup', 'dashboard', 'normal');
		}
		add_action('wp_dashboard_setup', 'disable_admin_dashboard_setup_widget', 40);
	}

	/* Disable WooCommerce Marketplace Suggestions
/***********************************************************************/
	if (!empty(get_option('wcbloat_wc_marketplace')) && (get_option('wcbloat_wc_marketplace') === 'yes')) {
		add_filter('woocommerce_allow_marketplace_suggestions', '__return_false', 999);
	}

	/* Disable Extensions submenu
/***********************************************************************/
	if (!empty(get_option('wcbloat_remove_addon_submenu')) && (get_option('wcbloat_remove_addon_submenu') === 'yes')) {
		add_action('admin_menu', 'wcbloat_remove_admin_addon_submenu', 999);
		function wcbloat_remove_admin_addon_submenu()
		{
			remove_submenu_page('woocommerce', 'wc-addons');
			remove_submenu_page('woocommerce', 'wc-addons&section=helper');
		}
	}

	// Hide Discover other payment providers link on the WooCommerce Settings Payments screen

	if (!empty(get_option('wcbloat_hide_payment_providers')) && (get_option('wcbloat_hide_payment_providers') === 'yes')) {
		function wcbloat_hide_payment_providers()
		{
		?>
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					if (window.location.search.includes('wc-settings&tab=checkout')) {
						const wcBloatDiscoverPaymentsBloat = document.querySelector('#settings-other-payment-methods').parentElement.parentElement;
						wcBloatDiscoverPaymentsBloat.style.display = 'none';
					}
				});
			</script>
<?php
		}
		add_action('admin_head', 'wcbloat_hide_payment_providers');
	}


	/* Disable WooCommerce Widgets
/***********************************************************************/
	if (!empty(get_option('wcbloat_wc_widgets_disable')) && (get_option('wcbloat_wc_widgets_disable') === 'yes')) {
		add_action('widgets_init', 'wcbloat_disable_woocommerce_widgets', 99);
	}
	function wcbloat_disable_woocommerce_widgets()
	{

		unregister_widget('WC_Widget_Products');
		unregister_widget('WC_Widget_Product_Categories');
		unregister_widget('WC_Widget_Product_Tag_Cloud');
		unregister_widget('WC_Widget_Cart');
		unregister_widget('WC_Widget_Layered_Nav');
		unregister_widget('WC_Widget_Layered_Nav_Filters');
		unregister_widget('WC_Widget_Price_Filter');
		unregister_widget('WC_Widget_Product_Search');
		unregister_widget('WC_Widget_Recently_Viewed');
		unregister_widget('WC_Widget_Recent_Reviews');
		unregister_widget('WC_Widget_Top_Rated_Products');
		unregister_widget('WC_Widget_Rating_Filter');
	}
	// execute only if Woo is active END 
}

// Hide update notice for non-admin users

if (!empty(get_option('wcbloat_wp_update_nag_disable')) && (get_option('wcbloat_wp_update_nag_disable') === 'yes')) {

	function wcbloat_hide_wp_update_notice()
	{
		if (!current_user_can('update_core')) {
			remove_action('admin_notices', 'update_nag', 3);
		}
	}
	add_action('admin_head', 'wcbloat_hide_wp_update_notice', 1);
}

// Disable Autosave

if (!empty(get_option('wcbloat_autosave_disable')) && (get_option('wcbloat_autosave_disable') === 'yes')) {

	add_action('admin_init', 'wcbloat_autosave_disable_init');
	function wcbloat_autosave_disable_init()
	{
		wp_deregister_script('autosave');
	}
}


/* Disable Password Strength Meter
/***********************************************************************/
if (!empty(get_option('wcbloat_password_meter_disable')) && (get_option('wcbloat_password_meter_disable') === 'yes')) {
	add_action('wp_print_scripts', 'wcbloat_disable_password_strength_meter', 100);
}

function wcbloat_disable_password_strength_meter()
{
	global $wp;

	$wp_check = isset($wp->query_vars['lost-password']) || (isset($_GET['action']) && $_GET['action'] === 'lostpassword') || is_page('lost_password');

	$wc_check = (class_exists('WooCommerce') && (is_account_page() || is_checkout()));

	if (!$wp_check && !$wc_check) {
		if (wp_script_is('zxcvbn-async', 'enqueued')) {
			wp_dequeue_script('zxcvbn-async');
		}

		if (wp_script_is('password-strength-meter', 'enqueued')) {
			wp_dequeue_script('password-strength-meter');
		}

		if (wp_script_is('wc-password-strength-meter', 'enqueued')) {
			wp_dequeue_script('wc-password-strength-meter');
		}
	}
}

/**
 * Load comment script only when needed
 */

if (!empty(get_option('wcbloat_load_comment_scripts_when_needed')) && (get_option('wcbloat_load_comment_scripts_when_needed') === 'yes')) {

	add_filter('show_recent_comments_widget_style', '__return_false');
}

/**
 * Prevent auto-linking URLs in comments
 */

if (!empty(get_option('wcbloat_prevent_linking_url_comments')) && (get_option('wcbloat_prevent_linking_url_comments') === 'yes')) {

	remove_filter('comment_text', 'make_clickable', 9);
}

/**
 * Disable Dashicons
 */

if (!empty(get_option('wcbloat_disable_dashicons')) && (get_option('wcbloat_disable_dashicons') === 'yes')) {
	function wcbloat_disable_dashicons()
	{


		// Fix for Dokan plugin - user with role Dokan-seller (vendor) will still load dashicons script. Required by Dokan Vendor Dashboard

		if (function_exists('dokan_is_user_seller')) {
			if (dokan_is_user_seller(get_current_user_id())) {
				return;
			}
		}

		// End fix for Dokan plugin

		// Fix for Oxgen builder plugin

		if (isset($_GET['ct_builder']) && $_GET['ct_builder'] === 'true') {
			return;
		}

		// End fix for Oxygen

		if (!is_admin_bar_showing() && !is_customize_preview()) {
			wp_dequeue_style('dashicons');
			wp_deregister_style('dashicons');
		}
	}
	add_action('wp_print_styles', 'wcbloat_disable_dashicons', 100);
}


/* Disable Jetpack promotions
/***********************************************************************/
if (!empty(get_option('wcbloat_jetpack_disable')) && (get_option('wcbloat_jetpack_disable') === 'yes')) {
	add_filter('jetpack_just_in_time_msgs', '__return_false', 20);
	add_filter('jetpack_show_promotions', '__return_false', 20);
}

// Disable Jetpack Blaze feature

if (!empty(get_option('wcbloat_jetpack_blaze_disable')) && (get_option('wcbloat_jetpack_blaze_disable') === 'yes')) {
	add_filter('jetpack_blaze_enabled', '__return_false');
}

/* Disable SkyVerge Dashboard
/***********************************************************************/
if (!empty(get_option('wcbloat_wc_skyverge_disable')) && (get_option('wcbloat_wc_skyverge_disable') === 'yes')) {
	// remove SkyVerge support dashboard
	add_action('admin_menu', function () {
		remove_menu_page('skyverge');
	}, 99);

	// remove dashboard stylesheet
	add_action('admin_enqueue_scripts', function () {
		wp_dequeue_style('sv-wordpress-plugin-admin-menus');
	}, 20);
}

/* Disable Elementor Dashboard widget
/***********************************************************************/
if (!empty(get_option('wcbloat_elementor_widget_disable')) && (get_option('wcbloat_elementor_widget_disable') === 'yes')) {
	function disable_elementor_dashboard_overview_widget()
	{
		remove_meta_box('e-dashboard-overview', 'dashboard', 'normal');
	}
	add_action('wp_dashboard_setup', 'disable_elementor_dashboard_overview_widget', 40);
}

/**
 * Disable Gutenberg
 */

if (!empty(get_option('wcbloat_disable_gutenberg')) && (get_option('wcbloat_disable_gutenberg') === 'yes')) {

	add_filter('use_block_editor_for_post_type', '__return_false', 100);

	function wcbloat_disable_gutenberg_hooks()
	{
		remove_action('admin_menu', 'gutenberg_menu');
		remove_action('admin_init', 'gutenberg_redirect_demo');

		remove_filter('wp_refresh_nonces', 'gutenberg_add_rest_nonce_to_heartbeat_response_headers');
		remove_filter('get_edit_post_link', 'gutenberg_revisions_link_to_editor');
		remove_filter('wp_prepare_revision_for_js', 'gutenberg_revisions_restore');

		remove_action('rest_api_init', 'gutenberg_register_rest_routes');
		remove_action('rest_api_init', 'gutenberg_add_taxonomy_visibility_field');
		remove_filter('rest_request_after_callbacks', 'gutenberg_filter_oembed_result');
		remove_filter('registered_post_type', 'gutenberg_register_post_prepare_functions');

		remove_action('do_meta_boxes', 'gutenberg_meta_box_save', 1000);
		remove_action('submitpost_box', 'gutenberg_intercept_meta_box_render');
		remove_action('submitpage_box', 'gutenberg_intercept_meta_box_render');
		remove_action('edit_page_form', 'gutenberg_intercept_meta_box_render');
		remove_action('edit_form_advanced', 'gutenberg_intercept_meta_box_render');
		remove_filter('redirect_post_location', 'gutenberg_meta_box_save_redirect');
		remove_filter('filter_gutenberg_meta_boxes', 'gutenberg_filter_meta_boxes');

		remove_action('admin_notices', 'gutenberg_build_files_notice');
		remove_filter('body_class', 'gutenberg_add_responsive_body_class');
		remove_filter('admin_url', 'gutenberg_modify_add_new_button_url'); // old
		remove_action('admin_enqueue_scripts', 'gutenberg_check_if_classic_needs_warning_about_blocks');
		remove_filter('register_post_type_args', 'gutenberg_filter_post_type_labels');

		remove_action('admin_init', 'gutenberg_add_edit_link_filters');
		remove_action('admin_print_scripts-edit.php', 'gutenberg_replace_default_add_new_button');
		remove_filter('redirect_post_location', 'gutenberg_redirect_to_classic_editor_when_saving_posts');
		remove_filter('display_post_states', 'gutenberg_add_gutenberg_post_state');
		remove_action('edit_form_top', 'gutenberg_remember_classic_editor_when_saving_posts');
	}

	add_filter('after_setup_theme', 'wcbloat_disable_gutenberg_hooks');
	function wcbloat_disable_gutenberg_wp_enqueue_scripts()
	{
		wp_dequeue_style('wp-block-library');
	}

	add_filter('wp_enqueue_scripts', 'wcbloat_disable_gutenberg_wp_enqueue_scripts', 100);
}

// Disable Gutenberg Widget Block Editor

if (!empty(get_option('wcbloat_disable_widget_block_editor')) && (get_option('wcbloat_disable_widget_block_editor') === 'yes')) {
	add_filter('gutenberg_use_widgets_block_editor', '__return_false', 100);
	add_filter('use_widgets_block_editor', '__return_false');
	if (!function_exists('wcbloat_disable_widget_block_editor_theme')) :
		function wcbloat_disable_widget_block_editor_theme()
		{
			remove_theme_support('widgets-block-editor');
		}
	endif;
	add_action('after_setup_theme', 'wcbloat_disable_widget_block_editor_theme');
}

/**
 * Deactivate the Template Editor
 */

if (!empty(get_option('wcbloat_disable_template_editor')) && (get_option('wcbloat_disable_template_editor') === 'yes')) {

	remove_theme_support('block-templates');
}

// Remove the "Grow your business with WP Desk" widget

if (!empty(get_option('wcbloat_wpdesk_disable_dashboard_widget')) && (get_option('wcbloat_wpdesk_disable_dashboard_widget') === 'yes')) {

	function wcbloat_disable_wpdesk_dashboard_widgets()
	{
		if (function_exists('get_current_screen')) {

			$screen = get_current_screen();
			if (!$screen) {
				return;
			}

			remove_meta_box('flexible-checkout-fields', 'dashboard', 'normal');
			remove_meta_box('flexible-invoices', 'dashboard', 'normal');
			remove_meta_box('wpdesk_ltv_dashboard_widget', 'dashboard', 'normal');
			remove_meta_box('flexible-product-fields', 'dashboard', 'normal');
		}
	}

	add_action('wp_dashboard_setup', 'wcbloat_disable_wpdesk_dashboard_widgets', 20);
};


// Remove the "Shipping Extensions" WooCommerce menu entry

if (!empty(get_option('wcbloat_flexible_shipping_remove_menu')) && (get_option('wcbloat_flexible_shipping_remove_menu') === 'yes')) {

	function wcbloat_disable_flexible_shipping_extensions_submenu()
	{
		remove_submenu_page('woocommerce', 'octolize-shipping-extensions');
	}
	add_action('admin_menu', 'wcbloat_disable_flexible_shipping_extensions_submenu', 999);
}

// Prevent Elementor from loading Google fonts in the frontend

if (!empty(get_option('wcbloat_elementor_google_fonts')) && (get_option('wcbloat_elementor_google_fonts') === 'yes')) {

	add_filter('elementor/frontend/print_google_fonts', '__return_false');
}
