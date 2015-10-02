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
Flight::register('artmoi', 'Artmoi_Request');
Flight::register('response','Artmoi_response');
Flight::register('controller', 'Artmoi_Controller');
Flight::register('item', 'Artmoi_Item');



class ArtMoi_WP
{
    /**
     * constructor
     */
    public function __construct()
    {
        // Load admin menu and pages
        add_action('admin_menu', array($this, 'load_admin_menu'));

        // Add admin scripts
        add_action('admin_enqueue_scripts',array($this, 'add_style_into_admin'));

        // Register setting options
        add_action('admin_init', array($this, 'register_artmoi_settings'));

        // Unlimit the number of images on a page or post
        add_action('pre_get_posts', array($this, 'no_limit_posts'));

        // Set the ajax call for creating the media file
        add_action('wp_ajax_sync_items', array($this, 'sync_items'));

        // Add a meta box
        add_action('add_meta_boxes', array($this, 'add_custom_meta'));

        // Add a hook to save_post
        add_action('save_post', array($this, 'save_meta'));

        // Load css into the fornt-end
        add_action('wp_enqueue_scripts',array($this,'add_style'));

        // Create Short Codes to load a real time list of items from ArtMoi
        add_shortcode('am_items', array($this, 'shortcode_items'));

        // Create Short Code for displaying an Alphabetical Menu
        add_shortcode('am_menu_alpha', array($this, 'shortcode_menu_alpha'));

        // Create Short Code for displaying an Alphabetical Menu
        add_shortcode('am_menu_date', array($this, 'shortcode_menu_date'));

        // Add the_content filter
        add_filter('the_content', array($this, 'get_the_content'));

        // Install Hooks.
        register_activation_hook(__FILE__, array($this, 'install_artmoi_plugin'));

        // Uninstall Hook
        register_deactivation_hook(__FILE__, array($this, 'uninstall_artmoi_plugin'));
    }

    /**
     * Load admin menu & sub menu
     */
    public function load_admin_menu()
    {
        add_menu_page('ArtMoi', 'ArtMoi', 'manage_options', 'artmoi-lists', array(__CLASS__, 'load_menu_pages'), plugins_url('images/ArtMoi-Logo.png', __FILE__), '1.2.0');
        add_submenu_page('artmoi-lists', 'ArtMoi' . ' Lists', ' Lists', 'manage_options', 'artmoi-lists', array(__CLASS__, 'load_menu_pages'));
        add_submenu_page('artmoi-lists', 'ArtMoi' . ' Settings', 'Settings', 'manage_options', 'artmoi-settings', array(__CLASS__, 'load_menu_pages'));

        // This view-report page will not in admin menu
        add_submenu_page(null, 'ArtMoi' . ' Report Details', 'Report Details', 'manage_options', 'artmoi-view-items', array(__CLASS__, 'load_menu_pages'));
    }

    /**
     * Load scripts into admin page
     */
    public function add_style_into_admin()
    {
        // Load bootstrap scripts
        wp_enqueue_script('admin_js_bootstrap_hack', plugins_url('ArtMoiWP/scripts/bootstrap-hack.js'), array('jquery'));
        wp_enqueue_script('admin_js_bootstrap', plugins_url('ArtMoiWP/scripts/bootstrap.js'), array('jquery'));
    }


    /**
     * Load menu pages
     */
    public function load_menu_pages()
    {
        Flight::controller()->before();

        $screen = get_current_screen();
        $isApikey = get_option('artmoiwp_apikey');

        // Stay on the setting page until a user enters the api key
        if(is_null($isApikey) || $isApikey == "")
        {
            Flight::controller()->settings();
        }
        else
        {
            if(strpos($screen->base, 'artmoi-settings') !== false)
            {
                // Load the setting page
                Flight::controller()->settings();
            }
            else if(strpos($screen->base, 'artmoi-view-items') !== false)
            {
                // Load the viewItems page
                Flight::controller()->viewItems();
            }
            else if(strpos($screen->base, 'artmoi-lists') !== false){
                // Load the list page
                Flight::controller()->lists();
            }
        }
    }

    /**
     * Register ArtMoi options
     */
    public function register_artmoi_settings()
    {
        register_setting('artmoiwp_apikey','artmoiwp_apikey');
        register_setting('artmoiwp_syncedReports','artmoiwp_syncedReports');
        register_setting('artmoiwp_syncedCollections','artmoiwp_syncedCollections');
        register_setting('artmoiwp_allitems','artmoiwp_allitems');
    }

    public function no_limit_posts($query)
    {
        update_option('posts_per_page', '-1');
        update_option('page_for_posts','-1');
    }

    public function shortcode_items( $atts )
    {
        $atts = shortcode_atts(
            array(
                'limit' => 30,
                'orderby' => 'createdAt',
                'orderdir' => 'descending',
                'daterange' => '',
            ), $atts, 'am_items' );


        $controller = Flight::controller();

        // Pull updated attributes from query
        foreach($atts as $key => $value)
        {
            if( isset($_GET[$key]) ){
                $atts[$key] = $_GET[$key];
            }
        }

        //error_log("Loading wordpres PostID $postId");

        return $controller->getItems($atts);

        //return "Shortcode ITems";
    }

    public function shortcode_menu_alpha( $atts )
    {
        $atts = shortcode_atts(
            array(
                'range_start' => 'a',
                'range_end' => 'z',
            ), $atts, 'am_menu_alpha' );

        return Flight::view()->render('frontend/menu/alpha', array('atts' => $atts));
    }

    public function shortcode_menu_date( $atts )
    {
        wp_reset_postdata();
        global $post;
        $link = get_permalink( $post->ID );

        $atts = shortcode_atts(
            array(
                'range_start' => '1990',
                'range_end' => '2015',
                'groupby' => 0,
            ), $atts, 'am_menu_date' );

        $dates = array();

        if( $atts['groupby'] )
        {
            $groupby = $atts['groupby'];
            $start = $atts['range_start'];
            $end = $atts['range_end'];

            $last = $start;

            while($last <= $end)
            {
                $set_start = $last;
                $set_end = $last + $groupby;

                if( $set_end > $end )
                {
                    $set_end = $end;
                }

                $dates[] = $set_start.' - '.$set_end;
                //$dates[] = $set_end;

                $last = $set_end + 1;
            }
        }
        else
        {
            $dates = range($atts['range_start'], $atts['range_end']);
        }


        return Flight::view()->render('frontend/menu/date', array('dates' => $dates, 'link'=>$link));
    }

    /**
     * Sync selected items
     */
    public function sync_items()
    {
//        Flight::controller()->before();
        Flight::controller()->syncCreation( $_POST );
    }

    /**
     * Save meta values from input
     * @param $post_id
     */
    public function save_meta($postId)
    {
        Flight::controller()->saveOrDeleteMetaValue($postId);
        $detail = Flight::controller()->searchData($postId,"detail");

        // Call AddTag only when in post edit page
        $screen = get_current_screen();
        if(strpos($screen->post_type, 'post') !== false)
        {
            Flight::controller()->addTag($postId,$detail);
        }


    }

    /**
     * Add style and script into front-end
     */
    public function add_style()
    {
        // Load style into front-end
        wp_enqueue_style('bootstrap-style', plugins_url('css/bootstrap.min.css', __FILE__));

        wp_enqueue_style('artmoi-theme-style', plugins_url('css/template.css', __FILE__));

        // Load script into front-end
        wp_enqueue_script('admin_js_bootstrap', plugins_url('ArtMoiWP/scripts/bootstrap.js'), array('jquery'));
    }

    /**
     * Get the selected report or collection item image urls and
     * Update a post or page with them
     * @param $theContent
     * @return mixed
     */
    public function get_the_content( $theContent )
    {
        global $post;
        $postId = $post->ID; // the post ID in edit , not published

        $thumbnail = Flight::controller()->searchData($postId, "thumbnail");
        $image = Flight::controller()->searchData($postId, "image");
        $detail = Flight::controller()->searchData($postId, "detail");

        // Format the results into an array of Artmoi_Item objects
        $items = array();

        for( $i=0; $i < count($detail); $i ++ )
        {
            $items[] = Artmoi_Item::buildFromMeta($detail[$i], $image[$i], $thumbnail[$i]);
        }

//        return Flight::controller()->insertItems($postId, $theContent,  $detail, $image, $thumbnail);
        return Flight::controller()->insertItems($postId, $theContent,  $items);
    }

    /**
     * Create a custom meta box
     */
    public function add_custom_meta()
    {
       Flight::controller()->before();
       Flight::controller()->addCustomMeta();
    }

    /**
     * Actions perform on activation of ArtMoi plugin
     */
    public function install_artmoi_plugin()
    {
    }

    /**
     * Actions perform on deactivation of ArtMoi plugin
     */
    public function uninstall_artmoi_plugin()
    {
    }

}

new ArtMoi_WP();
