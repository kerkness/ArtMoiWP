<?php

// Require flight
require dirname(__FILE__).'/vendor/flight/flight/Flight.php';

// Set the search path for flight registered classes
Flight::path( dirname(__FILE__).'/classes');

// Set the view folder for flight
Flight::set('flight.views.path',  dirname(__FILE__).'/views');

// Register classes with flight
Flight::register('artmoi', 'Artmoi_Request');
Flight::register('controller', 'Artmoi_Controller');



class ArtMoi_WP{
    //constructor
    function __construct(){


        add_action('admin_menu', array($this, 'wpa_add_menu'));

        wp_enqueue_style('wp-artmoi-style',plugins_url('css/wp-artmoi-style.css',__FILE__));
        register_activation_hook( __FILE__, array($this, 'wpa_install'));
        register_deactivation_hook(__FILE__, array($this, 'wpa_uninstall'));

    }

    /* actions perform at loading of admin menu*/
    function wpa_add_menu(){
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
    function wpa_page_file_path(){
        $screen = get_current_screen();

        if(strpos($screen->base, 'ArtMoi-settings') !== false){

            Flight::controller()->settings();

            //include(dirname(__FILE__) . '/includes/ArtMoi-settings.php');
        }
        else{

            Flight::controller()->dashboard();

            //include(dirname(__FILE__) . '/includes/ArtMoi-dashboard.php');
        }
    }


    /* actions perform on activation of plugin*/
    function wpa_install(){

    }

    /*actions perform on deactivation of plugin*/
    function wpa_uninstall(){


    }



}


new ArtMoi_WP();
