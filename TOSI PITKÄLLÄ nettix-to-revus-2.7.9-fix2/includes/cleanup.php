<?php
defined('ABSPATH') || exit;

function nettix_revus_cleanup_missing_vehicles(array $existing_ids, array $fetched_ids) {
    if (!get_option('nettix_auto_remove_missing')) return;

    $to_delete = array_diff($existing_ids, $fetched_ids);

    foreach ($to_delete as $external_id) {
        $posts = get_posts([
            'post_type'  => 'pixad_autos',
            'meta_key'   => '_nettix_external_id',
            'meta_value' => $external_id,
            'numberposts'=> -1,
            'post_status'=> 'any'
        ]);

        foreach ($posts as $post) {
            wp_delete_post($post->ID, true);
            nettix_revus_log('info', "Poistettiin ajoneuvo ID: {$post->ID} (external_id: {$external_id})");
        }
    }
}
