<?php
defined('ABSPATH') || exit;

function nettix_revus_get_token() {
    $token_url = get_option('nettix_token_url');
    $client_id = get_option('nettix_client_id');
    $client_secret = get_option('nettix_client_secret');

    if (!$token_url || !$client_id || !$client_secret) {
        nettix_revus_log('error', 'Tokenin haku: puuttuvat asetukset.');
        return false;
    }

    $response = wp_remote_post($token_url, [
        'body' => [
            'grant_type'    => 'client_credentials',
            'client_id'     => $client_id,
            'client_secret' => $client_secret
        ]
    ]);

    if (is_wp_error($response)) {
        nettix_revus_log('error', 'Tokenin haku epäonnistui: ' . $response->get_error_message());
        return false;
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);
    return $body['access_token'] ?? false;
}
