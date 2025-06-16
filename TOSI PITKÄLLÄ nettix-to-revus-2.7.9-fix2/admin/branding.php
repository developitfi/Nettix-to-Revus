<?php
defined('ABSPATH') || exit;

/**
 * Lisää brändätyt tyylit vain lisäosan hallintasivuille
 */
add_action('admin_enqueue_scripts', function ($hook) {
    if (isset($_GET['page']) && strpos($_GET['page'], 'nettix_to_revus') !== false) {
        wp_enqueue_style('nettix-to-revus-branding', plugin_dir_url(__FILE__) . 'assets/style.css');
    }
});

/**
 * Näyttää logon lisäosan hallintasivujen yläosassa
 */
add_action('in_admin_header', function () {
    if (isset($_GET['page']) && strpos($_GET['page'], 'nettix_to_revus') !== false) {
        $logo_url = plugin_dir_url(__FILE__) . 'assets/logo.png'; // oletusnimi
        echo '<style>.wrap > h1 { display: flex; align-items: center; gap: 12px; }</style>';
        echo '<script>document.addEventListener("DOMContentLoaded", function() {
            var h1 = document.querySelector(".wrap > h1");
            if(h1) {
                var img = document.createElement("img");
                img.src = "' . esc_url($logo_url) . '";
                img.id = "nettix-brand-logo";
                h1.prepend(img);
            }
        });</script>';
    }
});
