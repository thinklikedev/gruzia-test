<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

// Add sections and fields on Main settings screen
function wcbloat_plugin_data_settings_init()
{

	// Section's icon
	add_settings_section(
		'wcbloat-data-icon-section',
		'',
		'wcbloat_data_icon_callback',
		'wcbloat-data'
	);

	// Section's title and description
	add_settings_section(
		'wcbloat-data-desc-section',
		esc_attr__('Plugin data', 'disable-dashboard-for-woocommerce'),
		'wcbloat_data_desc_callback',
		'wcbloat-data'
	);

	// Cleanup during uninstall section
	add_settings_section(
		'wcbloat-data-cleanup-desc-section',
		esc_attr__('Plugin data cleanup', 'disable-dashboard-for-woocommerce'),
		'wcbloat_cleanup_desc_callback',
		'wcbloat-data'
	);

	// Uninstall setting
	add_settings_field(
		'wcbloat_uninstall_cleanup',
		esc_attr__('Plugin data cleanup during uninstallation', 'disable-dashboard-for-woocommerce'),
		'wcbloat_uninstall_cleanup_callback',
		'wcbloat-data',
		'wcbloat-data-cleanup-desc-section'
	);
	register_setting(
		'wcbloat-data-options',
		'wcbloat_uninstall_cleanup',
		array('sanitize_callback' => 'sanitize_key')
	);

	// Import export section
	add_settings_section(
		'wcbloat-data-import-export-desc-section',
		esc_attr__('Export settings to file', 'disable-dashboard-for-woocommerce'),
		'wcbloat_import_export_desc_callback',
		'wcbloat-data'
	);
}


// Display the fields added before
add_action('admin_init', 'wcbloat_plugin_data_settings_init');

// Fields callbacks

// Section's icon
function wcbloat_data_icon_callback()
{
	echo '<span class="dashicons dashicons-database"></span>';
}

// First section with title description
function wcbloat_data_desc_callback()
{
	echo __('Here you can manage your plugin settings. Import, export, or reset settings. Use it for backup or testing purposes.', 'disable-dashboard-for-woocommerce') . '<hr />';
}

// Cleanup section description
function wcbloat_cleanup_desc_callback()
{
	echo __('If you decide to uninstall the plugin using the standard WordPress <i>Delete plugin</i> option, you can automatically erase the plugin settings from the database.', 'disable-dashboard-for-woocommerce');
}


// Plugin data cleanup during uninstallation
function wcbloat_uninstall_cleanup_callback()
{
	$value = get_option('wcbloat_uninstall_cleanup');
?>
	<input type='hidden' name='wcbloat_uninstall_cleanup' value='no'>
	<label><input type='checkbox' name='wcbloat_uninstall_cleanup' <?php checked(esc_attr($value), 'yes'); ?> value='yes'> <?php esc_attr_e('Clean plugin settings while deleting the plugin', 'disable-dashboard-for-woocommerce'); ?></label>
	<p><?php _e('Clear plugin data from the database during the uninstallation.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
	submit_button();
}

// Import / export section
function wcbloat_import_export_desc_callback()
{
	echo __('With the Export feature, you can securely save your settings as a downloadable JSON file. Click the Export button, and your personalized settings will be ready for backup or future use. You can use the config file in other plugin installations.', 'disable-dashboard-for-woocommerce');

?> </form>
	<div class="metabox-holder import-export">
		<div class="postbox">
			<h3><span><?php _e('Export Settings'); ?></span></h3>
			<div class="inside">
				<p><?php _e('Export the plugin settings for this site as a JSON file. This allows you to easily import the configuration into another site.'); ?></p>
				<form method="post">
					<p><input type="hidden" name="wcbloat_export_action" value="export_settings" /></p>
					<p>
						<?php wp_nonce_field('wcbloat_export_nonce', 'wcbloat_export_nonce'); ?>
						<?php submit_button(__('Export and download plugin configuration', 'disable-dashboard-for-woocommerce'), 'secondary', 'submit', false); ?>
					</p>
				</form>
			</div>
		</div>
		<?php
		$bytes = apply_filters('import_upload_size_limit', wp_max_upload_size());

		// Handle File Upload
		if (isset($_FILES['import'])) {
			$file_extension = pathinfo($_FILES['import']['name'], PATHINFO_EXTENSION);
			$file_size = $_FILES['import']['size'];

			// Validate File Extension
			if ($file_extension === 'json' && $file_size < wp_max_upload_size()) {
				// Sanitize File Name
				$file_name = sanitize_file_name($_FILES['import']['name']);

				// Temporary File Path
				$temp_file_path = $_FILES['import']['tmp_name'];

				// Validate and Process File
				if (is_uploaded_file($temp_file_path)) {
					// Read File Contents
					$file_contents = file_get_contents($temp_file_path);

					// Validate JSON Format
					$options = json_decode($file_contents, true);
					if ($options !== null) {
						$updated_options_count = 0; // Initialize the count

						$allowed_option_names = wcbloat_option_names();

						// Process and Sanitize Options
						foreach ($options as $option_name => $option_value) {
							if (in_array($option_name, $allowed_option_names)) {
								// Sanitize Option Name and Value
								$option_name = sanitize_key($option_name);
								// Sanitize further based on the type of data (e.g., esc_attr, esc_html, etc.)
								$option_value = esc_sql($option_value);

								// Update Option
								update_option($option_name, $option_value);
								$updated_options_count++; // Increment the count
							}
						}

						// Display Success Notice
						if ($updated_options_count > 0) {
							/* translators: number of imported options */
							echo "<div class='updated'><p>" . sprintf(__('The import was successful. %d options were imported.', 'disable-dashboard-for-woocommerce'), $updated_options_count) . "</p></div>";
						} else {
							echo "<div class='error'><p>" . __('No valid options found for update. Please check your JSON file and make sure it was correctly exported.', 'disable-dashboard-for-woocommerce') . "</p></div>";
						}
					} else {
						echo "<div class='error'><p>" . __('Invalid JSON file format. Please make sure that you are uploading a right file.', 'disable-dashboard-for-woocommerce') . "</p></div>";
					}
				} else {
					echo "<div class='error'><p>" . __('There is a problem with reading your file. Please try again.', 'disable-dashboard-for-woocommerce') . "</p></div>";
					if (defined('WP_DEBUG') && WP_DEBUG) {
						echo '<pre>';
						print_r($_FILES);
						echo '</pre>';
					}
				}
			} else {
				echo "<div class='error'><p>" . __('Invalid file format or size.', 'disable-dashboard-for-woocommerce') . "</p></div>";
			}
		}



		?>
	</div>
	<?php
	$size = size_format($bytes);
	$upload_dir = wp_upload_dir();
	if (!empty($upload_dir['error'])) :
	?><div class="error">
			<p><?php _e('Before you can upload your import file, you will need to fix the following error:', 'disable-dashboard-for-woocommerce'); ?></p>
			<p><strong><?php echo $upload_dir['error']; ?></strong></p>
		</div><?php
			else :
				?>
		<?php echo '<h2>' . __('Import configuration file', 'disable-dashboard-for-woocommerce'); ?></h2>
		<?php echo __('Import the configuration file in JSON format. All settings from the file will be saved in the database.', 'disable-dashboard-for-woocommerce'); ?>
		<div class="metabox-holder import-export">
			<div class="postbox">
				<h3><span><?php _e('Import Settings', 'disable-dashboard-for-woocommerce'); ?></span></h3>
				<div class="inside">
					<p><?php _e('Import feature: browse, select your JSON file, and activate the import process by clicking a button below. Existing data will be overwritten.', 'disable-dashboard-for-woocommerce'); ?></p>
					<form enctype="multipart/form-data" id="import-upload-form" method="post" class="wp-upload-form" action="<?php echo esc_url(wp_nonce_url('options-general.php?page=disable-bloat&tab=data', 'wcbloat-import-nonce')); ?>">
						<p>
							<label for="upload"><?php _e('Choose a JSON file from your computer:', 'disable-dashboard-for-woocommerce'); ?></label>
							<!-- 
							<?php
							/* translators: maximum upload size */
							printf(__('Maximum size: %s', 'disable-dashboard-for-woocommerce'), $size); ?>) -->
							<input type="file" id="upload" name="import" accept=".json" />
							<input type="hidden" name="action" value="save" />
							<input type="hidden" name="max_file_size" value="<?php echo $bytes; ?>" />
							<?php wp_nonce_field('wcbloat-import-nonce'); ?>
						</p>
						<?php submit_button(__('Upload file and import', 'disable-dashboard-for-woocommerce'), 'button', 'wcbloat-upload-json-file'); ?>
					</form>
					</p>
					</form>
				</div>
			</div>
	<?php
			endif;
		}

		function wcbloat_settings_export()
		{

			if (empty($_POST['wcbloat_export_action']) || 'export_settings' != $_POST['wcbloat_export_action'])
				return;

			if (!wp_verify_nonce($_POST['wcbloat_export_nonce'], 'wcbloat_export_nonce'))
				wp_die(__('Invalid nonce. Please try again.', 'disable-dashboard-for-woocommerce'), 'Error', array('response' => 403));

			if (!current_user_can('manage_options'))
				wp_die(__('Permission denied. Please contact your site administrator to run the export process.', 'disable-dashboard-for-woocommerce'), 'Error', array('response' => 403));

			$options = wcbloat_option_names();
			foreach (array('wcbloat_marketing_disable', 'wcbloat_admin_disable', 'wcbloat_wc_helper_disable') as $option_name) {
				if (!get_option($option_name)) {
					$index = array_search($option_name, $options);
					if ($index !== false) {
						unset($options[$index]);
					}
				}
			}


			$options_values = array();
			$all_options_empty = true;

			foreach ($options as $option_name) {
				$option_value = get_option($option_name);
				$options_values[$option_name] = $option_value;
				$all_options_empty = false;
			}

			if ($all_options_empty) {
				wp_die(__('No options to export. You are probably using a fresh install of Disable Bloat plugin. Please save the plugin settings to write the configuration to the database', 'disable-dashboard-for-woocommerce'), 'Error', array('response' => 400));
			}

			ignore_user_abort(true);

			nocache_headers();
			header('Content-Type: application/json; charset=utf-8');
			header('Content-Disposition: attachment; filename=disable-bloat-settings-export-' . date('d-m-Y') . '.json');
			header("Expires: 0");

			echo json_encode($options_values);
			exit;
		}
		add_action('admin_init', 'wcbloat_settings_export');
