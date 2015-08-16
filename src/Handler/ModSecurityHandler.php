<?php

namespace BitSensor\Handler;


use BitSensor\Core\Collector;
use BitSensor\Core\ModSecurityContext;

/**
 * Collects information from ModSecurity.
 * @package BitSensor\Handler
 * @see https://www.modsecurity.org/
 */
class ModSecurityHandler {

    /**
     * @param Collector $collector
     */
    public static function handle(Collector $collector) {
        $modSecurity = array(
            ModSecurityContext::WAF_EVENTS => isset($_SERVER['HTTP_X_WAF_EVENTS']) ? $_SERVER['HTTP_X_WAF_EVENTS'] : null,
            ModSecurityContext::WAF_SCORE => isset($_SERVER['HTTP_X_WAF_SCORE']) ? $_SERVER['HTTP_X_WAF_SCORE'] : null
        );

        foreach ($modSecurity as $k => $v) {
            $collector->addContext(new ModSecurityContext($k, $v));
        }
    }

}