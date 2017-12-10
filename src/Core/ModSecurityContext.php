<?php

namespace BitSensor\Core;


/**
 * Information from ModSecurity.
 * @package BitSensor\Core
 * @see https://www.modsecurity.org/
 */
class ModSecurityContext extends Constants
{
    /**
     * ModSecurity.
     */
    const NAME = 'modSecurity';
    /**
     * Events from ModSecurity.
     */
    const WAF_EVENTS = 'events';
    /**
     * Score from ModSecurity.
     */
    const WAF_SCORE = 'score';

}