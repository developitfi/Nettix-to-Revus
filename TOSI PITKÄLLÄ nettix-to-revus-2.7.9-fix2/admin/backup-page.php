<?php
defined('ABSPATH') || exit;

add_action('admin_menu', function () {
    
});

function nettix_revus_backup_page() {
    $backup_dir = plugin_dir_path(__FILE__) . '../backups/';
    $backup_url = plugin_dir_url(__FILE__) . '../backups/';
    $files = [];

    if (file_exists($backup_dir)) {
        foreach (glob($backup_dir . '*.json') as $file) {
            $files[] = basename($file);
        }
    }
    ?>
    <div class="wrap">
        <h1>Nettix to Revus – Varmuuskopiointi (AJAX)</h1>

        <button id="ajax-create-backup" class="button button-primary">📦 Luo uusi varmuuskopio (AJAX)</button>
        <div id="ajax-status" style="margin-top:1em;"></div>

        <?php if (!empty($files)): ?>
            <h2 style="margin-top:2em;">Varmuuskopiotiedostot</h2>
            <table class="widefat striped">
                <thead><tr><th>Tiedosto</th><th>Toiminnot</th></tr></thead>
                <tbody id="backup-list">
                <?php foreach ($files as $file): ?>
                    <tr data-file="<?php echo esc_attr($file); ?>">
                        <td><?php echo esc_html($file); ?></td>
                        <td>
                            <a class="button" href="<?php echo esc_url($backup_url . $file); ?>" download>⬇️ Lataa</a>
                            <button class="button ajax-restore" data-file="<?php echo esc_attr($file); ?>">♻️ Palauta</button>
                            <button class="button button-link-delete ajax-delete" data-file="<?php echo esc_attr($file); ?>">🗑 Poista</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <script>
        const status = document.getElementById('ajax-status');

        document.getElementById('ajax-create-backup').addEventListener('click', () => {
            status.textContent = 'Luodaan varmuuskopiota...';
            fetch(ajaxurl, {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=nettix_ajax_create_backup'
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    status.innerHTML = '<span style="color:green;">✅ Varmuuskopio luotu: ' + res.data.filename + '</span>';
                    location.reload(); // Päivitä lista
                } else {
                    status.innerHTML = '<span style="color:red;">❌ ' + res.data + '</span>';
                }
            });
        });

        document.querySelectorAll('.ajax-delete').forEach(btn => {
            btn.addEventListener('click', () => {
                const file = btn.dataset.file;
                if (!confirm('Poistetaanko varmuuskopio ' + file + '?')) return;

                fetch(ajaxurl, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'action=nettix_ajax_delete_backup&filename=' + encodeURIComponent(file)
                })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        btn.closest('tr').remove();
                        status.innerHTML = '<span style="color:green;">🗑 ' + file + ' poistettu</span>';
                    } else {
                        status.innerHTML = '<span style="color:red;">❌ ' + res.data + '</span>';
                    }
                });
            });
        });

        document.querySelectorAll('.ajax-restore').forEach(btn => {
            btn.addEventListener('click', () => {
                const file = btn.dataset.file;
                if (!confirm('Palautetaanko varmuuskopio ' + file + '?')) return;

                status.textContent = 'Palautetaan...';
                fetch(ajaxurl, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'action=nettix_ajax_restore_backup&filename=' + encodeURIComponent(file)
                })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        status.innerHTML = '<span style="color:green;">♻️ Palautus onnistui: ' + file + '</span>';
                    } else {
                        status.innerHTML = '<span style="color:red;">❌ ' + res.data + '</span>';
                    }
                });
            });
        });
        </script>
    </div>
<?php } ?>
