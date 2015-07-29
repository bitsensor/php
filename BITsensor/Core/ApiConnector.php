<?php

namespace BITsensor\Core;


/**
 * Handles the connection with the BITsensor servers.
 * @package BITsensor\Core
 */
class ApiConnector {

    private $url = 'https://localhost/api/';
    /**
     * @var string JSON encoded string
     */
    private $data;
    private $apiKey = 'abcdefghijklmnopqrstuvwxyz';

    /**
     * @param string $data The data to send as a JSON encoded object.
     * @return $this
     */
    public static function with($data) {
        $connector = new self;
        $connector->setData($data);
        return $connector;
    }

    /**
     * @param string $data
     */
    public function setData($data) {
        $this->data = $data;
    }

    /**
     * Sends the data to the server.
     */
    public function send() {
        $json = array(
            'key' => $this->apiKey,
            'data' => $this->data
        );

        echo json_encode($json);
    }

}