<?php

namespace BitSensor\Connector;

use Proto\Datapoint;

class FileConnector extends AbstractConnector
{

    private static $filename = "/var/log/bitsensor-datapoints-php.log";

    /**
     * Log event to file.
     * @param Datapoint $datapoint
     * @return mixed
     */
    protected function send(Datapoint $datapoint)
    {
        return file_put_contents(self::$filename, $datapoint->serializeToJsonString() . '\n', FILE_APPEND | LOCK_EX);
    }

    /**
     * Se the filename that the log message is appended to.
     *
     * @param $filename
     */
    public static function setFilename($filename)
    {
        self::$filename = $filename;
    }

    /**
     * Connector constructor must optionally allow to be configured with a
     * assoc string[].
     * @param string[] $optionalConfiguration
     */
    public function __construct($optionalConfiguration = null)
    {
        if (!isset($optionalConfiguration))
            return;

        if (array_key_exists("filename", $optionalConfiguration))
            self::$filename = $optionalConfiguration["filename"];
    }
}