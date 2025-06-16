<?php
defined('ABSPATH') || exit;

function nettix_revus_render_status_page() {
    $log = get_option('nettix_revus_last_log', []);
    $log_time = isset($log['time']) ? date_i18n('j.n.Y \k\l\o G:i', strtotime($log['time'])) : 'Ei saatavilla';
    $log_success = isset($log['success']) ? $log['success'] : false;
    $vehicle_count = isset($log['count']) ? intval($log['count']) : 0;
    $recent_titles = isset($log['titles']) && is_array($log['titles']) ? $log['titles'] : [];

    $status_class = $log_success ? 'notice-success' : 'notice-error';
    $status_icon = $log_success ? '✔️' : '❌';
    $status_text = $log_success ? 'Viimeisin tuonti onnistui' : 'Viimeisin tuonti epäonnistui';
    ?>

    <div class="wrap">
        <h1>Nettix to Revus – Tilaruutu</h1>

        <div class="notice <?php echo $status_class; ?> is-dismissible">
            <p><strong><?php echo $status_icon . ' ' . $status_text; ?></strong></p>
            <p>Ajankohta: <code><?php echo $log_time; ?></code></p>
            <p>Tuotu ajoneuvoja: <strong><?php echo $vehicle_count; ?></strong></p>
        </div>

        <?php if (!empty($recent_titles)) : ?>
            <h2>Tuodut ajoneuvot (uusimmat):</h2>
            <ul>
                <?php foreach (array_slice($recent_titles, 0, 5) as $title): ?>
                    <li>🚗 <?php echo esc_html($title); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="nettix_revus_sync_now" value="1" />
            <?php submit_button('🔁 Käynnistä synkronointi nyt'); ?>
        </form>
    </div>
    <?php
}
