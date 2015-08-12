<?php

namespace BitSensor\Core;


/**
 * Information about the HTTP request.
 * @package BitSensor\Core
 * @see $_SERVER
 */
class HttpContext extends Context {

    /**
     * Server protocol.
     */
    const SERVER_PROTOCOL = 'version';
    /**
     * Query string.
     */
    const QUERY_STRING = 'query';
    /**
     * User agent string
     */
    const HTTP_USER_AGENT = 'userAgent';
    /**
     * Referer.
     */
    const HTTP_REFERER = 'referer';
    /**
     * Request method.
     */
    const REQUEST_METHOD = 'type';
    /**
     * Request time in UNIX time.
     */
    const REQUEST_TIME = 'localtimeUnix';
    /**
     * Accept header.
     */
    const HTTP_ACCEPT = 'acceptMedia';
    /**
     * Accept charset header.
     */
    const HTTP_ACCEPT_CHARSET = 'acceptCharset';
    /**
     * Accept encoding header.
     */
    const HTTP_ACCEPT_ENCODING = 'acceptEncoding';
    /**
     * Accept language header.
     */
    const HTTP_ACCEPT_LANGUAGE = 'acceptLanguage';
    /**
     * Request URI.
     */
    const REQUEST_URI = 'uri';
    /**
     * Path info.
     */
    const PATH_INFO = 'pathInfo';
    /**
     * HTTPS connection.
     */
    const HTTPS = 'https';

    public function __construct() {
        $this->setName(Context::HTTP);
        $this->setValue(array(
            self::SERVER_PROTOCOL => $_SERVER['SERVER_PROTOCOL'],
            self::QUERY_STRING => $_SERVER['QUERY_STRING'],
            self::HTTP_USER_AGENT => $_SERVER['HTTP_USER_AGENT'],
            self::HTTP_REFERER => $_SERVER['HTTP_REFERER'],
            self::REQUEST_METHOD => $_SERVER['REQUEST_METHOD'],
            self::REQUEST_TIME => $_SERVER['REQUEST_TIME'],
            self::HTTP_ACCEPT => $_SERVER['HTTP_ACCEPT'],
            self::HTTP_ACCEPT_CHARSET => $_SERVER['HTTP_ACCEPT_CHARSET'],
            self::HTTP_ACCEPT_ENCODING => $_SERVER['HTTP_ACCEPT_ENCODING'],
            self::HTTP_ACCEPT_LANGUAGE => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
            self::REQUEST_URI => $_SERVER['REQUEST_URI'],
            self::PATH_INFO => $_SERVER['PATH_INFO'],
            self::HTTPS => isset($_SERVER['HTTPS'])
        ));
    }

}