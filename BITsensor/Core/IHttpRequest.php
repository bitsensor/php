<?php
namespace BITsensor\Core;

use BITsensor\Core\Lib\autoParse;
use BITsensor\Core\Lib\LibRename;

abstract class IHttpRequest extends autoParse implements IContextable{
    public $SERVER_PROTOCOL, $QUERY_STRING,
        $HTTP_USER_AGENT, $HTTP_REFERER,
        $REQUEST_METHOD, $REQUEST_TIME,
        $HTTP_ACCEPT, $HTTP_ACCEPT_CHARSET, $HTTP_ACCEPT_ENCODING, $HTTP_ACCEPT_LANGUAGE,
        $REQUEST_URI, $PATH_INFO,
        $HTTPS, $PHP_AUTH_DIGEST;

    public function autoParseFinalization() {
        $this->HTTPS = ($this->isHttps()) ? 'true' : 'false';
        $this->PHP_AUTH_DIGEST = ($this->isAuthRequest()) ? 'true' : 'false';
    }

    public function isAuthRequest() {
        return ($this->PHP_AUTH_DIGEST == "Authorization");
    }

    public function isHttps() {
        return (!(($this->HTTPS == "off") || ($this->HTTPS == "")));
    }

    public function isHttpAuthenticationRequest() {
        $httpAuthenticationRequest = new HttpAuthenticationRequest($this->RAW_CONSTRUCTOR_ARRAY);

        if ($httpAuthenticationRequest->PHP_AUTH_USER != '')
            return $httpAuthenticationRequest;
        else
            return false;
    }

    public function isHttpAuthenticatedRequest() {
        $httpAuthenticatedRequest = new HttpAuthenticatedRequest($this->RAW_CONSTRUCTOR_ARRAY);

        if (isset($httpAuthenticatedRequest->REMOTE_USER))
            return $httpAuthenticatedRequest;
        else
            return false;
    }

    public function toContext() {
        return LibRename::Rename($this, array(
            'SERVER_PROTOCOL' => 'Version',
            'QUERY_STRING' => 'Query',
            'HTTP_USER_AGENT' => 'User-Agent',
            'HTTP_REFERER' => 'Referrer',
            'REQUEST_METHOD' => 'Type',
            'REQUEST_TIME' => 'Localtime-Unix',
            'HTTP_ACCEPT' => 'Accept-Media',
            'HTTP_ACCEPT_CHARSET' => 'Accept-Charset',
            'HTTP_ACCEPT_ENCODING' => 'Accept-Encoding',
            'HTTP_ACCEPT_LANGUAGE' => 'Accept-Language',
            'REQUEST_URI' => 'Uri',
            'PATH_INFO' => 'PathInfo',
            'HTTPS' => 'HTTPS'
        ));
    }
}