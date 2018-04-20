<?php

namespace BitSensor\Handler;

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
     * @param string[] $config
     */
    public function __construct($config = null);

}