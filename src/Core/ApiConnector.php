<?php

namespace BITsensor\Core;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

/**
 * Handles the connection with the BITsensor servers.
 * @package BITsensor\Core
 */
class ApiConnector {

    /**
     * @var string BITsensor server URI
     */
    private $uri;
    /**
     * @var array JSON array
     */
    private $data;
    /**
     * @var string API key
     */
    private $apiKey;

    /**
     * @param string $apiKey The API key used to authenticate with the BITsensor servers.
     * @return ApiConnector
     */
    public static function from($apiKey) {
        $connector = new self;
        $connector->setApiKey($apiKey);
        return $connector;
    }

    /**
     * @param string $data The data to send as a JSON encoded object.
     * @return ApiConnector
     */
    public function with($data) {
        $this->setData($data);
        return $this;
    }

    /**
     * @param string $uri The server to send the data to.
     * @return ApiConnector
     */
    public function to($uri) {
        $this->setUri($uri);
        return $this;
    }

    /**
     * @param string $data
     */
    public function setData($data) {
        $this->data = $data;
    }

    /**
     * @param string $uri
     */
    public function setUri($uri) {
        $this->uri = $uri;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey($apiKey) {
        $this->apiKey = $apiKey;
    }


    /**
     * Sends the data to the server.
     */
    public function send() {
        $json = array(
            'key' => $this->apiKey,
            'data' => $this->data
        );


        $client = new Client([
            'base_uri' => $this->uri
        ]);

        try {
            $response = $client->post('index.php', [
                'json' => $json
            ]);

            $response->getBody();
        } catch (ClientException $e) {
            // TODO: Handle data not send
        }
    }

}