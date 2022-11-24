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

            add_action('wp_enqueue_scripts', 'custom_css_file', 50);

            function custom_css_file()
            {
                wp_register_style('customStyle', '/wp-content/plugins/click-and-collect-plugin/css/customStyle.css');
                wp_enqueue_style('customStyle');
            };
        }
    }

    $clickAndCollect = new ClickAndCollect;
    $clickAndCollect->instanciate();
}
