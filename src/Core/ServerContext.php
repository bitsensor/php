<?php

namespace BitSensor\Core;


class ServerContext extends Context {

    const SERVER_ADDR = 'Address';
    const SERVER_NAME = 'Name';
    const SERVER_SOFTWARE = 'Software';
    const SERVER_SIGNATURE = 'Signature';
    const SERVER_PORT = 'Port';
    const DOCUMENT_ROOT = 'Document Root';
    const GATEWAY_INTERFACE = 'Interface';

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