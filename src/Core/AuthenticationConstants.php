<?php

namespace BitSensor\Core;


/**
 * Information about the user trying to authenticate.
 * @package BitSensor\Core
 */
class AuthenticationConstants extends Constants
{
    /**
     * Authentication of the connecting user.
     */
    const NAME = 'authentication';
    /**
     * Username of the connecting user.
     */
    const PHP_AUTH_USER = 'username';
    /**
     * Password of the connecting user.
     */
    const PHP_AUTH_PW = 'password';
    /**
     * Authentication type used.
     */
    const AUTH_TYPE = 'type';
    /**
     * Username of the connecting user.
     */
    const REMOTE_USER = 'user';

}