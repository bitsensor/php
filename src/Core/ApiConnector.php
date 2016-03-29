<?php

namespace BitSensor\Core;

use BitSensor\Exception\ApiException;
use BitSensor\Util\Log;


/**
 * Handles the connection with the BitSensor servers.
 * @package BitSensor\Core
 */
class ApiConnector {

    /**
     * Authorize the connecting user.
     */
    const ACTION_AUTHORIZE = 'authorize';
    /**
     * Log the current request and errors.
     */
    const ACTION_LOG = 'log';

    /**
     * Action executed successfully.
     */
    const RESPONSE_OK = 'ok';
    /**
     * Allow the connecting user.
     */
    const RESPONSE_ALLOW = 'allow';
    /**
     * Block the connecting user.
     */
    const RESPONSE_BLOCK = 'block';
    /**
     * The API key is invalid or does not have sufficient permission to execute the action.
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
     * @param string $action The action to post to the server. Possible values:
     * {@link ApiConnector::$ACTION_AUTHORIZE},
     * {@link ApiConnector::$ACTION_LOG}
     * @return ApiConnector
     */
    public function post($action) {
        $this->setAction($action);
        return $this;
    }

    /**
     * @param string $data The data to send as a JSON encoded object.
     */
    public function setData($data) {
        $this->data = $data;
    }

    /**
     * @param string $uri The server to send the data to.
     */
    public function setUri($uri) {
        $this->uri = $uri;
    }

    /**
     * @param string $user The ID of the user.
     */
    public function setUser($user) {
        $this->user = $user;
    }

    /**
     * @param string $apiKey The API key used to authenticate with the BitSensor servers.
     */
    public function setApiKey($apiKey) {
        $this->apiKey = $apiKey;
    }

    /**
     * @param string $action The action to post to the server. Possible values:
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

        $this->data[MetaContext::NAME] = array(
            MetaContext::USER => $this->user,
            MetaContext::API_KEY => $this->apiKey,
            MetaContext::PROVIDER => MetaContext::PROVIDER_PHP,
            MetaContext::PROVIDER_VERSION => BitSensor::VERSION
        );

        $json = json_encode($this->data);

        Log::d('<pre>' . json_encode($this->data, defined ("JSON_PRETTY_PRINT") ? JSON_PRETTY_PRINT : 0) . '</pre>');

        $fp = fopen(dirname(__DIR__) . '/bitbrain.pem', 'r');
        $cert = fread($fp, 8192);
        fclose($fp);
        $pubKey = openssl_get_publickey($cert);

        openssl_seal($json, $encryptedJson, $eKeys, array($pubKey));

        openssl_free_key($pubKey);

        $content = json_encode(array(
            MetaContext::DATA => base64_encode($encryptedJson),
            MetaContext::ENCRYPTION => true,
            MetaContext::ENCRYPTION_KEY => base64_encode($eKeys[0])
        ));

        Log::d('<pre>' . $content . '</pre>');

        // Create cURL handle
        $ch = curl_init($this->uri . '/' . $this->action);
        curl_setopt_array($ch, array(
            CURLOPT_CUSTOMREQUEST => self::getRequestType($this->action),
            CURLOPT_POSTFIELDS => $content,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($content)
            ),
            CURLOPT_TCP_NODELAY => true,
            CURLOPT_TIMEOUT_MS => 200,
            CURLOPT_NOSIGNAL => true
        ));

        // Send data
        $result = curl_exec($ch);
        $curl_errno = curl_errno($ch);
        $curl_error = curl_error($ch);

        if ($curl_errno > 0) {
            throw new ApiException("Server connection failed! $curl_error Code: $curl_errno", ApiException::CONNECTION_FAILED);
        }

        return $result;
    }

    /**
     * Maps an action to a HTTP request type.
     *
     * @param string $action See {@link ApiConnector::setAction()}
     * @return string HTTP request type
     *
     * @see ApiConnector::setAction()
     */
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
