<?php

namespace BitSensor\Core;


use Guzzle\Http\Client;
use Guzzle\Http\Exception\RequestException;

/**
 * Handles the connection with the BitSensor servers.
 * @package BitSensor\Core
 */
class ApiConnector {

    /**
     * @var string BitSensor server URI
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
     * @param string $apiKey The API key used to authenticate with the BitSensor servers.
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


        $client = new Client($this->uri);

        try {
            $response = $client->post('index.php', array(
                'Content-Type' => 'application/json'
            ), json_encode($json))->send();

            $response->getBody();
        } catch (RequestException $e) {
            // TODO: Handle data not send
        }
    }

}