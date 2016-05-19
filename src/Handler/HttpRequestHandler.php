<?php

namespace BitSensor\Handler;

use BitSensor\Core\AuthenticationContext;
use BitSensor\Core\Collector;
use BitSensor\Core\Config;
use BitSensor\Core\EndpointContext;
use BitSensor\Core\HttpContext;
use BitSensor\Util\ServerInfo;

/**
 * Collects information about the HTTP request.
 * @package BitSensor\Handler
 */
class HttpRequestHandler implements Handler {

    /**
     * @param Collector $collector
     * @param Config $config
     */
    public function handle(Collector $collector, Config $config) {
        $http = array(
            HttpContext::SERVER_PROTOCOL => isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : null,
            HttpContext::QUERY_STRING => isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : null,
            HttpContext::HTTP_USER_AGENT => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null,
            HttpContext::HTTP_REFERER => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null,
            HttpContext::REQUEST_METHOD => isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : null,
            HttpContext::HTTP_ACCEPT => isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : null,
            HttpContext::HTTP_ACCEPT_CHARSET => isset($_SERVER['HTTP_ACCEPT_CHARSET']) ? $_SERVER['HTTP_ACCEPT_CHARSET'] : null,
            HttpContext::HTTP_ACCEPT_ENCODING => isset($_SERVER['HTTP_ACCEPT_ENCODING']) ? $_SERVER['HTTP_ACCEPT_ENCODING'] : null,
            HttpContext::HTTP_ACCEPT_LANGUAGE => isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : null,
            HttpContext::PATH_INFO => isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : null,
            HttpContext::HTTPS => ServerInfo::isHttps(),
        );
        foreach ($http as $k => $v) {
            if ($v !== null) {
                $collector->addContext(new HttpContext($k, $v));
            }
        }

        $auth = array(
            AuthenticationContext::PHP_AUTH_USER => isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : null,
            AuthenticationContext::PHP_AUTH_PW => isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : null,
            AuthenticationContext::AUTH_TYPE => isset($_SERVER['AUTH_TYPE']) ? $_SERVER['AUTH_TYPE'] : null,
            AuthenticationContext::REMOTE_USER => isset($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER'] : null
        );

        foreach ($auth as $k => $v) {
            if ($v !== null) {
                $collector->addContext(new AuthenticationContext($k, $v));
            }
        }

        $micro_date = microtime();
        $date_array = explode(' ', $micro_date);
        $date = date('Y-m-d\TH:i:s', $date_array[1]);
        $time = substr($date_array[0], 1);
        $timezone = date('O', $date_array[1]);

        $endpoint = array(
            EndpointContext::SERVER_ADDR => isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : null,
            EndpointContext::SERVER_NAME => isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : null,
            EndpointContext::SERVER_SOFTWARE => isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : null,
            EndpointContext::SERVER_SIGNATURE => isset($_SERVER['SERVER_SIGNATURE']) ? $_SERVER['SERVER_SIGNATURE'] : null,
            EndpointContext::SERVER_PORT => isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : null,
            EndpointContext::DOCUMENT_ROOT => isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : null,
            EndpointContext::GATEWAY_INTERFACE => isset($_SERVER['GATEWAY_INTERFACE']) ? $_SERVER['GATEWAY_INTERFACE'] : null,
            EndpointContext::SCRIPT_FILENAME => isset($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME'] : null,
            EndpointContext::REQUEST_TIME => $date . $time . $timezone,
            EndpointContext::REQUEST_URI => isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null
        );

        $host = null;

        switch ($config->getHostSrc()) {
            case Config::HOST_SERVER_NAME:
                $host = $_SERVER['SERVER_NAME'];
                break;
            case Config::HOST_HOST_HEADER:
                $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null;
                break;
            case Config::HOST_MANUAL:
                $host = $config->getHost();
                break;
        }

        $endpoint[EndpointContext::SERVER_NAME] = $host;

        if (function_exists('http_response_code')) {
            $endpoint[EndpointContext::STATUS] = http_response_code();
        }

        foreach ($endpoint as $k => $v) {
            if ($v !== null) {
                $collector->addEndpointContext(new EndpointContext($k, $v));
            }
        }
    }

}
