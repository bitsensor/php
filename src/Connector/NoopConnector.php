<?php

namespace BitSensor\Connector;


use Proto\Datapoint;

class NoopConnector extends AbstractConnector implements Connector
{

    /**
     * Place to implement sending data to remote
     * @param Datapoint $datapoint
     * @return mixed
     */
    protected function send(Datapoint $datapoint)
    {
        // NoOp :)
        return;
    }

}