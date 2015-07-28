<?php

namespace BITsensor\Core;


abstract class Error {

    const ERRNO = 'Code';
    const ERRSTR = 'Description';

    /**
     * @var int
     */
    private $errno;
    /**
     * @var string
     */
    private $errstr;

    /**
     * @param int $errno
     * @param string $errstr
     */
    public function __construct($errno, $errstr) {
        $this->setCode($errno);
        $this->setMessage($errstr);
    }

    /**
     * @return string
     */
    public function getMessage() {
        return $this->errstr;
    }

    /**
     * @param string $errstr
     */
    public function setMessage($errstr) {
        $this->errstr = $errstr;
    }

    /**
     * @return int
     */
    public function getCode() {
        return $this->errno;
    }

    /**
     * @param int $errno
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