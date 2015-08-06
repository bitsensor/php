<?php

namespace BitSensor\Core;


/**
 * Responsible for collecting data about the connecting user and the accessed files.
 * @package BitSensor\Core
 */
class Collector {

    /**
     * @var Context[]
     */
    private $contextCollection = array();

    /**
     * @var Context[]
     */
    private $inputCollection = array();

    /**
     * @var Error[]
     */
    private $errorCollection = array();

    public function __construct() {
    }

    /**
     * @param Context $context
     */
    public function addContext(Context $context) {
        $this->contextCollection[] = $context;
    }

    public function addInput(Context $input) {
        $this->inputCollection[] = $input;
    }

    /**
     * @param Error $error
     */
    public function addError(Error $error) {
        $this->errorCollection[] = $error;
    }

    /**
     * @param bool $prettyPrint
     * @return string JSON encoded string
     */
    public function serialize($prettyPrint = false) {
        $json = $this->toArray();

        return $prettyPrint ? json_encode($json, JSON_PRETTY_PRINT) : json_encode($json);
    }

    public function toArray() {
        $json = array();

        foreach ($this->contextCollection as $context) {
            $json[$context->getName()] = $context->getValue();
        }

        foreach ($this->inputCollection as $input) {
            $json['input'][$input->getName()] = $input->getValue();
        }


        foreach ($this->errorCollection as $error) {
            $json['errors'][] = $error->toArray();
        }

        return $json;
    }

    public function __toString() {
        return $this->serialize(true);
    }

}