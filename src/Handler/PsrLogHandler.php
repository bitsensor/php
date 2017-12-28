<?php

namespace BitSensor\Handler;

use Proto\Error;
use Psr\Log\AbstractLogger;

class PsrLogHandler extends AbstractLogger
{

    public function log($level, $message, array $context = array())
    {
        $error = new Error();
        $error->setType($level);
        $error->setDescription($message);

        global $bitSensor;
        if(isset($bitSensor))
            $bitSensor->addError($error);
    }
}