<?php
defined('ABSPATH') || exit;

function nettix_revus_sync() {
    $vehicles = nettix_revus_fetch_vehicles();
    if (!$vehicles) return;

    $existing_posts = get_posts([
        'post_type'   => 'pixad_autos',
        'numberposts' => -1,
        'post_status' => 'any',
        'meta_key'    => '_nettix_external_id'
    ]);

    $existing_ids = [];
    foreach ($existing_posts as $post) {
        $external_id = get_post_meta($post->ID, '_nettix_external_id', true);
        if ($external_id) {
            $existing_ids[$external_id] = $post->ID;
        }
    }

    $imported_ids = [];

    foreach ($vehicles as $vehicle) {
        $external_id = $vehicle['id'] ?? null;
        if (!$external_id) continue;

        $post_id = $existing_ids[$external_id] ?? null;

        if ($post_id) {
            wp_update_post(['ID' => $post_id, 'post_title' => $vehicle['title'] ?? 'Ajoneuvo']);
            nettix_revus_log('info', "Päivitettiin ajoneuvo ID: {$post_id}");
        } else {
            $post_id = wp_insert_post([
                'post_type'   => 'pixad_autos',
                'post_title'  => $vehicle['title'] ?? 'Ajoneuvo',
                'post_status' => 'publish'
            ]);
            nettix_revus_log('info', "Luotiin uusi ajoneuvo ID: {$post_id}");
        }

        update_post_meta($post_id, '_nettix_external_id', $external_id);
        update_post_meta($post_id, '_nettix_raw_data', $vehicle);

        if (function_exists('nettix_revus_handle_all_images')) {
            nettix_revus_handle_all_images($post_id, $vehicle);
        }

        if (function_exists('nettix_revus_handle_images')) {
            nettix_revus_handle_images($post_id, $vehicle);
        }

        if (function_exists('nettix_revus_apply_mappings')) {
            nettix_revus_apply_mappings($post_id, $vehicle);
        }

        $imported_ids[] = $external_id;
    }

    if (function_exists('nettix_revus_cleanup_missing_vehicles')) {
        nettix_revus_cleanup_missing_vehicles(array_keys($existing_ids), $imported_ids);
    }

    file_put_contents(plugin_dir_path(__FILE__) . '../data/last_import.json', json_encode($vehicles, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    nettix_revus_log('info', 'Synkronointi valmis. Tuotu ' . count($imported_ids) . ' ajoneuvoa.');
}

add_action('admin_post_nettix_revus_sync', 'nettix_revus_sync');

add_action('admin_post_nettix_revus_delete_all', function () {
    $posts = get_posts(['post_type' => 'pixad_autos', 'numberposts' => -1]);
    foreach ($posts as $post) {
        wp_delete_post($post->ID, true);
    }
    nettix_revus_log('info', 'Kaikki ajoneuvot poistettu.');
    wp_redirect(admin_url('admin.php?page=nettix_to_revus_status'));
    exit;
});
