<?php

namespace BitSensor\Core;


/**
 * Information about the PHP session of the user.
 * @package BitSensor\Core
 */
class SessionContext extends Context {

    /**
     * PHP Session ID of the connecting user.
     */
    const NAME = 'sessionId';

    public function __construct($value) {
        $this->setName('php.' . self::NAME);
        $this->setValue($value);
    }

}