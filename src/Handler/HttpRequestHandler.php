<?php

namespace BitSensor\Handler;

use BitSensor\Core\AuthenticationContext;
use BitSensor\Core\Collector;
use BitSensor\Core\Config;
use BitSensor\Core\EndpointContext;
use BitSensor\Core\HttpContext;

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
            HttpContext::HTTPS => isset($_SERVER['HTTPS'])
        );

        foreach ($http as $k => $v) {
            $collector->addContext(new HttpContext($k, $v));
        }

        $auth = array(
            AuthenticationContext::PHP_AUTH_USER => isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : null,
            AuthenticationContext::PHP_AUTH_PW => isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : null,
            AuthenticationContext::AUTH_TYPE => isset($_SERVER['AUTH_TYPE']) ? $_SERVER['AUTH_TYPE'] : null,
            AuthenticationContext::REMOTE_USER => isset($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER'] : null
        );

        foreach ($auth as $k => $v) {
            $collector->addContext(new AuthenticationContext($k, $v));
        }

        $micro_date = microtime();
        $date_array = explode(' ', $micro_date);
        $date = date('Y-m-d\TH:i:s', $date_array[1]);
        $time = substr($date_array[0], 1);
        $timezone = date('O', $date_array[1]);


        $endpoint = array(
            EndpointContext::SERVER_ADDR => $_SERVER['SERVER_ADDR'],
            EndpointContext::SERVER_NAME => $_SERVER['SERVER_NAME'],
            EndpointContext::SERVER_SOFTWARE => $_SERVER['SERVER_SOFTWARE'],
            EndpointContext::SERVER_SIGNATURE => $_SERVER['SERVER_SIGNATURE'],
            EndpointContext::SERVER_PORT => $_SERVER['SERVER_PORT'],
            EndpointContext::DOCUMENT_ROOT => $_SERVER['DOCUMENT_ROOT'],
            EndpointContext::GATEWAY_INTERFACE => $_SERVER['GATEWAY_INTERFACE'],
            EndpointContext::SCRIPT_FILENAME => $_SERVER['SCRIPT_FILENAME'],
            EndpointContext::REQUEST_TIME => $date . $time . $timezone,
            EndpointContext::REQUEST_TIME_UNIX => isset($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : null,
            EndpointContext::REQUEST_URI => isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null
        );

        foreach ($endpoint as $k => $v) {
            $collector->addEndpointContext(new EndpointContext($k, $v));
        }
    }

}