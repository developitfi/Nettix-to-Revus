<?php
defined('ABSPATH') || exit;
require_once plugin_dir_path(__FILE__) . '/../includes/available-fields.php';

add_action('admin_menu', function () {
    
});

add_action('admin_init', function () {
    register_setting('nettix_revus_mapping_group', 'nettix_field_mappings');
});

function nettix_revus_get_revus_fields() {
    return [
        '' => '– ei valintaa –',
        '_car_price' => '_car_price (hinta)',
        '_car_year' => '_car_year (vuosimalli)',
        '_car_mileage' => '_car_mileage (kilometrit)',
        '_car_equipment' => '_car_equipment (varusteet)',
        'tax_make' => 'tax_make (merkki)',
        'tax_fuel' => 'tax_fuel (polttoaine)',
        '_acf_gallery' => '_acf_gallery (kuvagalleria)'
    ];
}

function nettix_revus_mapping_page() {
    $saved_mappings = get_option('nettix_field_mappings', []);
    $available_fields = nettix_revus_get_available_fields();
    $revus_fields = nettix_revus_get_revus_fields();
    ?>
    <div class="wrap">
        <h1>Visuaalinen kenttäkohdistus</h1>
        <p>Valitse, mihin WordPress-kenttään kukin Nettix-kenttä tallennetaan.</p>
        <form method="post" action="options.php">
            <?php settings_fields('nettix_revus_mapping_group'); ?>
            <table class="form-table">
                <thead>
                    <tr><th>Nettix-kenttä</th><th>Revus-teeman autos-kohdan kenttä</th><th>Muut (vapaamuotoinen)</th></tr>
                </thead>
                <tbody>
                <?php foreach ($available_fields as $field): ?>
                    <?php
                        $selected_value = $saved_mappings[$field] ?? '';
                        $is_custom = (!isset($revus_fields[$selected_value]) && !in_array($selected_value, array_keys($revus_fields)));
                    ?>
                    <tr>
                        <td><strong><?php echo esc_html($field); ?></strong></td>
                        <td>
                            <select name="nettix_field_mappings[<?php echo esc_attr($field); ?>]">
                                <?php foreach ($revus_fields as $key => $label): ?>
                                    <option value="<?php echo esc_attr($key); ?>" <?php selected($selected_value, $key); ?>><?php echo esc_html($label); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="nettix_field_mappings[<?php echo esc_attr($field); ?>]"
                                value="<?php echo $is_custom ? esc_attr($selected_value) : ''; ?>"
                                placeholder="esim. _acf_hinta, tax_custom"
                                class="regular-text" />
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php submit_button('Tallenna kohdistukset'); ?>
        </form>
    </div>
<?php } ?>
