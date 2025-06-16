<?php
defined('ABSPATH') || exit;

/**
 * Luo varmuuskopiotiedosto kaikista ajoneuvopostauksista, riippumatta post_type:stä
 */
function nettix_revus_create_backup() {
    global $wpdb;

    $raw_posts = $wpdb->get_results("
        SELECT ID, post_type, post_title FROM {$wpdb->posts}
        WHERE post_status IN ('publish','draft','pending','private')
        AND post_type NOT IN ('revision','nav_menu_item','acf-field','acf-field-group')
        ORDER BY post_date DESC
    ", ARRAY_A);

    $data = [];

    foreach ($raw_posts as $p) {
        $post_id = $p['ID'];
        $post_data = get_post($post_id);

        $meta = get_post_meta($post_id);
        $has_meta = isset($meta['_car_price']) || isset($meta['_car_year']) || isset($meta['_car_mileage']);
        $taxonomies = get_object_taxonomies($post_data->post_type);
        $has_tax = false;

        foreach ($taxonomies as $taxonomy) {
            $terms = wp_get_object_terms($post_id, $taxonomy, ['fields' => 'ids']);
            if (!is_wp_error($terms) && !empty($terms)) {
                $has_tax = true;
                break;
            }
        }

        // Otetaan mukaan vain, jos on taksonomioita tai tunnistettavia metakenttiä
        if (!$has_meta && !$has_tax) continue;

        $tax_data = [];
        foreach ($taxonomies as $taxonomy) {
            $terms = wp_get_object_terms($post_id, $taxonomy, ['fields' => 'slugs']);
            if (!is_wp_error($terms)) {
                $tax_data[$taxonomy] = $terms;
            }
        }

        $flat_meta = [];
        foreach ($meta as $key => $val) {
            $flat_meta[$key] = is_array($val) && count($val) === 1 ? $val[0] : $val;
        }

        $data[] = [
            'post'       => get_object_vars($post_data),
            'meta'       => $flat_meta,
            'taxonomies' => $tax_data
        ];
    }

    $backup_dir = plugin_dir_path(__FILE__) . '../backups/';
    if (!file_exists($backup_dir)) {
        mkdir($backup_dir, 0755, true);
    }

    $file = $backup_dir . 'revus-backup-' . date('Y-m-d-H-i-s') . '.json';
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    return basename($file);
}
