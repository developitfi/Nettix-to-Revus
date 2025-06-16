<?php
function nettix_revus_register_menu() {
    add_menu_page(
        'Nettix to Revus',
        'Nettix to Revus',
        'manage_options',
        'nettix_to_revus',
        'nettix_revus_render_settings_page',
        'dashicons-admin-tools',
        30
    );

    add_submenu_page('nettix_to_revus', 'Asetukset', 'Asetukset', 'manage_options', 'nettix_to_revus', 'nettix_revus_render_settings_page');
    add_submenu_page('nettix_to_revus', 'Tilaruutu', 'Tilaruutu', 'manage_options', 'nettix_to_revus_status', 'nettix_revus_render_status_page');
    add_submenu_page('nettix_to_revus', 'Kenttäkohdistus', 'Kenttäkohdistus', 'manage_options', 'nettix_to_revus_field_mapping', 'nettix_revus_mapping_page');
    add_submenu_page('nettix_to_revus', 'JSON-esikatselu', 'JSON-esikatselu', 'manage_options', 'nettix_to_revus_json_preview', 'nettix_revus_json_page');
    add_submenu_page('nettix_to_revus', 'Lokit', 'Lokit', 'manage_options', 'nettix_to_revus_log', 'nettix_revus_log_page');
    add_submenu_page('nettix_to_revus', 'Varmuuskopiointi', 'Varmuuskopiointi', 'manage_options', 'nettix_to_revus_backup', 'nettix_revus_backup_page');
    add_submenu_page('nettix_to_revus', 'Ohje', 'Ohje', 'manage_options', 'nettix_to_revus_help', 'nettix_revus_help_page');
}
add_action('admin_menu', 'nettix_revus_register_menu');
?>
