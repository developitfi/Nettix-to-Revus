<?php
defined('ABSPATH') || exit;

add_action('wp_ajax_nettix_ajax_create_backup', function () {
    $filename = nettix_revus_create_backup();
    if ($filename) {
        wp_send_json_success(['filename' => $filename]);
    } else {
        wp_send_json_error('Varmuuskopion luonti epäonnistui.');
    }
});

add_action('wp_ajax_nettix_ajax_delete_backup', function () {
    if (empty($_POST['filename'])) {
        wp_send_json_error('Tiedostonimi puuttuu.');
    }

    $filename = basename(sanitize_text_field($_POST['filename']));
    $path = plugin_dir_path(__FILE__) . '../backups/' . $filename;

    if (file_exists($path)) {
        unlink($path);
        if (function_exists('nettix_revus_log')) {
            nettix_revus_log('info', "Poistettiin varmuuskopio {$filename} AJAX-toiminnolla.");
        }
        wp_send_json_success('Poisto onnistui.');
    } else {
        wp_send_json_error('Tiedostoa ei löytynyt.');
    }
});

add_action('wp_ajax_nettix_ajax_restore_backup', function () {
    if (empty($_POST['filename'])) {
        wp_send_json_error('Tiedostonimi puuttuu.');
    }

    $filename = basename(sanitize_text_field($_POST['filename']));
    $restored = nettix_revus_restore_backup($filename);

    if ($restored) {
        wp_send_json_success('Palautus onnistui.');
    } else {
        wp_send_json_error('Palautus epäonnistui.');
    }
});
