<?php

namespace BitSensor\Core;


/**
 * Information about the user's input.
 * @package BitSensor\Core
 */
class InputContext extends Context {

    /**
     * POST, GET and Cookie.
     */
    const NAME = 'input';
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
        $this->setName(HttpContext::NAME . '.' . $name . '.' . $key);
        $this->setValue($value);
    }

}