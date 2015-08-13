<?php

namespace BitSensor\Core;


/**
 * Information about an error in the code.
 * @package BitSensor\Core
 */
class CodeError extends Error {

    /**
     * Name of the file in which the error occurred.
     */
    const ERRFILE = 'filename';
    /**
     * Line at which the error occurred.
     */
    const ERRLINE = 'line';

    /**
     * Name of the file in which the error occurred.
     *
     * @var string
     */
    private $errfile;
    /**
     * Line at which the error occurred.
     *
     * @var int
     */
    private $errline;

    /**
     * @param int $errno Error code.
     * @param string $errstr Error description.
     * @param string $errfile Name of the file in which the error occurred.
     * @param int $errline Line at which the error occurred.
     */
    public function __construct($errno, $errstr, $errfile, $errline) {
        parent::__construct($errno, $errstr);
        $this->setFile($errfile);
        $this->setLine($errline);
    }

    /**
     * @return string Name of the file in which the error occurred.
     */
    public function getFile() {
        return $this->errfile;
    }

    /**
     * @param string $errfile Name of the file in which the error occurred.
     */
    public function setFile($errfile) {
        $this->errfile = $errfile;
    }

    /**
     * @return int Line at which the error occurred.
     */
    public function getLine() {
        return $this->errline;
    }

    /**
     * @param int $errline Line at which the error occurred.
     */
    public function setLine($errline) {
        $this->errline = $errline;
    }

    public function toArray() {
        $array = parent::toArray();

        $array[self::ERRFILE] = $this->getFile();
        $array[self::ERRLINE] = $this->getLine();

        return $array;
    }

}