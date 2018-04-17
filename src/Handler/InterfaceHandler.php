<?php

namespace BitSensor\Handler;

use BitSensor\Core\EndpointConstants;
use BitSensor\Util\ServerInfo;
use Proto\Datapoint;


/**
 * Collects information about the used interface.
 * @package BitSensor\Handler
 */
class InterfaceHandler extends AbstractHandler
{

    /**
     * @param Datapoint $datapoint
     */
    public function doHandle(Datapoint $datapoint)
    {
        $datapoint->getEndpoint()[EndpointConstants::CLI] = ServerInfo::isCli() ? 'true' : 'false';
        $datapoint->getEndpoint()[EndpointConstants::SAPI] = php_sapi_name();
    }
}