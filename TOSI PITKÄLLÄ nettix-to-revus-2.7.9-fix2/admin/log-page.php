<?php
defined('ABSPATH') || exit;

add_action('admin_menu', function () {
    
});

function nettix_revus_log_page() {
    $log_file = plugin_dir_path(__FILE__) . '../data/nettix-log.json';
    $log_entries = file_exists($log_file) ? json_decode(file_get_contents($log_file), true) : [];
    $search_term = isset($_POST['log_search']) ? sanitize_text_field($_POST['log_search']) : null;

    if ($search_term) {
        $log_entries = array_filter($log_entries, function($entry) use ($search_term) {
            return stripos($entry['message'], $search_term) !== false;
        });
    }

    ?>
    <div class="wrap">
        <h1>Nettix to Revus – Loki</h1>

        <form method="post">
            <input type="text" name="log_search" value="<?php echo esc_attr($search_term); ?>" placeholder="Hae lokista..." class="regular-text">
            <?php submit_button('Hae'); ?>
        </form>

        <form method="post" style="margin-top:1em;">
            <input type="hidden" name="clear_log" value="1">
            <?php submit_button('Tyhjennä loki', 'delete'); ?>
        </form>

        <table class="widefat fixed striped" style="margin-top:1em;">
            <thead><tr><th>Aika</th><th>Tyyppi</th><th>Viesti</th></tr></thead>
            <tbody>
                <?php if (empty($log_entries)): ?>
                    <tr><td colspan="3">Ei lokimerkintöjä.</td></tr>
                <?php else: ?>
                    <?php foreach (array_reverse($log_entries) as $entry): ?>
                        <tr>
                            <td><?php echo esc_html($entry['time']); ?></td>
                            <td><strong><?php echo esc_html($entry['type']); ?></strong></td>
                            <td><?php echo esc_html($entry['message']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php

    if (isset($_POST['clear_log']) && current_user_can('manage_options')) {
        file_put_contents($log_file, json_encode([]));
        echo '<div class="notice notice-success"><p>Loki tyhjennetty.</p></div>';
    }
}
