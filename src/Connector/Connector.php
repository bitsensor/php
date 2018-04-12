<?php

namespace BitSensor\Connector;

use BitSensor\Exception\ApiException;
use Proto\Datapoint;


/**
 * Handles the connection with the BitSensor servers.
 * @package BitSensor\Core
 */
interface Connector
{
    /**
     * Sends the data to the server.
     * @param Datapoint $datapoint The data to send as a JSON encoded object.
     * @throws ApiException
     */
    public function close($datapoint);

    /**
     * Connector constructor must optionally allow to be configured with a
     * assoc string[].
     * @param string[] $optionalConfiguration
     */
    public function __construct($optionalConfiguration = null);
}