<?php

/**
 * Class Artmoi_Response
 *
 * Response object for calls to the ArtMoi API
 */
class Artmoi_Response
{
    /**
     * @var bool
     */
    public $error = false;
    /**
     * @var bool
     */
    public $success = false;
    /**
     * @var bool
     */
    public $results = false;


    /**
     * @param null $json
     */
    public function __construct( $json = NULL )
    {
        $data = json_decode($json);

        if( $data->error ){
            $this->error($data->error);
        }

        if( $data->success ){
            $this->success($data->success);
        }

        if( $data->results ){
            $this->results($data->results);
        }

    }

    /**
     * @param null $value
     * @return null
     */
    public function error( $value = NULL )
    {
        if( ! is_null($value) ) {
            $this->error = $value;
        }
        return $this->error;
    }

    /**
     * @param null $value
     * @return null
     */
    public function success( $value = NULL )
    {
        if( ! is_null($value) ) {
            $this->success = $value;
        }
        return $this->success;
    }

    /**
     * @param null $objects
     * @param null $withPrivacy
     * @return array|null
     */
    public function results( $objects = NULL )
    {

        if( ! is_null($objects) ) {
            $this->results = $objects;
        }
        return $this->results;
    }

    /**
     * Returns the first value from the results set
     *
     * @return object|bool
     */
    public function first()
    {
        if( is_array($this->results()) and count($this->results()) > 0 ){
            return $this->results()[0];
        }
        else if($this->results() and ! is_array($this->results()) )
        {
            return $this->results();
        }

        return false;
    }

}