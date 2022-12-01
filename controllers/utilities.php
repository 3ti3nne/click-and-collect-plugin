<?php

////////////// Checkout page and registering order datas to db //////////////





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


//////////////  Add checkout field for date and time of the pickup

add_action('woocommerce_after_order_notes', 'custom_checkout_field');

function custom_checkout_field($checkout)

{
    $checkout = WC()->checkout();



    //////////////  For days
?>

    <div id="click_and_collect_day_off">
        <h2>Selectionnez un jour pour votre commande. </h2>
        <p>Nous sommes fermés les <?php

                                    $daysOff = (get_option('click_and_collect_day_off'));

                                    $daysArray = [
                                        1 => 'lundi',
                                        2 => 'mardi',
                                        3 => 'mercredi',
                                        4 => 'jeudi',
                                        5 => 'vendredi',
                                        6 => 'samedi',
                                        0 => 'dimanche'
                                    ];

                                    foreach ($daysOff as $key => $value) {
                                        foreach ($daysArray as $num => $day) {

                                            if ((int)$value === $num) {

                                                echo  ucfirst($day . " ");
                                            }
                                        }
                                    }
                                    ?>
        </p>

        <?php



        woocommerce_form_field(
            'click_and_collect_day',
            array(
                'type' => 'text',
                'id' => 'datepicker',
                'class' => array('click_and_collect_day', 'form-row-wide'),
                'label' => __('Jour du retrait'),
                'placeholder' => __('Jour du retrait'),
                'required' => true
            ),

            $checkout->get_value('click_and_collect_day')
        );

        ?>

    </div>


    <!--//////////////  For time     -->

    <div id="click_and_collect_time">
        <h2>Selectionnez une heure pour votre commande. </h2>

        <?php

        //////////////  Retrieve times registered in db

        $opensAt = get_option('click_and_collect_opens_at');
        $closesAt = get_option('click_and_collect_closes_at');

        $hours = [
            6 => '06h00',
            7 => '07h00',
            8 => '08h00',
            9 => '09h00',
            10 => '10h00',
            11 => '11h00',
            12 => '12h00',
            13 => '13h00',
            14 => '14h00',
            15 => '15h00',
            16 => '16h00',
            17 => '17h00',
            18 => '18h00',
            19 => '19h00',
            20 => '20h00'
        ];


        //////////////  New arrays filtered by open and close hours

        $filteredOpensAt = [];

        ////////////// To counter key getting defined and saved in db instead of hour

        for ($i = $opensAt; $i <= $closesAt; $i++) {
            $filteredOpensAt += [$hours[$i] => $hours[$i]];
        }

        woocommerce_form_field(
            'click_and_collect_time',
            array(
                'type' => 'select',
                'options' => $filteredOpensAt,
                'class' => array('click_and_collect_time', 'form-row-wide'),
                'label' => __('Heure du retrait'),
                'required' => true
            ),
            $checkout->get_value('click_and_collect_time')
        );

        echo '</div>';
    }

    //////////////  Show an error message if the field is not set

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


    //////////////   Add the optionnal checkout fields to the database in wp_postmeta table  /////////////////:

    add_action('woocommerce_checkout_update_order_meta', 'click_and_collect_checkout_field_update_order_meta');

    function click_and_collect_checkout_field_update_order_meta($order_id)

    {

        if (!empty($_POST['click_and_collect_time']) && !empty($_POST['click_and_collect_day'])) {

            update_post_meta($order_id, 'click_and_collect_day', sanitize_text_field($_POST['click_and_collect_day']));
            update_post_meta($order_id, 'click_and_collect_time', sanitize_text_field($_POST['click_and_collect_time']));
        }
    }


    //////////////    Add some order details to the thank you page

    add_action('woocommerce_order_details_after_order_table_items', 'custom_order_details_click_and_collect');

    function custom_order_details_click_and_collect($order)
    {

        $day = get_post_meta($order->get_id(), 'click_and_collect_day', true);
        $time = get_post_meta($order->get_id(), 'click_and_collect_time', true);

        if ($day) {
        ?>

            <tr>
                <th scope="row">Jour du retrait</th>
                <td><?= $day ?></td>
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

    //////////////  Finitos the pepitos