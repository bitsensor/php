<?php

namespace BitSensor\Core;


/**
 * Information about the HTTP request.
 * @package BitSensor\Core
 * @see $_SERVER
 */
class HttpContext extends Context {

    /**
     * HTTP request.
     */
    const NAME = 'http';
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
    /**
     * Status code.
     */
    const STATUS = 'code';

    public function __construct($key, $value) {
        $this->setName(self::NAME . '.' . $key);
        $this->setValue($value);
    }

}