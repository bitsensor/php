<?php

namespace BitSensor\Blocking\Action;


use Proto\Datapoint;

class TestAction extends AbstractBlockingAction
{
    public static $datapoint;

    /**
     * Implement action that should be executed upon BlockingHandler
     * decided a request should be blocked.
     *
     * @param Datapoint $datapoint that is blocked
     * @return mixed
     */
    public function doBlock(Datapoint $datapoint)
    {
        self::$datapoint = $datapoint;
    }

    public function __construct($optionalConfiguration = null)
    {
        parent::__construct($optionalConfiguration);
    }
}