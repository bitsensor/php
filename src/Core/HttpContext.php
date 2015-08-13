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
            self::SERVER_PROTOCOL => isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : null,
            self::QUERY_STRING => isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : null,
            self::HTTP_USER_AGENT => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null,
            self::HTTP_REFERER => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null,
            self::REQUEST_METHOD => isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : null,
            self::HTTP_ACCEPT => isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : null,
            self::HTTP_ACCEPT_CHARSET => isset($_SERVER['HTTP_ACCEPT_CHARSET']) ? $_SERVER['HTTP_ACCEPT_CHARSET'] : null,
            self::HTTP_ACCEPT_ENCODING => isset($_SERVER['HTTP_ACCEPT_ENCODING']) ? $_SERVER['HTTP_ACCEPT_ENCODING'] : null,
            self::HTTP_ACCEPT_LANGUAGE => isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : null,
            self::PATH_INFO => isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : null,
            self::HTTPS => isset($_SERVER['HTTPS'])
        ));
    }

}