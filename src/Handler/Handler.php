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
     * @return void
     */
    public function handle(Datapoint $datapoint);

    /**
     * Handler constructor.
     * @param Config $config
     */
    public function __construct(Config $config = null);

}