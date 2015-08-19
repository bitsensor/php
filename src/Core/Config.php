<?php

namespace BitSensor\Core;


/**
 * Configuration for the BitSensor Web Application Security plugin.
 * @package BitSensor\Core
 */
class Config {

    /**
     * The BitSensor server to connect to.
     */
    const URI = 'uri';
    /**
     * Your BitSensor username.
     */
    const USER = 'user';
    /**
     * Your BitSensor API key.
     */
    const API_KEY = 'apiKey';
    /**
     * Running mode.
     */
    const MODE = 'mode';
    /**
     * Only do detection but don't block attackers.
     */
    const MODE_DETECTION = 'detection';
    /**
     * Do detection and block attackers.
     */
    const MODE_ON = 'on';
    /**
     * Action to perform when the connection to the BitSensor servers is lost.
     */
    const CONNECTION_FAIL = 'connectionFail';
    /**
     * Allow the user to connect.
     */
    const ACTION_ALLOW = 'allow';
    /**
     * Block the user.
     */
    const ACTION_BLOCK = 'block';

    /**
     * The BitSensor server to connect to.
     *
     * @var string
     */
    private $uri;
    /**
     * Your BitSensor username.
     *
     * @var string
     */
    private $user;
    /**
     * Your BitSensor API key.
     *
     * @var string
     */
    private $apiKey;
    /**
     * Running mode.
     *
     * @var string
     */
    private $mode;
    /**
     * Action to perform when the connection to the BitSensor servers is lost.
     *
     * @var string
     */
    private $connectionFail;

    public function __construct($json) {
        $config = json_decode($json, true);

        if (isset($config[self::URI])) {
            $this->setUri($config[self::URI]);
        }

        if (isset($config[self::USER])) {
            $this->setUser($config[self::USER]);
        };

        if (isset($config[self::API_KEY])) {
            $this->setApiKey($config[self::API_KEY]);
        }

        if (isset($config[self::MODE])) {
            $this->setMode($config[self::MODE]);
        }

        if (isset($config[self::CONNECTION_FAIL])) {
            $this->setConnectionFail($config[self::CONNECTION_FAIL]);
        }
    }

    /**
     * @return string The BitSensor server to connect to.
     */
    public function getUri() {
        return $this->uri;
    }

    /**
     * @param string $uri The BitSensor server to connect to.
     */
    public function setUri($uri) {
        $this->uri = $uri;
    }

    /**
     * @return string Your BitSensor username.
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * @param string $user Your BitSensor username.
     */
    public function setUser($user) {
        $this->user = $user;
    }

    /**
     * @return string Your BitSensor API key.
     */
    public function getApiKey() {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey Your BitSensor API key.
     */
    public function setApiKey($apiKey) {
        $this->apiKey = $apiKey;
    }

    /**
     * @return string Running mode.
     */
    public function getMode() {
        return $this->mode;
    }

    /**
     * @param string $mode Running mode.
     */
    public function setMode($mode) {
        $this->mode = $mode;
    }

    /**
     * @return string Action to perform when the connection to the BitSensor servers is lost.
     */
    public function getConnectionFail() {
        return $this->connectionFail;
    }

    /**
     * @param string $connectionFail Action to perform when the connection to the BitSensor servers is lost.
     */
    public function setConnectionFail($connectionFail) {
        $this->connectionFail = $connectionFail;
    }

}