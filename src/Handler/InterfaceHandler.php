<?php

namespace BitSensor\Handler;

use BitSensor\Core\Config;
use BitSensor\Core\EndpointConstants;
use BitSensor\Util\ServerInfo;
use Proto\Datapoint;


/**
 * Collects information about the used interface.
 * @package BitSensor\Handler
 */
class InterfaceHandler implements Handler
{

    /**
     * @param Datapoint $datapoint
     * @param Config $config
     */
    public function handle(Datapoint $datapoint, Config $config)
    {
        $datapoint->getEndpoint()[EndpointConstants::CLI] = ServerInfo::isCli() ? 'true' : 'false';
        $datapoint->getEndpoint()[EndpointConstants::SAPI] = php_sapi_name();
    }
}