<?php


/**
 * Class ArtMoi_Request
 *
 * Request object
 */
class ArtMoi_Request{

    /**
     * @var string
     */
    public $apiKey;

    /**
     * TODO: This needs to change to https when we have an SSL certificate for api.artmoi.me enabled
     * @var string
     */
    public $baseURI = 'http://api.omona.me';

    /**
     * @var string
     */
    public $version = '1.0';

    /**
     * @var array
     */
    public $params = array();

    /**
     * @var Artmoi_Response
     */
    public $response;

    public function __construct()
    {
        $this->apiKey = get_option('artmoiwp_apikey');

        // Create the response object
        $this->response = new Artmoi_Response();
    }

    /**
     * Getter and Setter for Param values
     *
     * @param null $key
     * @param null $value
     * @return mixed    either returns the requested key/value pair or the entier set of params
     */
    public function params( $key = NULL, $value = NULL )
    {
        if( ! is_null($key) and is_null($value) ){
            return ( $this->params[$key] ) ? $this->params[$key]  : false;
        }

        if( ! is_null($key) and ! is_null($value) ){
            $this->params[$key] = $value;

            return $this;
        }

        return $this->params;
    }

    /**
     * @param $controller
     * @param string $action
     * @param null $id
     * @return Artmoi_Response
     */
    public function call( $controller, $action = 'index', $id = NULL )
    {
        if( ! $controller )
        {
            $this->response->error( __('You must provide a controller') );
        }
        else
        {
            // Record the  parameters for building the api call.
            $uriParts = array($this->baseURI, $this->version, $controller, $action);


            // Add id if provided
            if( $id ){
                $uriParts[] = $id;
            }

            // Build the uri
            $uri = implode('/', $uriParts);

            error_log("uri ".$uri);

            // Add the apiKey to the params
            $this->params('key', $this->apiKey);

            foreach( $this->params() as $k => $v )
            {
                error_log("calling api with param $k => $v");
            }

            // CURL Arguments
            $args = array(
                'method' => 'POST',
                'timeout' => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(),
                'body' => $this->params(),
                'cookies' => array()
            );


            // Use the wordpress remote post and remote body methods
            $json = wp_remote_retrieve_body( wp_remote_post( $uri, $args ) );

            $this->response = new Artmoi_Response( $json );
        }
        return $this->response();
    }

    /**
     * @return Artmoi_Response
     */
    public function response()
    {
        if( ! $this->response->error && ! $this->response->success )
        {
            $this->response->error('Incomplete request. Success or Error must be called.');
        }

        return $this->response;
    }

}