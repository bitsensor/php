<?php

namespace BitSensor\Core;


/**
 * Information about the PHP session of the user.
 * @package BitSensor\Core
 */
class SessionContext extends Context {

    /**
     * PHP session.
     */
    const NAME = 'session';
    /**
     * Session ID of the connecting user.
     */
    const SESSION_ID = 'sessionId';
    /**
     * Username.
     */
    const USERNAME = 'username';

    public function __construct($key, $value) {
        $this->setName('php.' . self::NAME . '.' . $key);
        $this->setValue($value);
    }

}