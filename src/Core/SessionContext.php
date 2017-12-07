<?php

namespace BitSensor\Core;


/**
 * Information about the PHP session of the user.
 * @package BitSensor\Core
 */
class SessionContext extends Constants
{
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

}