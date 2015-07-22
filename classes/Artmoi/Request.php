<?php


/**
 * Class ArtMoi_Request
 *
 */
class ArtMoi_Request{

    /**
     * @var
     */
    public $apiKey;
    /**
     * @var
     */
    public $email;
    /**
     * @var
     */
    public $password;
    /**
     * @var
     */
    public $response;

    /**
     * @var string
     */
    public $baseURI = 'https://api.artmoi.me';

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

    /**
     * @param $apiKey
     * @param $email
     * @param $password
     */
    //public function __construct($apiKey, $email, $password)
    /**
     *
     */
    public function __construct()
    {
        // TODO: load the api key from wp-settings
        $this->apiKey = $apiKey;

        // TODO: will probably not need these properties as authorization is provided with the apikey
        $this->email = $email;
        $this->password = $password;

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

            // Add the apiKey to the params
            $this->params('apiKey', $this->apiKey);

            // Use the wordpress remote get and remote body methods
            $json = wp_remote_retrieve_body( wp_remote_post( $uri, $this->params() ) );

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