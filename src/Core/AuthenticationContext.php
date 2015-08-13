<?php

namespace BitSensor\Core;


/**
 * Information about the user trying to authenticate.
 * @package BitSensor\Core
 */
class AuthenticationContext extends Context {

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

    public function __construct() {
        $this->setName(Context::AUTH);
        $this->setValue(array(
            self::PHP_AUTH_USER => isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : null,
            self::PHP_AUTH_PW => isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : null,
            self::AUTH_TYPE => isset($_SERVER['AUTH_TYPE']) ? $_SERVER['AUTH_TYPE'] : null,
            self::REMOTE_USER => isset($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER'] : null
        ));
    }


}