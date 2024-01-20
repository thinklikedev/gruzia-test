<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

/* Uninstall cleanup - delete plugin options from database during plugin deletion
/***********************************************************************/
if (!empty(get_option('wcbloat_uninstall_cleanup')) && (get_option('wcbloat_uninstall_cleanup') == 'yes')) {
    function wcbloat_fs_uninstall_cleanup()
    {
        $options = wcbloat_option_names();
        foreach ($options as $option) {
            delete_option($option);
        }
    }
    wcbloat_fs()->add_action('after_uninstall', 'wcbloat_fs_uninstall_cleanup');
    register_uninstall_hook(__FILE__, 'wcbloat_fs_uninstall_cleanup');
}
