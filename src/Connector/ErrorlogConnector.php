<?php

namespace BitSensor\Connector;


use Proto\Datapoint;

class ErrorlogConnector extends AbstractConnector
{

    /**
     * Place to implement sending data to remote
     * @param Datapoint $datapoint
     * @return mixed
     */
    protected function send(Datapoint $datapoint)
    {
        return error_log($datapoint->serializeToJsonString());
    }
}