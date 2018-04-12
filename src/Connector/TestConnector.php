<?php

namespace BitSensor\Connector;


use Proto\Datapoint;

class TestConnector extends AbstractConnector
{
    public static $datapoint;
    public static $configuration;

    /**
     * Place to implement sending data to remote
     * @param Datapoint $datapoint
     * @return mixed
     */
    protected function send(Datapoint $datapoint)
    {
        return self::$datapoint = $datapoint;
    }

    /**
     * Connector constructor must optionally allow to be configured with a
     * assoc string[].
     * @param string[] $optionalConfiguration
     */
    public function __construct($optionalConfiguration = null)
    {
        self::$configuration = $optionalConfiguration;
    }
}