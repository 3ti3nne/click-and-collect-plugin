<?php
/*
* Plugin Name: Click and Collect Plugin
* Plugin URI: github.com
* Description: Add Click and Collect Time Selector
* Version: 0.1
* Author: Etienne
* Author URI: github.com
*/





//Prevent from accessing the plugin page if not on wordpress.
if (!defined('ABSPATH')) {
    exit;
}

//Prevent from having two instances of the class

if (!class_exists('ClickAndCollect')) {



    class ClickAndCollect
    {
        public function __construct()
        {
            define('CC_PLUGIN_PATH', plugin_dir_path(__FILE__));
        }

        public function instanciate()
        {

            include_once(CC_PLUGIN_PATH . '/controllers/utilities.php');
            include_once(CC_PLUGIN_PATH . '/admin/admin-page.php');

            function custom_script()
            {

                $daysOff = get_option("click_and_collect_day_off");

                wp_enqueue_script('jquery-ui-datepicker');
                wp_enqueue_script('custom_script', '/wp-content/plugins/click-and-collect-plugin/js/customDatePicker.js', array('jquery'), '1.0', true);
                wp_localize_script('custom_script', 'php_vars', $daysOff);
                wp_enqueue_style('style', '/wp-content/plugins/click-and-collect-plugin/css/customStyle.css');
            }

            add_action('wp_enqueue_scripts', 'custom_script');
        }
    }

    $clickAndCollect = new ClickAndCollect;
    $clickAndCollect->instanciate();
}
