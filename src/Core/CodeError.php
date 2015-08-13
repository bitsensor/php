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
     * Stacktrace of the error.
     */
    const ERRCONTEXT = 'context';

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
     * Stacktrace of the error.
     *
     * @var array
     */
    private $errcontext;

    /**
     * @param int $errno Error code.
     * @param string $errstr Error description.
     * @param string $errfile Name of the file in which the error occurred.
     * @param int $errline Line at which the error occurred.
     * @param array $errcontext Stacktrace of the error.
     */
    public function __construct($errno, $errstr, $errfile, $errline, $errcontext = null) {
        parent::__construct($errno, $errstr);
        $this->setFile($errfile);
        $this->setLine($errline);
        $this->setContext($errcontext);
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

    /**
     * @return array Stacktrace of the error.
     */
    public function getContext() {
        return $this->errcontext;
    }

    /**
     * @param array $errcontext Stacktrace of the error.
     */
    public function setContext($errcontext) {
        $this->errcontext = $errcontext;
    }

    public function toArray() {
        $array = parent::toArray();

        $array[self::ERRFILE] = $this->getFile();
        $array[self::ERRLINE] = $this->getLine();
        $array[self::ERRCONTEXT] = $this->getContext();

        return $array;
    }

}