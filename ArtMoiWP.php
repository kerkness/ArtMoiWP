<?php

// Require flight
require 'vendor/flight/flight/Flight.php';

// Set the search path for flight registered classes
Flight::path('classes');

// Register classes with flight
Flight::register('artmoi', 'Artmoi_Request');

// Set the view folder for flight
Flight::set('flight.views.path', 'views');


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
            include (dirname(__FILE__). '/includes/ArtMoi-settings.php');
        }
        else{
            include (dirname(__FILE__). '/includes/ArtMoi-dashboard.php');
        }
    }


    /* actions perform on activation of plugin*/
    function wpa_install(){

    }

    /*actions perform on deactivation of plugin*/
    function wpa_uninstall(){


    }

    // EXAMPLE ..
    public function callExample( $page, $limit )
    {
        $artmoi = Flight::artmoi();

        $artmoi->params('page', $page);
        $artmoi->params('limit', $limit);

        $controller = 'user';
        $action = 'images';

        $response = $artmoi->call($controller, $action);

        Flight::view()->set('results', $response->results() );

        Flight::render('artworkGrid');

        //print_r($response);
    }


}

new ArtMoi_WP();
