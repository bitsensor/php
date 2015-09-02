<?php

namespace BitSensor\Core;


/**
 * Container for information that helps to authenticate a user.
 * @package BitSensor\Core
 */
abstract class Context {

    /**
     * Name of the Context.
     */
    const NAME = 'context';

    /**
     * Name of the context.
     *
     * @var string
     */
    private $name;
    /**
     * Content of the context. May be a string or an array.
     *
     * @var array|string
     */
    private $value;

    /**
     * @return string Name of the context.
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name Name of the context.
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return array|string Content of the context.
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * @param array|string $value Context of the context.
     */
    public function setValue($value) {
        $this->value = $value;
    }

}