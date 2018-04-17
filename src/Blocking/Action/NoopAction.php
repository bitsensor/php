<?php

namespace BitSensor\Blocking\Action;


use Proto\Datapoint;

class NoopAction extends AbstractBlockingAction
{

    /**
     * Implement action that should be executed upon BlockingHandler
     * decided a request should be blocked.
     *
     * @param Datapoint $datapoint that is blocked
     * @return mixed
     */
    public function doBlock(Datapoint $datapoint)
    {
        return; // NoOp
    }
}