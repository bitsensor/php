<?php

namespace BitSensor\Handler;

use BitSensor\Core\BitSensor;
use Proto\Error;
use Psr\Log\AbstractLogger;

class PsrLogHandler extends AbstractLogger
{

    public function log($level, $message, array $context = array())
    {
        $error = new Error();
        $error->setType($level);
        $error->setDescription($message);

        BitSensor::addError($error);
    }
}