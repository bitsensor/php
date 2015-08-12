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

    public function __construct() {
        $this->setName(Context::AUTH);
        $this->setValue(array(
            self::PHP_AUTH_USER => $_SERVER['PHP_AUTH_USER'],
            self::PHP_AUTH_PW => $_SERVER['PHP_AUTH_PW'],
            self::AUTH_TYPE => $_SERVER['AUTH_TYPE']
        ));
    }


}