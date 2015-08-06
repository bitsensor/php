<?php

namespace BitSensor\Core;


class InputContext extends Context {

    const POST = 'post';
    const GET = 'get';
    const COOKIE = 'cookie';

    public function __construct($name, $values) {
        $this->setName($name);
        $this->setValue($values);
    }

}