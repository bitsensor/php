<?php

namespace BitSensor\Core;


/**
 * Information about the authenticated user.
 * @package BitSensor\Core
 */
class AuthenticatedContext extends Context {

    /**
     * Username of the connecting user.
     */
    const REMOTE_USER = 'user';

    public function __construct() {
        $this->setName(Context::AUTH);
        $this->setValue(array(
            self::REMOTE_USER => $_SERVER['REMOTE_USER']
        ));
    }


}