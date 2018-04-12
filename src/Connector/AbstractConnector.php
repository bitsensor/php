<?php

namespace BitSensor\Connector;

use BitSensor\Util\Log;
use Proto\Datapoint;

abstract class AbstractConnector implements Connector
{

    /**
     * Place to implement sending data to remote
     * @param Datapoint $datapoint
     * @return mixed
     */
    abstract protected function send(Datapoint $datapoint);

    /**
     * Logs debug message, and sends data to remote
     * @param Datapoint $datapoint
     */
    public function close($datapoint)
    {
        $this->logDebug($datapoint->serializeToJsonString());
        $this->send($datapoint);
    }

    protected function logDebug($datapoint)
    {
        Log::d('<pre>' .
            json_encode($datapoint,
                defined("JSON_PRETTY_PRINT") ? JSON_PRETTY_PRINT : 0) .
            '</pre>');
    }

    /**
     * Connector constructor must optionally allow to be configured with a
     * assoc string[].
     * @param string[] $optionalConfiguration
     */
    public function __construct($optionalConfiguration = null)
    {
        //Optionally implemented
        return;
    }
}