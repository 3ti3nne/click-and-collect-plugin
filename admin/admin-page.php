<?php

require('C:/xampp/htdocs/wordpress/wp-load.php');
//Add a menu page, hook on admin_menu
add_action('admin_menu', 'click_and_collect_settings_menu');
//Add the content of the form to the db
add_action('admin_init', 'click_and_collect_settings');




//////////  Admin page pour the plugin

function click_and_collect_settings()
{
    ///For days off
    add_settings_section('cc_days_section', 'Choississez le(s) jour(s) de fermeture', null, 'click-and-collect-settings-page');
    add_settings_field('click_and_collect_day_off', 'Choississez le(s) jour(s) de fermeture', 'options_page_click_and_collect', 'click-and-collect-settings-page', 'cc_days_section');
    register_setting('ccplugin', 'click_and_collect_day_off', 'array', array('sanitize_callback' => 'sanitize_text_field', 'default' => 'dimanche'));

    ///For open hours
    add_settings_section('cc_opens_at_section', 'Choississez l\'heure d\'ouverture', null, 'click-and-collect-settings-page');
    add_settings_field('click_and_collect_opens_at', 'Choississez l\'heure d\'ouverture', 'options_page_click_and_collect', 'click-and-collect-settings-page', 'cc_days_section');
    register_setting('ccplugin', 'click_and_collect_opens_at', 'array', array('sanitize_callback' => 'sanitize_text_field'));


    ///For close hours
    add_settings_section('cc_closes_at_section', 'Choississez l\'heure de fermeture', null, 'click-and-collect-settings-page');
    add_settings_field('click_and_collect_closes_at', 'Choississez l\'heure de fermeture', 'options_page_click_and_collect', 'click-and-collect-settings-page', 'cc_days_section');
    register_setting('ccplugin', 'click_and_collect_closes_at', 'array', array('sanitize_callback' => 'sanitize_text_field'));
}

function click_and_collect_settings_menu()
{
    add_options_page('Click and Collect Options', 'Click and Collect', 'manage_options', 'click-and-collect-settings-page', 'options_page_click_and_collect');
}

function options_page_click_and_collect()
{
    if (!current_user_can('manage_options')) {
        wp_die('Vous n\'avez pas les permissions.');
    }

?>


    <!-- //////////////////// Form on the admin page to register options in wp DB -->
    <div class="wrap">

        <h1>Click and Collect Options</h1>

        <form method="POST" action="options.php">

            <?php

            ///////////////     Options     //////////////////////////////

            settings_fields('ccplugin');
            do_settings_sections(' click-and-collect-settings-page ');

            $daysArray = [
                1 => 'lundi',
                2 => 'mardi',
                3 => 'mercredi',
                4 => 'jeudi',
                5 => 'vendredi',
                6 => 'samedi',
                0 => 'dimanche'
            ];
            $times = [6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21]

            ///////////////   End of Options     //////////////////////////////

            ?>


            <fieldset>
                <h2>Jour(s) de fermeture</h2>
                <?php foreach ($daysArray as $key => $day) {
                ?>
                    <div>
                        <label for="<?= $day ?>"><?= ucfirst($day) ?></label>
                        <input id="<?= $day ?>" type="checkbox" name="click_and_collect_day_off[]" value="<?= $key ?>" />
                    </div>
                <?php
                }
                ?>
                <h2>Heure d'ouverture</h2>
                <select name="click_and_collect_opens_at" id="opensAt">
                    <?php foreach ($times as $time) {
                    ?>
                        <option value="<?= $time ?>"><?= $time ?>h00</option>
                    <?php
                    }
                    ?>
                </select>
                <h2>Heure de fermeture</h2>
                <select name="click_and_collect_closes_at" id="closesAt">
                    <?php foreach ($times as $time) {
                    ?>
                        <option value="<?= $time ?>"><?= $time ?>h00</option>
                    <?php
                    }
                    ?>
                </select>
                <div>
                    <?php
                    submit_button() ?>
                </div>
            </fieldset>

        </form>

    </div>
    <?php
    if (!empty(get_option('click_and_collect_day_off'))) {
    ?>
        <div class="wrap">
            <h2>Les jours de fermeture sélectionnés sont : </h2>
            <ul>

                <?php
                $daysOff = (get_option('click_and_collect_day_off'));

                foreach ($daysOff as $key => $value) {
                    foreach ($daysArray as $num => $day) {
                        if ((int)$value === $num) {

                ?>
                            <li><?= ucfirst($day) ?></li>

            <?php
                        }
                    }
                }
            }


            ?>

            </ul>

            <?php




            if (!empty(get_option('click_and_collect_opens_at'))) {
            ?>
                <h2>Les horaires sont</h2>
                <p>Ouvert de <?= (get_option('click_and_collect_opens_at')) . "h00 à " . (get_option('click_and_collect_closes_at')) . "h00" ?></p>
            <?php
            };
            ?>
        </div>
    <?php
}

/////////// End of admin page
