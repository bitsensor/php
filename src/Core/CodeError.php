<?php

namespace BitSensor\Core;


class CodeError extends Error {

    const ERRFILE = 'filename';
    const ERRLINE = 'line';
    const ERRCONTEXT = 'context';

    /**
     * @var string
     */
    private $errfile;
    /**
     * @var int
     */
    private $errline;
    /**
     * @var array
     */
    private $errcontext;

    /**
     * @param int $errno
     * @param string $errstr
     * @param string $errfile
     * @param int $errline
     * @param array $errcontext
     */
    public function __construct($errno, $errstr, $errfile, $errline, $errcontext) {
        parent::__construct($errno, $errstr);
        $this->setFile($errfile);
        $this->setLine($errline);
        $this->setContext($errcontext);
    }

    /**
     * @return string
     */
    public function getFile() {
        return $this->errfile;
    }

    /**
     * @param string $errfile
     */
    public function setFile($errfile) {
        $this->errfile = $errfile;
    }

    /**
     * @return int
     */
    public function getLine() {
        return $this->errline;
    }

    /**
     * @param int $errline
     */
    public function setLine($errline) {
        $this->errline = $errline;
    }

    /**
     * @return array
     */
    public function getContext() {
        return $this->errcontext;
    }

    /**
     * @param array $errcontext
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