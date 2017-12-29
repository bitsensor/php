<?php

namespace BitSensor\Handler;

use Proto\Error;
use Psr\Log\AbstractLogger;

class PsrLogHandler extends AbstractLogger
{
    /**
     * Buffer to hold log messages that are received before $bitSensor is initialized.
     *
     * @var Error[]
     */
    static private $buffer = [];

    /**
     * Maximum capacity of the buffer.
     *
     * @var int
     */
    static private $bufferSize = 50;

    /**
     * Indicates messages have been lost due to a too small bufferSize
     *
     * @var bool
     */
    static private $isOverflown = false;

    /**
     * @return array
     */
    public static function getBuffer()
    {
        return self::$buffer;
    }

    /**
     * Set the new size of the buffer. Does not remove items if the new capacity is below the current buffer size.
     *
     * @param int $bufferSize
     */
    public static function setBufferSize($bufferSize)
    {
        self::$bufferSize = $bufferSize;
    }

    /**
     * Indicates that messages have been lost, due to a too small bufferSize.
     *
     * @return bool
     */
    public static function isOverflown()
    {
        return self::$isOverflown;
    }

    /**
     * @return int
     */
    public static function getBufferSize()
    {
        return self::$bufferSize;
    }

    public static function clearBuffer(){
        unset($buffer);
        self::$buffer = [];
    }

    public function log($level, $message, array $context = array())
    {
        global $bitSensor;

        $error = new Error();
        $error->setType($level);
        $error->setDescription($message);

        if (isset($bitSensor)) {
            $bitSensor->addError($error);
            return;
        }

        if (count(self::$buffer) < self::$bufferSize) {
            array_push(self::$buffer, $error);
        } else {
            self::$isOverflown = true;
        }
    }
}