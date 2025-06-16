<?php
defined('ABSPATH') || exit;

/**
 * Palauttaa listan saatavilla olevista Nettix-kentistä viimeisimmästä tuonnista
 * @return array
 */
function nettix_revus_get_available_fields() {
    $path = plugin_dir_path(__FILE__) . '../data/last_import.json';

    if (!file_exists($path)) return [];

    $data = json_decode(file_get_contents($path), true);
    if (!is_array($data) || empty($data)) return [];

    // Otetaan ensimmäinen ajoneuvo esimerkiksi
    $first = reset($data);
    return is_array($first) ? array_keys($first) : [];
}
