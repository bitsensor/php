<?php

namespace BitSensor\Handler;


use BitSensor\Core\Collector;
use BitSensor\Core\Config;

/**
 * Interface Handler
 * @package BitSensor\Handler
 */
interface Handler {

    /**
     * @param Collector $collector
     * @param Config $config
     * @return void
     */
    public function handle(Collector $collector, Config $config);

}