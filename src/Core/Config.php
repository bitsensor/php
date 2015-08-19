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


}