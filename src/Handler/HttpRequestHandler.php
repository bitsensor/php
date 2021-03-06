<?php

namespace BitSensor\Handler;

use BitSensor\Core\AuthenticationConstants;
use BitSensor\Core\EndpointConstants;
use BitSensor\Core\HttpConstants;
use BitSensor\Util\ServerInfo;
use Proto\Datapoint;

/**
 * Collects information about the HTTP request.
 * @package BitSensor\Handler
 */
class HttpRequestHandler extends AbstractHandler
{

    /**
     * Source of the host address of the server.
     *
     * One of
     *  - {@see HOST_SERVER_NAME}
     *  - {@see HOST_HOST_HEADER}
     *  - {@see HOST_MANUAL}
     *
     * Defaults to {@link HOST_SERVER_NAME}.
     */
    const HOST_SRC = 'hostSrc';
    /**
     * Set host according to <code>$_SERVER['SERVER_NAME']</code>.
     */
    const HOST_SERVER_NAME = 'serverName';
    /**
     * Set IP address according to the <code>host</code> HTTP header.
     */
    const HOST_HOST_HEADER = 'hostHeader';
    /**
     * Set host header manually.
     */
    const HOST_MANUAL = 'manual';

    /**
     * Manual host header.
     *
     * <i>Optional. Only required when {@link HOST_SRC} is set to {@link HOST_MANUAL}.</i>
     */
    const HOST = 'host';


    public static $hostSrc = self::HOST_SERVER_NAME;
    public static $host;

    /**
     * Configure the Handler. Automatically called in the constructor.
     *
     * @param string[] $config
     * @return mixed
     */
    public function configure($config)
    {
        parent::configure($config);

        if (array_key_exists(self::HOST_SRC, $config))
            self::$hostSrc = $config[self::HOST_SRC];

        if (array_key_exists(self::HOST, $config))
            self::$host = $config[self::HOST];
    }

    /**
     * @param Datapoint $datapoint
     */
    public function doHandle(Datapoint $datapoint)
    {
        $context = array(
            HttpConstants::SERVER_PROTOCOL => isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : null,
            HttpConstants::QUERY_STRING => isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : null,
            HttpConstants::HTTP_USER_AGENT => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null,
            HttpConstants::HTTP_REFERER => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null,
            HttpConstants::REQUEST_METHOD => isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : null,
            HttpConstants::HTTP_ACCEPT => isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : null,
            HttpConstants::HTTP_ACCEPT_CHARSET => isset($_SERVER['HTTP_ACCEPT_CHARSET']) ? $_SERVER['HTTP_ACCEPT_CHARSET'] : null,
            HttpConstants::HTTP_ACCEPT_ENCODING => isset($_SERVER['HTTP_ACCEPT_ENCODING']) ? $_SERVER['HTTP_ACCEPT_ENCODING'] : null,
            HttpConstants::HTTP_ACCEPT_LANGUAGE => isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : null,
            HttpConstants::PATH_INFO => isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : null,
            HttpConstants::HTTPS => ServerInfo::isHttps() ? 'true' : 'false',
        );

        foreach ($context as $k => $v) {
            if ($v !== null)
                $datapoint->getContext()[HttpConstants::NAME . '.' . $k] = $v;
        }

        $auth = array(
            AuthenticationConstants::PHP_AUTH_USER => isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : null,
            AuthenticationConstants::PHP_AUTH_PW => isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : null,
            AuthenticationConstants::AUTH_TYPE => isset($_SERVER['AUTH_TYPE']) ? $_SERVER['AUTH_TYPE'] : null,
            AuthenticationConstants::REMOTE_USER => isset($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER'] : null
        );

        foreach ($auth as $k => $v) {
            if ($v !== null) {
                $datapoint->getContext()[AuthenticationConstants::NAME . '.' . $k] = $v;
            }
        }

        $micro_date = microtime();
        $date_array = explode(' ', $micro_date);
        $date = date('Y-m-d\TH:i:s', $date_array[1]);
        $time = substr($date_array[0], 1);
        $timezone = date('O', $date_array[1]);

        $endpoint = array(
            EndpointConstants::SERVER_ADDR => isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : null,
            EndpointConstants::SERVER_NAME => isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : null,
            EndpointConstants::SERVER_SOFTWARE => isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : null,
            EndpointConstants::SERVER_SIGNATURE => isset($_SERVER['SERVER_SIGNATURE']) ? $_SERVER['SERVER_SIGNATURE'] : null,
            EndpointConstants::SERVER_PORT => isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : null,
            EndpointConstants::DOCUMENT_ROOT => isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : null,
            EndpointConstants::GATEWAY_INTERFACE => isset($_SERVER['GATEWAY_INTERFACE']) ? $_SERVER['GATEWAY_INTERFACE'] : null,
            EndpointConstants::SCRIPT_FILENAME => isset($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME'] : null,
            EndpointConstants::REQUEST_TIME => $date . $time . $timezone,
            EndpointConstants::REQUEST_URI => isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null
        );

        $host = null;

        switch (self::$hostSrc) {
            case self::HOST_SERVER_NAME:
                $host = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : null;
                break;
            case self::HOST_HOST_HEADER:
                $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null;
                break;
            case self::HOST_MANUAL:
                $host = self::$host;
                break;
        }

        if ($host !== null) {
            $endpoint[EndpointConstants::SERVER_NAME] = $host;
        }

        if (function_exists('http_response_code')) {
            $endpoint[EndpointConstants::STATUS] = http_response_code();
        }

        foreach ($endpoint as $k => $v) {
            if ($v !== null) {
                $datapoint->getEndpoint()[$k] = $v;
            }
        }
    }
}
