<?php

namespace BitSensor\Blocking\Action;


use Proto\Datapoint;

abstract class AbstractBlockingAction implements BlockingAction
{

    public function block(Datapoint $datapoint, $id)
    {
        $datapoint->getEndpoint()['blocked'] = 'true';
        $datapoint->getEndpoint()['blocking.id'] = $id;

        $this->doBlock($datapoint);
    }

    /**
     * Implement action that should be executed upon BlockingHandler
     * decided a request should be blocked.
     *
     * @param Datapoint $datapoint that is blocked
     * @return mixed
     */
    public abstract function doBlock(Datapoint $datapoint);

    public function __construct($optionalConfiguration = null)
    {
        // Implement optional configuration
    }

}