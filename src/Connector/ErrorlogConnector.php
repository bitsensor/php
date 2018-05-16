<?php

namespace BitSensor\Connector;


use Proto\Datapoint;

class ErrorlogConnector extends AbstractConnector
{

    /**
     * Log event to php error log
     * @param Datapoint $datapoint
     * @return mixed
     */
    protected function send(Datapoint $datapoint)
    {
        return error_log($datapoint->serializeToJsonString());
    }
}