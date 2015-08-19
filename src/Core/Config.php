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
     * Source of the IP address of the user.
     */
    const IP_ADDRESS_SRC = 'ipAddressSrc';
    /**
     * Manual IP address.
     */
    const IP_ADDRESS = 'ipAddress';
    /**
     * Set IP address manually.
     */
    const IP_ADDRESS_MANUAL = 'manual';
    /**
     * Set IP address according to <code>$_SERVER['REMOTE_ADDR']</code>.
     */
    const IP_ADDRESS_REMOTE_ADDR = 'remoteAddr';
    /**
     * Set IP address according to the <code>X-Forwarded-For</code> HTTP header.
     */
    const IP_ADDRESS_X_FORWARDED_FOR = 'forwardedFor';

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
    /**
     * Source of the IP address of the user.
     *
     * @var string
     */
    private $ipAddressSrc;
    /**
     * Manual IP address.
     *
     * @var string
     */
    private $ipAddress;

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

        if (isset($config[self::IP_ADDRESS_SRC])) {
            $this->setIpAddressSrc($config[self::IP_ADDRESS_SRC]);
        }

        if (isset($config[self::IP_ADDRESS])) {
            $this->setIpAddress($config[self::IP_ADDRESS]);
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

    /**
     * @return string Source of the IP address of the user.
     */
    public function getIpAddressSrc() {
        return $this->ipAddressSrc;
    }

    /**
     * @param string $ipAddressSrc Source of the IP address of the user.
     */
    public function setIpAddressSrc($ipAddressSrc) {
        $this->ipAddressSrc = $ipAddressSrc;
    }

    /**
     * @return string Manual IP address.
     */
    public function getIpAddress() {
        return $this->ipAddress;
    }

    /**
     * @param string $ipAddress Manual IP address.
     */
    public function setIpAddress($ipAddress) {
        $this->ipAddress = $ipAddress;
    }
}