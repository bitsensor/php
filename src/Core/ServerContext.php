<?php

namespace BitSensor\Core;


/**
 * Information about the server.
 * @package BitSensor\Core
 * @see $_SERVER
 */
class ServerContext extends Context {

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

    public function __construct() {
        $this->setName(Context::SERVER);
        $this->setValue(array(
            self::SERVER_ADDR => $_SERVER['SERVER_ADDR'],
            self::SERVER_NAME => $_SERVER['SERVER_NAME'],
            self::SERVER_SOFTWARE => $_SERVER['SERVER_SOFTWARE'],
            self::SERVER_SIGNATURE => $_SERVER['SERVER_SIGNATURE'],
            self::SERVER_PORT => $_SERVER['SERVER_PORT'],
            self::DOCUMENT_ROOT => $_SERVER['DOCUMENT_ROOT'],
            self::GATEWAY_INTERFACE => $_SERVER['GATEWAY_INTERFACE']
        ));
    }

}