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

    public function __construct($name, $values) {
        $this->setName($name);
        $this->setValue($values);
    }

}