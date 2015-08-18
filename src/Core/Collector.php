<?php

namespace BitSensor\Core;


/**
 * Responsible for collecting data about the connecting user and the accessed files.
 * @package BitSensor\Core
 */
class Collector {

    /**
     * Collection of contexts.
     *
     * @var Context[]
     */
    private $contextCollection = array();

    /**
     * Collection of inputs.
     *
     * @var Context[]
     */
    private $inputCollection = array();

    /**
     * Collection of errors.
     *
     * @var Error[]
     */
    private $errorCollection = array();
    /**
     * Collection of endpoint contexts.
     *
     * @var Context[]
     */
    private $endpointCollection = array();

    public function __construct() {
    }

    /**
     * Adds a new {@link Context} to the context collection.
     *
     * @param Context $context
     */
    public function addContext(Context $context) {
        $this->contextCollection[] = $context;
    }

    /**
     * Adds a new {@link Context} to the endpoint collection.
     *
     * @param Context $context
     */
    public function addEndpointContext(Context $context) {
        $this->endpointCollection[] = $context;
    }

    /**
     * Adds a new {@link Context} to the input collection.
     *
     * @param Context $input
     */
    public function addInput(Context $input) {
        $this->inputCollection[] = $input;
    }

    /**
     * Adds a new {@link Error} to the error collection.
     *
     * @param Error $error
     */
    public function addError(Error $error) {
        $this->errorCollection[] = $error;
    }

    /**
     * Converts all collections to a JSON encoded string.
     *
     * @param bool $prettyPrint Use whitespace in returned data to format it.
     * @return string JSON encoded string
     */
    public function serialize($prettyPrint = false) {
        $json = $this->toArray();

        return $prettyPrint ? json_encode($json, JSON_PRETTY_PRINT) : json_encode($json);
    }

    /**
     * Converts all collections to a single array.
     *
     * @return array
     */
    public function toArray() {
        $all = array();

        foreach ($this->contextCollection as $context) {
            $all[Context::NAME][] = array(
                'key' => $context->getName(),
                'value' => $context->getValue()
            );
        }

        foreach ($this->endpointCollection as $context) {
            $all[EndpointContext::NAME][] = array(
                'key' => $context->getName(),
                'value' => $context->getValue()
            );
        }

        foreach ($this->inputCollection as $input) {
            $all[InputContext::NAME][] = array(
                'key' => $input->getName(),
                'value' => $input->getValue()
            );
        }


        foreach ($this->errorCollection as $error) {
            $all['errors'][] = $error->toArray();
        }

        return $all;
    }

    public function __toString() {
        return $this->serialize(true);
    }

}