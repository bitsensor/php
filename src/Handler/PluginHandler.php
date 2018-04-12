<?php

namespace BitSensor\Handler;


use BitSensor\Core\BitSensor;
use BitSensor\Core\Config;
use BitSensor\Core\MetaContext;
use Proto\Datapoint;

/**
 * Class PluginHandler adds plugin meta context
 * @package BitSensor\Handler
 */
class PluginHandler implements Handler
{

    /**
     * @param Datapoint $datapoint
     * @param Config $config
     * @return void
     */
    public function handle(Datapoint $datapoint, Config $config)
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