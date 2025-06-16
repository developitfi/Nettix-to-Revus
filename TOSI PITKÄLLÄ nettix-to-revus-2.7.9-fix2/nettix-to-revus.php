<?php
/*
Plugin Name: Nettix to Revus
Description: Synkronoi ajoneuvotiedot Nettix Pro -rajapinnasta Revus-teemaan (pixad_autos).
Version: 2.7.7
Author: developit // it-ystävä
*/

defined('ABSPATH') || exit;

// Ladataan includes-tiedostot
foreach (glob(plugin_dir_path(__FILE__) . 'includes/*.php') as $file) {
    require_once $file;
}

// Ladataan asetussivu (callback-funktio valikkoa varten)
require_once plugin_dir_path(__FILE__) . 'admin/settings-page.php';
require_once plugin_dir_path(__FILE__) . 'admin/menu.php';


if (is_admin()) {
    add_action('admin_menu', function () {
        add_menu_page(
            'Nettix to Revus',
            'Nettix to Revus',
            'manage_options',
            'nettix_to_revus',
            'nettix_revus_render_settings_page',
            'dashicons-car',
            80
        );
    });

    // Ladataan muut admin-sivut
    foreach (glob(plugin_dir_path(__FILE__) . 'admin/*.php') as $file) {
        if (basename($file) !== 'settings-page.php') {
            require_once $file;
        }
    }
}

// Cron-ajastukset
register_activation_hook(__FILE__, function () {
    if (!wp_next_scheduled('nettix_revus_cron_sync')) {
        wp_schedule_event(time(), 'hourly', 'nettix_revus_cron_sync');
    }
});
register_deactivation_hook(__FILE__, function () {
    wp_clear_scheduled_hook('nettix_revus_cron_sync');
});
