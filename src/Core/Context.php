<?php

namespace BitSensor\Core;


/**
 * Class Context
 * @package BitSensor\Core
 */
abstract class Context {

    const REMOTE_ADDR = 'IP';
    const SCRIPT_FILENAME = 'Path';
    const HTTP = 'HTTP';
    const AUTH = 'Authentication';
    const SERVER = 'Server';
    const INPUT = 'Input';

    /**
     * @var string
     */
    private $name;
    /**
     * @var array|string
     */
    private $value;

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return array|string
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * @param array|string $value
     */
    public function setValue($value) {
        $this->value = $value;
    }

}