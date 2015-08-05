<?php

namespace BitSensor\Core;
use BitSensor\Exception\ApiException;


/**
 * Handles the connection with the BitSensor servers.
 * @package BitSensor\Core
 */
class ApiConnector {

    /**
     * @var string
     */
    const ACTION_AUTHORIZE = 'authorize';
    const ACTION_LOG = 'log';

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
     * @var string Action
     */
    private $action;

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
     * @param string $action The  action to post to the server. Possible values:
     * {@link ApiConnector::$ACTION_AUTHORIZE},
     * {@link ApiConnector::$ACTION_LOG}
     * @return ApiConnector
     */
    public function post($action) {
        $this->setAction($action);
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
     * @param string $action The  action to post to the server. Possible values:
     * {@link ApiConnector::$ACTION_AUTHORIZE},
     * {@link ApiConnector::$ACTION_LOG}
     */
    public function setAction($action) {
        $this->action = $action;
    }

    /**
     * Sends the data to the server.
     *
     * @throws ApiException
     */
    public function send() {
        $data = array(
            'key' => $this->apiKey,
            'action' => $this->action,
            'data' => $this->data
        );

        $json = json_encode($data);

        $ch = curl_init($this->uri);
        curl_setopt_array($ch, array(
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $json,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($json)
            ),
            CURLOPT_TCP_NODELAY => true,
            CURLOPT_TIMEOUT => 1
        ));

        $result = curl_exec($ch);

        if ($result === false) {
            throw new ApiException('Server connection failed!', ApiException::CONNECTION_FAILED);
        }

        return $result;
    }

}