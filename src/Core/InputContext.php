<?php

namespace BitSensor\Core;


/**
 * Information about the user's input.
 * @package BitSensor\Core
 */
class InputContext extends Context {

    /**
     * POST fields.
     */
    const POST = 'post';
    /**
     * GET fields.
     */
    const GET = 'get';
    /**
     * Cookies.
     */
    const COOKIE = 'cookie';

    /**
     * @param $name
     * @param $key
     * @param $value
     */
    public function __construct($name, $key, $value) {
        $this->setName('http.' . $name . '.' . $key);
        $this->setValue($value);
    }

}