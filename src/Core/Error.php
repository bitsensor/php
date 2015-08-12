<?php

namespace BitSensor\Core;


/**
 * Container for information about errors occurring during application execution.
 * @package BitSensor\Core
 */
abstract class Error {

    /**
     * Error code.
     */
    const ERRNO = 'code';
    /**
     * Error description.
     */
    const ERRSTR = 'description';

    /**
     * Error code.
     *
     * @var int
     */
    private $errno;
    /**
     * Error description.
     *
     * @var string
     */
    private $errstr;

    /**
     * @param int $errno Error code.
     * @param string $errstr Error description.
     */
    public function __construct($errno, $errstr) {
        $this->setCode($errno);
        $this->setMessage($errstr);
    }

    /**
     * @return string Error description.
     */
    public function getMessage() {
        return $this->errstr;
    }

    /**
     * @param string $errstr Error description.
     */
    public function setMessage($errstr) {
        $this->errstr = $errstr;
    }

    /**
     * @return int Error code.
     */
    public function getCode() {
        return $this->errno;
    }

    /**
     * @param int $errno Error code.
     */
    public function setCode($errno) {
        $this->errno = $errno;
    }

    /**
     * <p>Adds all elements of the error to an array.</p>
     *
     * <p>Child classes should add their own elements to this array. Example:</p>
     *
     * <code>
     * const MY_ERROR = 'My Error';
     *
     * public function toArray() {
     *     $array = parent::toArray();
     *     $array[self::MY_ERROR] = $this->getMyError();
     *     return $array;
     * }
     * </code>
     *
     * @return array
     */
    public function toArray() {
        return array(
            self::ERRNO => $this->getCode(),
            self::ERRSTR => $this->getMessage()
        );
    }

}