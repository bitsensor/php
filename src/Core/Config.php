<?php

namespace BitSensor\Core;


/**
 * Configuration for the BitSensor Web Application Security plugin.
 * @package BitSensor\Core
 */
class Config
{

    /**
     * The BitSensor server to connect to.
     *
     * <i>Required</i>
     */
    const URI = 'uri';
    /**
     * Your BitSensor username.
     *
     * <i>Required</i>
     */
    const USER = 'user';
    /**
     * Your BitSensor API key.
     *
     * <i>Required</i>
     */
    const API_KEY = 'apiKey';
    /**
     * Running mode.
     *
     * <i>Defaults to {@link BitSensor\Core\Config::MODE_ON Config::MODE_ON}.</i>
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
     *
     * <i>Defaults to {@link BitSensor\Core\Config::ACTION_BLOCK Config::ACTION_BLOCK}.</i>
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
     *
     * <i>Defaults to {@link Config::IP_ADDRESS_REMOTE_ADDR}.</i>
     */
    const IP_ADDRESS_SRC = 'ipAddressSrc';
    /**
     * Manual IP address.
     *
     * <i>Optional. Only required when {@link BitSensor\Core\Config::IP_ADDRESS_SRC Config::IP_ADDRESS_SRC} is set to {@link BitSensor\Core\Config::IP_ADDRESS_MANUAL Config::IP_ADDRESS_MANUAL}.</i>
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
     * Source of the host address of the server.
     *
     * <i>Defaults to {@link Config::HOST_SERVER_NAME}.</i>
     */
    const HOST_SRC = 'hostSrc';
    /**
     * Manual host header.
     *
     * <i>Optional. Only required when {@link Config::HOST_SRC} is set to {@link Config::HOST_MANUAL}.</i>
     */
    const HOST = 'host';
    /**
     * Set host header manually.
     */
    const HOST_MANUAL = 'manual';
    /**
     * Set host according to <code>$_SERVER['SERVER_NAME']</code>.
     */
    const HOST_SERVER_NAME = 'serverName';
    /**
     * Set IP address according to the <code>host</code> HTTP header.
     */
    const HOST_HOST_HEADER = 'hostHeader';
    /**
     * Set the log level.
     *
     * <i>Defaults to {@link BitSensor\Core\Config::LOG_LEVEL_ALL Config::LOG_LEVEL_ALL}.</i>
     */
    const LOG_LEVEL = 'logLevel';
    /**
     * Log everything.
     */
    const LOG_LEVEL_ALL = 'all';
    /**
     * Log nothing.
     */
    const LOG_LEVEL_NONE = 'none';
    /**
     * Output flushing. Turning this on allows the browser to render the page while BitSensor is still working in the background.
     *
     * <i>Defaults to {@link BitSensor\Core\Config::OUTPUT_FLUSHING_OFF Config::OUTPUT_FLUSHING_OFF}.</i>
     */
    const OUTPUT_FLUSHING = 'outputFlushing';
    /**
     * Use output flushing to reduce latency.
     */
    const OUTPUT_FLUSHING_ON = 'on';
    /**
     * Do not use output flushing.
     */
    const OUTPUT_FLUSHING_OFF = 'off';
    /**
     * Execute fastcgi_finish_request() in AfterRequestHandler. Turning this on allows the browser to render the page while BitSensor is still working in the background. A side effect is that output will not be flushed in shutdown_hooks that run after BitSensor.
     *
     * <i>Defaults to {@link BitSensor\Core\Config::OUTPUT_FLUSHING_OFF Config::OUTPUT_FLUSHING_OFF}.</i>
     */
    const EXECUTE_FASTCGI_FINISH_REQUEST = 'executeFastCgi';
    /**
     * Use fastcgi_finish_request flushing to reduce latency. A side effect is that output will not be flushed in shutdown_hooks that run after BitSensor.
     */
    const EXECUTE_FASTCGI_FINISH_REQUEST_ON = 'on';
    /**
     * Do not use fastcgi_finish_request.
     */
    const EXECUTE_FASTCGI_FINISH_REQUEST_OFF = 'off';
    /**
     * Uopz Hooking. Turning this on enables BitSensor to hook into function calls.
     */
    const UOPZ_HOOK = 'uopzHook';
    /**
     * Use uopz hook.
     */
    const UOPZ_HOOK_ON = 'on';
    /**
     * Do not use uopz hook.
     */
    const UOPZ_HOOK_OFF = 'off';

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
    private $mode = self::MODE_DETECTION;
    /**
     * Action to perform when the connection to the BitSensor servers is lost.
     *
     * @var string
     */
    private $connectionFail = self::ACTION_ALLOW;
    /**
     * Source of the IP address of the user.
     *
     * @var string
     */
    private $ipAddressSrc = self::IP_ADDRESS_REMOTE_ADDR;
    /**
     * Manual IP address.
     *
     * @var string
     */
    private $ipAddress;
    /**
     * Source of the host header.
     *
     * @var string
     */
    private $hostSrc = self::HOST_SERVER_NAME;
    /**
     * Manual host header.
     *
     * @var string
     */
    private $host;
    /**
     * Log level.
     *
     * @var string
     */
    private $logLevel = self::LOG_LEVEL_ALL;
    /**
     * Output flushing.
     *
     * @var string
     */
    private $outputFlushing = self::OUTPUT_FLUSHING_OFF;
    /**
     * Execute fastcgi_finish_request() in the AfterRequestHandler.
     *
     * @var string
     */
    private $executeFastcgiFinishRequest = self::EXECUTE_FASTCGI_FINISH_REQUEST_OFF;
    /**
     * Uopz Hook setting
     *
     * @var string
     */
    private $uopzHook = self::UOPZ_HOOK_OFF;

    /**
     * @param $json
     */
    public function __construct($json = null)
    {
        if ($json !== null) {
            $config = json_decode($json, true);

            if (array_key_exists(self::URI, $config)) {
                $this->setUri($config[self::URI]);
            }

            if (array_key_exists(self::USER, $config)) {
                $this->setUser($config[self::USER]);
            }

            if (array_key_exists(self::API_KEY, $config)) {
                $this->setApiKey($config[self::API_KEY]);
            }

            if (array_key_exists(self::MODE, $config)) {
                $this->setMode($config[self::MODE]);
            }

            if (array_key_exists(self::CONNECTION_FAIL, $config)) {
                $this->setConnectionFail($config[self::CONNECTION_FAIL]);
            }

            if (array_key_exists(self::IP_ADDRESS_SRC, $config)) {
                $this->setIpAddressSrc($config[self::IP_ADDRESS_SRC]);
            }

            if (array_key_exists(self::IP_ADDRESS, $config)) {
                $this->setIpAddress($config[self::IP_ADDRESS]);
            }

            if (array_key_exists(self::HOST_SRC, $config)) {
                $this->setHostSrc($config[self::HOST_SRC]);
            }

            if (array_key_exists(self::HOST, $config)) {
                $this->setHost($config[self::HOST]);
            }

            if (array_key_exists(self::LOG_LEVEL, $config)) {
                $this->setLogLevel($config[self::LOG_LEVEL]);
            }

            if (array_key_exists(self::OUTPUT_FLUSHING, $config)) {
                $this->setOutputFlushing($config[self::OUTPUT_FLUSHING]);
            }

            if (array_key_exists(self::EXECUTE_FASTCGI_FINISH_REQUEST, $config)) {
                $this->setFastcgiFinishRequest($config[self::EXECUTE_FASTCGI_FINISH_REQUEST]);
            }

            if (array_key_exists(self::UOPZ_HOOK, $config)) {
                $this->setUopzHook($config[self::UOPZ_HOOK]);
            }
        }
    }

    /**
     * @return string The BitSensor server to connect to.
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param string $uri The BitSensor server to connect to.
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     * @return string Your BitSensor username.
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string $user Your BitSensor username.
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return string Your BitSensor API key.
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey Your BitSensor API key.
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return string Running mode.
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param string $mode Running mode.
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    /**
     * @return string Action to perform when the connection to the BitSensor servers is lost.
     */
    public function getConnectionFail()
    {
        return $this->connectionFail;
    }

    /**
     * @param string $connectionFail Action to perform when the connection to the BitSensor servers is lost.
     */
    public function setConnectionFail($connectionFail)
    {
        $this->connectionFail = $connectionFail;
    }

    /**
     * @return string Source of the IP address of the user.
     */
    public function getIpAddressSrc()
    {
        return $this->ipAddressSrc;
    }

    /**
     * @param string $ipAddressSrc Source of the IP address of the user.
     */
    public function setIpAddressSrc($ipAddressSrc)
    {
        $this->ipAddressSrc = $ipAddressSrc;
    }

    /**
     * @return string Manual IP address.
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * @param string $ipAddress Manual IP address.
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;
    }

    /**
     * @return string Source of the server host.
     */
    public function getHostSrc()
    {
        return $this->hostSrc;
    }

    /**
     * @param string $ipAddressSrc Source of the server host.
     */
    public function setHostSrc($hostSrc)
    {
        $this->hostSrc = $hostSrc;
    }

    /**
     * @return string Manual host.
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $ipAddress Manual host.
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * @return string Log level.
     */
    public function getLogLevel()
    {
        return $this->logLevel;
    }

    /**
     * @param string $logLevel Log level.
     */
    public function setLogLevel($logLevel)
    {
        $this->logLevel = $logLevel;
    }

    /**
     * @return string Output flushing.
     */
    public function getOutputFlushing()
    {
        return $this->outputFlushing;
    }

    /**
     * @param string $outputFlushing Output flushing.
     */
    public function setOutputFlushing($outputFlushing)
    {
        $this->outputFlushing = $outputFlushing;
    }

    /**
     * @return string Execute fastcgi finish request.
     */
    public function getFastcgiFinishRequest()
    {
        return $this->executeFastcgiFinishRequest;
    }

    /**
     * @param string $executeFastcgiFinishRequest Execute fastcgi finish request.
     */
    public function setFastcgiFinishRequest($executeFastcgiFinishRequest)
    {
        if ($executeFastcgiFinishRequest == self::EXECUTE_FASTCGI_FINISH_REQUEST_ON) {
            if (!function_exists('fastcgi_finish_request')) {
                trigger_error("fastcgi is not available, however you wanted to enable it in the BitSensor configuration. Please install fastcgi or disable " . self::EXECUTE_FASTCGI_FINISH_REQUEST, E_USER_WARNING);
                $executeFastcgiFinishRequest = self::EXECUTE_FASTCGI_FINISH_REQUEST_OFF;
            }
        }

        $this->executeFastcgiFinishRequest = $executeFastcgiFinishRequest;
    }

    /**
     * @return string
     */
    public function getUopzHook()
    {
        return $this->uopzHook;
    }

    /**
     * @param string $uopzHook
     */
    public function setUopzHook($uopzHook)
    {
        $this->uopzHook = $uopzHook;
    }
}
