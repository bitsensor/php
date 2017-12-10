<?php

namespace BitSensor\Core;


/**
 * Information about the user's input.
 * @package BitSensor\Core
 */
class InputContext extends Constants
{
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

}