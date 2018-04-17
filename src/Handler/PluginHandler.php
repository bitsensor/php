<?php

namespace BitSensor\Handler;


use BitSensor\Core\BitSensor;
use BitSensor\Core\MetaContext;
use Proto\Datapoint;

/**
 * Class PluginHandler adds plugin meta context
 * @package BitSensor\Handler
 */
class PluginHandler extends AbstractHandler
{

    /**
     * @param Datapoint $datapoint
     * @return void
     */
    public function doHandle(Datapoint $datapoint)
    {
        $meta = array(
            MetaContext::PROVIDER => MetaContext::PROVIDER_PHP,
            MetaContext::PROVIDER_VERSION => BitSensor::VERSION
        );

        foreach ($meta as $k => $v) {
            $datapoint->getMeta()[$k] = $v;
        }
    }

}