<?php

class Artmoi_Controller
{


    public function settings()
    {

        // TODO: Check wordpress settings for artmoiwp_apikey

        $this->artmoiwp_apikey = get_option('artmoiwp_apikey');

        if($this->artmoiwp_apikey == null) {
            // If we are receiving a new apiKey via POST
            if ($_POST['apiKey']) {
                $apiKey = $_POST['apiKey'];
               // TODO: save apiKey to wordpress settings using name artmoiwp_apikey
                update_option('artmoiwp_apikey',$apiKey);

            }
        }else{
            $apiKey = $this->artmoiwp_apikey;
        }


        // Assign apiKey to view
        Flight::view()->set('apiKey', $apiKey);

        // Render the template
        Flight::render('admin/settings');
    }

    public function dashboard()
    {

        $results = $this->getRecentCreations(1, 30);
        $reports = $this->getUserReports();

        Flight::view()->set('reports', $reports);


        Flight::view()->set('gridTitle', __('Recent Creations'));

        Flight::render('admin/artworkGrid', array('artwork' => $results, 'reports' => $reports), 'grid');

        Flight::render('admin/dashboard');

    }



    public function getUserReports()
    {
        $artmoi = Flight::request();

        $artmoi->params('limit', 100);

        $controller = 'reports';

        $response = $artmoi->call($controller);


        return $response->results();
    }


    public function getRecentCreations( $page, $limit )
    {
        $artmoi = Flight::request();

        $artmoi->params('p', $page);
        $artmoi->params('limit', $limit);

        $controller = 'creation';
        $action = 'user';

        $response = $artmoi->call($controller, $action);

        return $response->results();
    }

}