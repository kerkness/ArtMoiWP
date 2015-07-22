<?php

class Artmoi_Controller
{
    public function settings()
    {

        // TODO: Check wordpress settings for artmoiwp_apikey
        //$apiKey =

        // If we are receiving a new apiKey via POST
        if( $_POST['apiKey'] )
        {
            $apiKey = $_POST['apiKey'];

            // TODO: save apiKey to wordpress settings using name artmoiwp_apikey
        }

        // Assign apiKey to view
        Flight::view()->set('apiKey', $apiKey);

        // Render the template
        Flight::render('admin/settings');

    }

    public function dashboard()
    {

        $results = $this->callExample(1, 20);


        Flight::render('admin/artworkGrid', array('artwork' => $results), 'grid');


        Flight::render('admin/dashboard');

    }


    // EXAMPLE ..
    public function callExample( $page, $limit )
    {
        $artmoi = Flight::artmoi();

        $artmoi->params('page', $page);
        $artmoi->params('limit', $limit);

        $controller = 'creation';
        $action = 'recent';

        $response = $artmoi->call($controller, $action);

        return $response->results();
    }

}