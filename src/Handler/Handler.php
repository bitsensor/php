<?php

namespace BitSensor\Handler;

use BitSensor\Core\Config;
use Proto\Datapoint;

/**
 * Interface Handler
 * @package BitSensor\Handler
 */
interface Handler
{

    /**
     * @param Datapoint $datapoint
     * @param Config $config
     * @return void
     */
    public function handle(Datapoint $datapoint, Config $config);

}