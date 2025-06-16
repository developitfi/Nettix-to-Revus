<?php
defined('ABSPATH') || exit;

function nettix_revus_handle_all_images($post_id, $vehicle) {
    if (empty($vehicle['images']) || !is_array($vehicle['images'])) return;

    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';

    $image_ids = [];

    foreach ($vehicle['images'] as $index => $image_url) {
        $image_id = media_sideload_image($image_url, $post_id, null, 'id');

        if (is_wp_error($image_id)) {
            nettix_revus_log('error', "Kuvan {$index} lataus epäonnistui postille {$post_id}: " . $image_id->get_error_message());
            continue;
        }

        if ($index === 0) {
            set_post_thumbnail($post_id, $image_id);
        }

        $image_ids[] = $image_id;
    }

    // Jos ACF-kenttä käytössä, tallennetaan galleria siihen
    if (function_exists('update_field') && !empty($image_ids)) {
        update_field('nettix_gallery', $image_ids, $post_id);
        nettix_revus_log('info', "Tallennettiin " . count($image_ids) . " kuvaa ACF-galleriaan postille {$post_id}");
    } else {
        update_post_meta($post_id, 'nettix_gallery', $image_ids);
    }
}
