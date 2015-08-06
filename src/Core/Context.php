<?php

namespace BitSensor\Core;


/**
 * Class Context
 * @package BitSensor\Core
 */
abstract class Context {

    const REMOTE_ADDR = 'ip';
    const SCRIPT_FILENAME = 'path';
    const HTTP = 'http';
    const AUTH = 'authentication';
    const SERVER = 'server';
    const INPUT = 'input';

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