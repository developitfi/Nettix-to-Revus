<?php
defined('ABSPATH') || exit;

add_action('admin_menu', function () {
    
});

function nettix_revus_help_page() {
    ?>
    <div class="wrap">
        <h1>Nettix to Revus – Käyttöohje</h1>

        <p>Tämä lisäosa tuo ajoneuvotiedot Nettix Pro -rajapinnasta WordPressin <code>pixad_autos</code>-postityyppiin.</p>

        <h2>Toiminnot:</h2>
        <ul>
            <li>OAuth2-tokenin haku</li>
            <li>Ajoneuvojen nouto ja tallennus</li>
            <li>Kuvien ja varusteiden käsittely</li>
            <li>Kenttäkohdistus (meta, taksonomia, ACF)</li>
            <li>Automaattinen poisto ja cron-synkronointi</li>
            <li>Lokitus ja sähköposti-ilmoitukset</li>
        </ul>

        <h2>Kenttäkohdistusvinkkejä:</h2>
        <table class="widefat">
            <thead><tr><th>Nettix-kenttä</th><th>WordPress-kenttä</th><th>Tyyppi</th></tr></thead>
            <tbody>
                <tr><td><code>price</code></td><td><code>_acf_price</code></td><td>Metakenttä (ACF)</td></tr>
                <tr><td><code>make</code></td><td><code>tax_make</code></td><td>Taksonomia</td></tr>
                <tr><td><code>equipment</code></td><td><code>tax_equipment</code></td><td>Taksonomia (array)</td></tr>
                <tr><td><code>description</code></td><td><code>acf_flex_desc</code></td><td>ACF Flexible Content</td></tr>
            </tbody>
        </table>

        <h2>JSON-esikatselu</h2>
        <p>Jos käytössä, voit tarkastella jokaiseen ajoneuvoon liittyvää raakadataa suoraan postieditorissa.</p>

        <h2>Versiotiedot</h2>
        <p>Versio: <strong>2.6.0</strong><br>Valmistaja: <strong>developit // it-ystävä</strong></p>
    </div>
    <?php
}
