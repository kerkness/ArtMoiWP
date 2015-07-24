<?php

/**
 * @package ArtMoi Wordpress Plugin
 * @version 1.0
 */
/*
Plugin Name: ArtMoi Wordpress Plugin
Description: Plugin for syncing your ArtMoi account with Wordpress Media Files
Version: 1.0
Author URI: http://artmoi.com
*/

// Require flight
require dirname(__FILE__).'/vendor/flight/flight/Flight.php';

// Set the search path for flight registered classes
Flight::path( dirname(__FILE__).'/classes');

// Set the view folder for flight
Flight::set('flight.views.path',  dirname(__FILE__).'/views');

// Register classes with flight
Flight::register('request', 'Artmoi_Request');
Flight::register('response','Artmoi_response');
Flight::register('controller', 'Artmoi_Controller');
Flight::register('images','ArtMoi_Images');


class ArtMoi_WP{
    //constructor
    public function __construct()
    {

        add_action('admin_menu', array($this, 'wpa_add_menu'));
        add_action('admin_init',array($this,'register_mysettings'));
        wp_enqueue_script('admin_js_bootstrap_hack', plugins_url('ArtMoiWP/scripts/bootstrap-hack.js'));
        wp_enqueue_script('admin_js_bootstrap', plugins_url('ArtMoiWP/scripts/bootstrap.js'));
        //wp_enqueue_style('wp-artmoi-style',plugins_url('css/style.css',__FILE__)); // TODO: MAKE IT PRETTY!
        register_activation_hook( __FILE__, array($this, 'wpa_install'));
        register_deactivation_hook(__FILE__, array($this, 'wpa_uninstall'));

    }

    /* actions perform at loading of admin menu*/
    public function wpa_add_menu()
    {
        add_menu_page( 'ArtMoi', 'ArtMoi', 'manage_options', 'ArtMoi-dashboard', array(
            __CLASS__,
            'wpa_page_file_path'
        ), plugins_url('images/ArtMoi-Logo.png', __FILE__),'1.0.0');

        add_submenu_page( 'ArtMoi-dashboard', 'ArtMoi' . ' Dashboard', ' Dashboard', 'manage_options', 'ArtMoi-dashboard', array(
            __CLASS__,
            'wpa_page_file_path'
        ));

        add_submenu_page( 'ArtMoi-dashboard', 'ArtMoi' . ' Settings', 'Settings', 'manage_options', 'ArtMoi-settings', array(
            __CLASS__,
            'wpa_page_file_path'
        ));
    }

    /* actions perform at loading of menu pages */
    public function wpa_page_file_path()
    {
        $screen = get_current_screen();
        $is_apikey = get_option('artmoiwp_apikey');
        // stay on the setting page until a visitor enters the api key
        if(is_null($is_apikey)){
            echo "<h3>Please enter an ArtMoi API Key</h3>";
            Flight::controller()->settings();
        }
        else{
            if(strpos($screen->base, 'ArtMoi-settings') !== false)
            {
                Flight::controller()->settings();
            }
            else
            {
                Flight::controller()->dashboard();
            }
        }
    }

    /* Register options */
    public function register_mysettings()
    {
        register_setting('artmoiwp_apikey','artmoiwp_apikey');

    }

    /* actions perform on activation of plugin*/
    public function wpa_install()
    {
    }

    /*actions perform on deactivation of plugin*/
    public function wpa_uninstall()
    {
    }
}

new ArtMoi_WP();