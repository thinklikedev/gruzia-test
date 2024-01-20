<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

// This function is used in export / batch delete
if (!function_exists('wcbloat_option_names')) {
   function wcbloat_option_names()
   {
      $options = array(
         // first two are core WooCommerce options
         'woocommerce_merchant_email_notifications',
         'woocommerce_feature_product_block_editor_enabled',
         'wcbloat_admin_disable',
         'wcbloat_admin_disable_features',
         'wcbloat_marketing_disable',
         'wcbloat_wc_helper_disable',
         'wcbloat_wc_marketplace',
         'wcbloat_remove_addon_submenu',
         'wcbloat_hide_payment_providers',
         'wcbloat_hide_woo_mobile_footer_text',
         'wcbloat_wc_status_meta_box_disable',
         'wcbloat_wc_blocks_backend_disable',
         'wcbloat_wc_widgets_disable',
         'wcbloat_wc_scripts_disable',
         'wcbloat_wc_fragmentation_disable',
         'wcbloat_wc_blocks_frontend_disable',
         'wcbloat_wc_stripe_scripts_disable',
         'wcbloat_wp_update_nag_disable',
         'wcbloat_wp_dashboard_widgets_disable',
         'wcbloat_w_logo_disable',
         'wcbloat_wp_footer_disable',
         'wcbloat_hide_wp_logo_on_login_page',
         'wcbloat_wp_logo_url_disable',
         'wcbloat_wp_logo_title',
         'wcbloat_wp_language_select_disable',
         'wcbloat_password_meter_disable',
         'wcbloat_load_comment_scripts_when_needed',
         'wcbloat_prevent_linking_url_comments',
         'wcbloat_disable_dashicons',
         'wcbloat_remove_dns_prefetch',
         'wcbloat_disable_jquery_migrate',
         'wcbloat_wp_sidebar_widgets_disable',
         'wcbloat_wp_meta_generator_disable',
         'wcbloat_remove_emoji_scripts',
         'wcbloat_disable_wp_embed',
         'wcbloat_remove_rss_links',
         'wcbloat_disable_all_feeds',
         'wcbloat_remove_feed_generator_tag',
         'wcbloat_disable_wlw_link',
         'wcbloat_disable_rsd_link',
         'wcbloat_remove_shortlink',
         'wcbloat_themes_auto_update_disable',
         'wcbloat_plugins_auto_update_disable',
         'wcbloat_wp_core_update_disable',
         'wcbloat_file_editor_disable',
         'wcbloat_post_revisions_disable',
         'wcbloat_app_passwords_disable',
         'wcbloat_remove_script_style_ver',
         'wcbloat_xml_rpc_disable',
         'wcbloat_wp_heartbeat_disable',
         'wcbloat_wp_rest_api_disable',
         'wcbloat_uninstall_cleanup',
         'wcbloat_disable_gutenberg',
         'wcbloat_autoclose_welcome_guide',
         'wcbloat_disable_block_directory',
         'wcbloat_disable_default_block_patterns',
         'wcbloat_disable_fullscreen_editor_mode',
         'wcbloat_disable_template_editor',
         'wcbloat_jetpack_installation_disable',
         'wcbloat_jetpack_disable',
         'wcbloat_elementor_widget_disable',
         'wcbloat_wc_skyverge_disable',
         'wcbloat_yoast_premium_disable',
         'wcbloat_yoast_admin_bar_disable',
         'wcbloat_yoast_html_comments_disable',
         'wcbloat_yoast_widget_disable',
         'wcbloat_cf7_disable',
         'wcbloat_updraftplus_menubar_disable',
         'wcbloat_acf_hide_menu',
         'wcbloat_wpml_remove_meta',
         'wcbloat_wpdesk_disable_dashboard_widget',
         'wcbloat_jetpack_blaze_disable',
         'wcbloat_autosave_disable',
         'wcbloat_disable_widget_block_editor',
         'wcbloat_elementor_google_fonts',
         'wcbloat_flexible_shipping_remove_menu'
      );
      return $options;
   }
}