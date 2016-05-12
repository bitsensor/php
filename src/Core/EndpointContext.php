<?php

namespace BitSensor\Core;


/**
 * Information about the server.
 * @package BitSensor\Core
 * @see $_SERVER
 */
class EndpointContext extends Context {

    /**
     * Server information.
     */
    const NAME = 'endpoint';
    /**
     * Server address.
     */
    const SERVER_ADDR = 'address';
    /**
     * Server name as defined in the server config.
     */
    const SERVER_NAME = 'name';
    /**
     * Name of the web server software.
     */
    const SERVER_SOFTWARE = 'software';
    /**
     * Signature of the web server software.
     */
    const SERVER_SIGNATURE = 'signature';
    /**
     * Port on which the server is running.
     */
    const SERVER_PORT = 'port';
    /**
     * Document root.
     */
    const DOCUMENT_ROOT = 'documentRoot';
    /**
     * CGI revision.
     */
    const GATEWAY_INTERFACE = 'interface';
    /**
     * Absolute path to the executed script.
     */
    const SCRIPT_FILENAME = 'location';
    /**
     * Request time with microseconds.
     *
     * Formatted as <code>Y-m-d\TH:i:s.µO</code>
     */
    const REQUEST_TIME = 'localtime';
    /**
     * Request URI.
     */
    const REQUEST_URI = 'uri';
    /**
     * Status code.
     */
    const STATUS = 'code';

    public function __construct($key, $value) {
        $this->setName($key);
        $this->setValue($value);
    }

}