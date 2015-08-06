<?php

namespace BitSensor\Core;


class HttpContext extends Context {

    const SERVER_PROTOCOL = 'version';
    const QUERY_STRING = 'query';
    const HTTP_USER_AGENT = 'userAgent';
    const HTTP_REFERRER = 'referrer';
    const REQUEST_METHOD = 'type';
    const REQUEST_TIME = 'localtimeUnix';
    const HTTP_ACCEPT = 'acceptMedia';
    const HTTP_ACCEPT_CHARSET = 'acceptCharset';
    const HTTP_ACCEPT_ENCODING = 'acceptEncoding';
    const HTTP_ACCEPT_LANGUAGE = 'acceptLanguage';
    const REQUEST_URI = 'uri';
    const PATH_INFO = 'pathInfo';
    const HTTPS = 'https';

    public function __construct() {
        $this->setName(Context::HTTP);
        $this->setValue(array(
            self::SERVER_PROTOCOL => $_SERVER['SERVER_PROTOCOL'],
            self::QUERY_STRING => $_SERVER['QUERY_STRING'],
            self::HTTP_USER_AGENT => $_SERVER['HTTP_USER_AGENT'],
            self::HTTP_REFERRER => $_SERVER['HTTP_REFERER'],
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