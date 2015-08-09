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
    /**
     * @var string
     */
    const ACTION_LOG = 'log';

    /**
     * @var string
     */
    const RESPONSE_OK = 'ok';
    /**
     * @var string
     */
    const RESPONSE_ALLOW = 'allow';
    /**
     * @var string
     */
    const RESPONSE_BLOCK = 'block';
    /**
     * @var string
     */
    const RESPONSE_ACCESS_DENIED = 'access_denied';

    /**
     * @var string BitSensor server URI
     */
    private $uri;
    /**
     * @var array JSON array
     */
    private $data;
    /**
     * @var string User ID
     */
    private $user;
    /**
     * @var string API key
     */
    private $apiKey;
    /**
     * @var string Action
     */
    private $action;

    /**
     * @param string $user The ID of the user.
     * @param string $apiKey The API key used to authenticate with the BitSensor servers.
     * @return ApiConnector
     */
    public static function from($user, $apiKey) {
        $connector = new self;
        $connector->setUser($user);
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
     * @param string $user
     */
    public function setUser($user) {
        $this->user = $user;
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
        $json = json_encode($this->data);

        $signature = hash_hmac('sha256', $json, $this->apiKey);

        $ch = curl_init($this->uri . '/' . $this->action . '/?user=' . $this->user . '&sig=' . $signature);
        curl_setopt_array($ch, array(
            CURLOPT_CUSTOMREQUEST => self::getRequestType($this->action),
            CURLOPT_POSTFIELDS => $json,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($json)
            ),
            CURLOPT_TCP_NODELAY => true,
            CURLOPT_TIMEOUT_MS => 200
        ));

        $result = curl_exec($ch);

        if ($result === false) {
            throw new ApiException('Server connection failed!', ApiException::CONNECTION_FAILED);
        }

        return $result;
    }

    private static function getRequestType($action) {
        switch ($action) {
            case ApiConnector::ACTION_AUTHORIZE:
                return 'POST';
            case ApiConnector::ACTION_LOG:
                return 'POST';
            default:
                return 'GET';
        }
    }

}