<?php

namespace BitSensor\Core;


class ServerContext extends Context {

    const SERVER_ADDR = 'address';
    const SERVER_NAME = 'name';
    const SERVER_SOFTWARE = 'software';
    const SERVER_SIGNATURE = 'signature';
    const SERVER_PORT = 'port';
    const DOCUMENT_ROOT = 'documentRoot';
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