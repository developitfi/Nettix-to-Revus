<?php
defined('ABSPATH') || exit;

function nettix_revus_log($type, $message) {
    $log_file = plugin_dir_path(__FILE__) . '../data/nettix-log.json';
    $log = file_exists($log_file) ? json_decode(file_get_contents($log_file), true) : [];

    $log[] = [
        'time' => current_time('mysql'),
        'type' => $type,
        'message' => $message
    ];

    file_put_contents($log_file, json_encode($log, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    if ($type === 'error' && get_option('nettix_email_notifications')) {
        nettix_revus_notify_admin('Nettix to Revus - virhe', $message);
    }
}

function nettix_revus_notify_admin($subject, $message) {
    $admin_email = get_option('admin_email');
    if (!$admin_email) return;

    wp_mail($admin_email, $subject, $message);
}
