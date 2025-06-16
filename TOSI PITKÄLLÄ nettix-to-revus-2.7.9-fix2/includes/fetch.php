<?php
defined('ABSPATH') || exit;

function nettix_revus_fetch_vehicles() {
    $token = nettix_revus_get_token();
    if (!$token) {
        nettix_revus_log('error', 'Tokenin haku epäonnistui, ajoneuvoja ei voitu hakea.');
        return [];
    }

    $response = wp_remote_post(trailingslashit(get_option('nettix_api_url')) . 'listVehicles', [
        'headers' => [
            'Authorization' => 'Bearer ' . $token,
            'Content-Type'  => 'application/json'
        ],
        'body' => json_encode([]),
        'timeout' => 15,
    ]);

    if (is_wp_error($response)) {
        nettix_revus_log('error', 'API-pyyntö epäonnistui: ' . $response->get_error_message());
        return [];
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);
    if (!isset($body['vehicles']) || !is_array($body['vehicles'])) {
        nettix_revus_log('error', 'Virheellinen vastemuoto listVehicles-metodista.');
        return [];
    }

    return $body['vehicles'];
}
