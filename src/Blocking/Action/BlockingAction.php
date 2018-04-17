<?php

namespace BitSensor\Blocking\Action;


use Proto\Datapoint;

interface BlockingAction
{
    /**
     * Implement action that should be executed upon BlockingHandler
     * decided a request should be blocked.
     *
     * @param Datapoint $datapoint that is blocked
     * @param string $id
     * @return mixed
     */
    public function block(Datapoint $datapoint, $id);

    public function __construct($optionalConfiguration = null);
}