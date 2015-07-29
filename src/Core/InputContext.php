<?php

namespace BITsensor\Core;


class InputContext extends Context {

    const POST = 'POST';
    const GET = 'GET';
    const COOKIE = 'Cookie';

    public function __construct($name, $values) {
        $this->setName($name);
        $this->setValue($values);
    }

}