<?php
defined('ABSPATH') || exit;

add_action('add_meta_boxes', function() {
    if (!get_option('nettix_enable_json_preview')) return;

    add_meta_box('nettix_raw_data_box', 'Nettix Raakadata (JSON)', function($post) {
        $raw = get_post_meta($post->ID, '_nettix_raw_data', true);
        if (!$raw) {
            echo '<p>Ei dataa saatavilla.</p>';
            return;
        }
        echo '<pre style="max-height:500px; overflow:auto; background:#f5f5f5; padding:1em;">';
        echo esc_html(json_encode($raw, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo '</pre>';
    }, 'pixad_autos', 'normal', 'default');
});
