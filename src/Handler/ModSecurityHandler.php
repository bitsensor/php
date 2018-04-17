<?php

namespace BitSensor\Handler;


use BitSensor\Core\ModSecurityContext;
use Proto\Datapoint;

/**
 * Collects information from ModSecurity.
 * @package BitSensor\Handler
 * @see https://www.modsecurity.org/
 */
class ModSecurityHandler extends AbstractHandler
{

    /**
     * @param Datapoint $datapoint
     */
    public function doHandle(Datapoint $datapoint)
    {
        $modSecurity = array(
            ModSecurityContext::WAF_EVENTS => isset($_SERVER['HTTP_X_WAF_EVENTS']) ? $_SERVER['HTTP_X_WAF_EVENTS'] : null,
            ModSecurityContext::WAF_SCORE => isset($_SERVER['HTTP_X_WAF_SCORE']) ? $_SERVER['HTTP_X_WAF_SCORE'] : null
        );

        foreach ($modSecurity as $k => $v) {
            if ($v !== null) {
                $datapoint->getContext()[ModSecurityContext::NAME . '.' . $k] = $v;
            }
        }
    }
}