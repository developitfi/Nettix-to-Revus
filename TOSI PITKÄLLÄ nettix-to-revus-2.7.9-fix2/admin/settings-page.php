<?php
defined('ABSPATH') || exit;

function nettix_revus_render_settings_page() {
    ?>
    <div class="wrap">
        <h1>Nettix to Revus – Asetukset</h1>
        <form method="post" action="options.php">
            <?php settings_fields('nettix_revus_settings'); ?>
            <?php do_settings_sections('nettix_revus'); ?>

            <div id="poststuff">
                <div class="postbox">
                    <h2 class="hndle">🔗 Rajapinta-asetukset</h2>
                    <div class="inside">
                        <table class="form-table">
                            <tr>
                                <th scope="row"><label for="nettix_token_url">Token URL</label></th>
                                <td>
                                    <input name="nettix_token_url" type="text" id="nettix_token_url" value="<?php echo esc_attr(get_option('nettix_token_url')); ?>" class="regular-text">
                                    <p class="description">OAuth2-tunnuksen hakemiseen käytettävä osoite</p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="nettix_api_url">API URL</label></th>
                                <td>
                                    <input name="nettix_api_url" type="text" id="nettix_api_url" value="<?php echo esc_attr(get_option('nettix_api_url')); ?>" class="regular-text">
                                    <p class="description">Nettix Pro -rajapinnan pääosoite (esim. https://api.nettix.fi/endpoint)</p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="nettix_cron_interval">Hakuväli (cron)</label></th>
                                <td>
                                    <select name="nettix_cron_interval" id="nettix_cron_interval">
                                        <option value="hourly" <?php selected(get_option('nettix_cron_interval'), 'hourly'); ?>>Tunti</option>
                                        <option value="twicedaily" <?php selected(get_option('nettix_cron_interval'), 'twicedaily'); ?>>2x päivä</option>
                                        <option value="daily" <?php selected(get_option('nettix_cron_interval'), 'daily'); ?>>Päivittäin</option>
                                    </select>
                                    <p class="description">Kuinka usein tietoja haetaan automaattisesti rajapinnasta.</p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="postbox">
                    <h2 class="hndle">🔐 Tunnistetiedot</h2>
                    <div class="inside">
                        <table class="form-table">
                            <tr>
                                <th scope="row"><label for="nettix_client_id">Client ID</label></th>
                                <td>
                                    <input name="nettix_client_id" type="text" id="nettix_client_id" value="<?php echo esc_attr(get_option('nettix_client_id')); ?>" class="regular-text">
                                    <p class="description">Nettixiltä saamasi asiakastunnus</p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="nettix_client_secret">Client Secret</label></th>
                                <td>
                                    <input name="nettix_client_secret" type="password" id="nettix_client_secret" value="<?php echo esc_attr(get_option('nettix_client_secret')); ?>" class="regular-text">
                                    <p class="description">Salainen avain, älä jaa tätä muiden kanssa</p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="postbox">
                    <h2 class="hndle">📡 API-yhteyden tila</h2>
                    <div class="inside">
                        <?php
                        $api_url = get_option('nettix_api_url');
                        if ($api_url) {
                            $response = wp_remote_get($api_url);
                            if (is_wp_error($response)) {
                                echo '<p style="color:red;">❌ Yhteys epäonnistui: ' . esc_html($response->get_error_message()) . '</p>';
                            } else {
                                $code = wp_remote_retrieve_response_code($response);
                                if ($code >= 200 && $code < 300) {
                                    echo '<p style="color:green;">✅ Yhteys onnistui (HTTP ' . esc_html($code) . ')</p>';
                                } else {
                                    echo '<p style="color:orange;">⚠️ Yhteys muodostui, mutta vastauskoodi oli ' . esc_html($code) . '</p>';
                                }
                            }
                        } else {
                            echo '<p style="color:gray;">ℹ️ API URL ei ole asetettu.</p>';
                        }
                        ?>
                    </div>
                </div>

                <p class="submit">
                    <button type="submit" class="button button-primary">💾 Tallenna asetukset</button>
                </p>
            </div>
        </form>
    </div>
    <?php
}
