<?php
defined('ABSPATH') || exit;

add_action('admin_menu', function () {
    
});

function nettix_revus_json_page() {
    $json_file = plugin_dir_path(__FILE__) . '../data/last_import.json';
    $data = file_exists($json_file) ? json_decode(file_get_contents($json_file), true) : [];

    echo '<div class="wrap"><h1>JSON-esikatselu – Viimeisin tuonti</h1>';

    if (!$data || empty($data)) {
        echo '<p><em>Ei dataa saatavilla.</em></p></div>';
        return;
    }

    echo '<p>Alla näytetään JSON-data viimeisimmästä tuonnista:</p>';
    echo '<textarea rows="30" style="width:100%; font-family:monospace;">';
    echo esc_textarea(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo '</textarea>';
    echo '</div>';
}
