<?php

namespace BitSensor\View;


/**
 * Page to show when a server error occurs.
 * @package BitSensor\View
 */
class ErrorView extends View {

    public function __construct($error) {
        $this->setContent('<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>' . $error . ' ' . $this->getTitle($error) . '</title>
</head><body>
<h1>' . $this->getTitle($error) . '</h1>
' . $this->getBody($error) . '
<hr>' . $_SERVER['SERVER_SIGNATURE'] . '</body></html>');
    }

    /**
     * Maps an error code to a name.
     *
     * @param $error string The error code.
     * @return string The name of the error.
     * @see https://github.com/apache/httpd/blob/trunk/modules/http/http_protocol.c
     */
    private function getTitle($error) {
        $header = array(
            '400' => 'Bad Request',
            '401' => 'Unauthorized',
            '402' => 'Payment Required',
            '403' => 'Forbidden',
            '404' => 'Not Found',
            '405' => 'Method Not Allowed',
            '406' => 'Not Acceptable',
            '407' => 'Proxy Authentication Required',
            '408' => 'Request Timeout',
            '409' => 'Conflict',
            '410' => 'Gone',
            '411' => 'Length Required',
            '412' => 'Precondition Failed',
            '413' => 'Request Entity Too Large',
            '414' => 'Request-URI Too Long',
            '415' => 'Unsupported Media Type',
            '500' => 'Internal Server Error',
            '501' => 'Not Implemented',
            '502' => 'Bad Gateway',
            '503' => 'Service Unavailable',
            '504' => 'Gateway Timeout',
            '505' => 'HTTP Version Not Supported'
        );

        return array_key_exists($error, $header) ? $header[$error] : 'Error';
    }

    /**
     * Maps an error code to a description.
     *
     * @param $error string The error code.
     * @return string The description of the error.
     * @see https://github.com/apache/httpd/blob/trunk/modules/http/http_protocol.c
     */
    private function getBody($error) {
        $body = array(
            '400' => '<p>Your browser sent a request that this server could not understand.</p>',
            '401' => '<p>This server could not verify that you are authorized to access the document requested.  Either you supplied the wrong credentials (e.g., bad password), or your browser doesn\'t understand how to supply the credentials required.</p>',
            '403' => '<p>You don\'t have permission to access ' . $_SERVER['REQUEST_URI'] . ' on this server.</p>',
            '404' => '<p>The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found on this server.</p>',
            '405' => '<p>The requested method ' . $_SERVER['REQUEST_METHOD'] . ' is not allowed for the URL ' . $_SERVER['REQUEST_URI'] . '.</p>',
            '406' => '<p>An appropriate representation of the requested resource ' . $_SERVER['REQUEST_URI'] . ' could not be found on this server.</p>',
            '407' => '<p>This server could not verify that you are authorized to access the document requested.  Either you supplied the wrong credentials (e.g., bad password), or your browser doesn\'t understand how to supply the credentials required.</p>',
            '408' => '<p>Server timeout waiting for the HTTP request from the client.</p>',
            '410' => '<p>The requested resource<br />' . $_SERVER['REQUEST_URI'] . '<br /> is no longer available on this server and there is no forwarding address. Please remove all references to this resource.</p>',
            '411' => '<p>A request of the requested method ' . $_SERVER['REQUEST_METHOD'] . ' requires a valid Content-length.</p>',
            '412' => '<p>The precondition on the request for the URL ' . $_SERVER['REQUEST_URI'] . ' evaluated to false.</p>',
            '413' => 'The requested resource<br />' . $_SERVER['REQUEST_URI'] . '<br /> does not allow request data with ' . $_SERVER['REQUEST_METHOD'] . ' requests, or the amount of data provided in the request exceeds the capacity limit.',
            '414' => '<p>The requested URL\'s length exceeds the capacity limit for this server.</p>',
            '415' => '<p>The supplied request data is not in a format acceptable for processing by this resource.</p>',
            '500' => '<p>The server encountered an internal error or misconfiguration and was unable to complete your request.</p><p>Please contact the server administrator at ' . $_SERVER['SERVER_ADMIN'] . ' to inform them of the time this error occurred, and the actions you performed just before this error.</p><p>More information about this error may be available in the server error log.</p>',
            '501' => '<p>' . $_SERVER['REQUEST_METHOD'] . ' to ' . $_SERVER['REQUEST_URI'] . ' not supported.</p>',
            '502' => '<p>The proxy server received an invalid response from an upstream server.</p>',
            '503' => '<p>The server is temporarily unable to service your request due to maintenance downtime or capacity problems. Please try again later.</p>'
        );

        return array_key_exists($error, $body) ? $body[$error] : '';
    }

}