<?php

namespace BitSensor\Core;


/**
 * Information from ModSecurity.
 * @package BitSensor\Core
 * @see https://www.modsecurity.org/
 */
class ModSecurityContext extends Context {

    /**
     * Events from ModSecurity.
     */
    const WAF_EVENTS = 'events';
    /**
     * Score from ModSecurity.
     */
    const WAF_SCORE = 'score';

    public function __construct($key, $value) {
        $this->setName(Context::MOD_SECURITY . '.' . $key);
        $this->setValue($value);
    }

}