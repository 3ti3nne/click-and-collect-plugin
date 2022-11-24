<?php

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
    <div class="wrap">

        <h1>Click and Collect Options</h1>



        <form method="POST" action="options.php">
            <?php
            settings_fields('ccplugin');
            do_settings_sections(' click-and-collect-settings-page ');

            $daysArray = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];
            $times = [6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21]
            ?>
            <fieldset>
                <h2>Jour(s) de fermeture</h2>
                <?php foreach ($daysArray as $day) {
                ?>
                    <div>
                        <label for="<?= $day ?>"><?= ucfirst($day) ?></label>
                        <input id="<?= $day ?>" type="checkbox" name="click_and_collect_day_off[<?= $day ?>]" value="<?= $day ?>" />
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
                foreach ($daysOff as $day => $value) {
                ?>
                    <li><?= ucfirst($value) ?></li>

            <?php
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






////////////// Remove some of the checkout fields non necessary for a pickup order

add_filter('woocommerce_checkout_fields', 'remove_checkout_fields');

function remove_checkout_fields($fields)
{

    unset(
        $fields['billing']['billing_email'],
        $fields['billing']['billing_company'],
        $fields['billing']['billing_address_1'],
        $fields['billing']['billing_address_2'],
        $fields['billing']['billing_city'],
        $fields['billing']['billing_postcode']
    );

    return $fields;
}


/////////////  Add checkout field for date and time of the pickup

add_action('woocommerce_after_order_notes', 'custom_checkout_field');

function custom_checkout_field($checkout)

{
    ///For days
    ?>

        <div id="click_and_collect_day_off">
            <h2>Selectionnez un jour pour votre commande. </h2>
            <p>Nous sommes fermés les <?php
                                        $daysOff = (get_option('click_and_collect_day_off'));

                                        foreach ($daysOff as $day => $value) {
                                            echo  ucfirst($value . " ");
                                        }
                                        ?> </p>


            <?php

            $checkout = WC()->checkout();


            woocommerce_form_field(
                'click_and_collect_day',
                array(
                    'type' => 'date',
                    'class' => array('click_and_collect_day', 'form-row-wide'),
                    'label' => __('Jour du retrait'),
                    'placeholder' => __('Jour du retrait'),
                    'required' => true
                ),

                $checkout->get_value('click_and_collect_day')
            );
            ?>
        </div>


        <!--         For time     -->
        <div id="click_and_collect_time">
            <h2>Selectionnez une heure pour votre commande. </h2>

            <?php

            woocommerce_form_field(
                'click_and_collect_time',
                array(
                    'type' => 'select',
                    'options' => array(
                        'Choisissez une heure',

                        '08h00' => '08h00',
                        '09h00' => '09h00',
                        '10h00' => '10h00',
                        '11h00' => '11h00',
                        '12h00' => '12h00',
                        '13h00' => '13h00',
                        '14h00' => '14h00',
                        '15h00' => '15h00',
                        '16h00' => '16h00',
                        '17h00' => '17h00',
                        '18h00' => '18h00',
                        '19h00' => '19h00',
                        '20h00' => '20h00'

                    ),
                    'class' => array('click_and_collect_time', 'form-row-wide'),
                    'label' => __('Heure du retrait'),
                    'required' => true
                ),

                $checkout->get_value('click_and_collect_time')
            );

            echo '</div>';
        }

        //////////////////  Show an error message if the field is not set

        add_action('woocommerce_checkout_process', 'click_and_collect_checkout_field_process');

        function click_and_collect_checkout_field_process()
        {
            if (!$_POST['click_and_collect_time']) {
                wc_add_notice(__('L\'heure de retrait est obligatoire.'), 'error');
            }
            if (!$_POST['click_and_collect_day']) {
                wc_add_notice(__('La date de retrait est obligatoire.'), 'error');
            }
        }


        /////////   Add the optionnal checkout fields to the database in wp_postmeta table  /////////////////:

        add_action('woocommerce_checkout_update_order_meta', 'click_and_collect_checkout_field_update_order_meta');

        function click_and_collect_checkout_field_update_order_meta($order_id)

        {

            if (!empty($_POST['click_and_collect_time']) && !empty($_POST['click_and_collect_day'])) {

                update_post_meta($order_id, 'click_and_collect_day', sanitize_text_field($_POST['click_and_collect_day']));
                update_post_meta($order_id, 'click_and_collect_time', sanitize_text_field($_POST['click_and_collect_time']));
            }
        }


        ////////    Add some order details to the thank you page

        add_action('woocommerce_order_details_after_order_table_items', 'custom_order_details_click_and_collect');

        function custom_order_details_click_and_collect($order)
        {

            $day = get_post_meta($order->get_id(), 'click_and_collect_day', true);
            $time = get_post_meta($order->get_id(), 'click_and_collect_time', true);

            if ($day) {
            ?>
                <tr>
                    <th scope="row">Jour du retrait</th>
                    <td><?= wp_date('l d, F Y', strtotime($day)) ?></td>
                </tr>
            <?php

            }
            if ($time) {
            ?>
                <tr>
                    <th scope="row">Heure du retrait</th>
                    <td><?= ($time) ?></td>
                </tr>
        <?php
            }
        }
