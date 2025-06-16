<?php
defined('ABSPATH') || exit;

// Suorita synkronointi kun cron-hook käynnistyy
add_action('nettix_revus_cron_sync', function () {
    if (function_exists('nettix_revus_sync')) {
        nettix_revus_sync();
        nettix_revus_log('info', 'Synkronointi suoritettu cronin kautta.');
    } else {
        nettix_revus_log('error', 'Cron-kutsu: nettix_revus_sync ei ole saatavilla.');
    }
});
