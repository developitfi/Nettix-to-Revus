<?php
defined('ABSPATH') || exit;

function nettix_revus_apply_mappings($post_id, $vehicle) {
    $mappings = get_option('nettix_field_mappings', []);

    foreach ($mappings as $source => $target) {
        if (!isset($vehicle[$source]) || empty($target)) continue;

        $value = $vehicle[$source];

        if (strpos($target, 'tax_') === 0) {
            $taxonomy = substr($target, 4);
            wp_set_object_terms($post_id, is_array($value) ? $value : [$value], $taxonomy);
        } elseif (strpos($target, 'acf_') === 0 && function_exists('update_field')) {
            $field_key = substr($target, 4);
            update_field($field_key, $value, $post_id);
        } else {
            update_post_meta($post_id, $target, $value);
        }
    }
}
