<?php

namespace BitSensor\Handler;


use BitSensor\Core\Config;
use Proto\Datapoint;

abstract class AbstractHandler implements Handler
{

    /**
     * @param Datapoint $datapoint
     * @return void
     */
    public function handle(Datapoint $datapoint)
    {
        $this->doHandle($datapoint);
    }

    /**
     * Extend a Datapoint with more fields
     *
     * @param Datapoint $datapoint
     * @return mixed
     */
    public abstract function doHandle(Datapoint $datapoint);

    /**
     * Configure the Handler. Automatically called in the constructor.
     *
     * @param Config $config
     * @return mixed
     */
    public function configure(Config $config)
    {
        return; // To be extended
    }

    /**
     * Handler constructor.
     * @param Config $config
     */
    public function __construct(Config $config = null)
    {
        if (!empty($config))
            $this->configure($config);
    }
}